<?php

namespace App\Models\Product\Cart;

use App\Models\Product;
use App\Models\Product\ProductVariation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class Cart extends Model
{
  use HasFactory;

  protected $table = 'cart';

  protected $fillable = [
    'user_id',
    'product_id',
    'variation_id',
    'is_oneclick',
    'quantity'
  ];

  public function scopeByProductVariation(Builder $query, $productId, $variationId)
  {
    $user = User::authUser();

    return $query
      ->where('cart.user_id', $user->id)
      ->where('cart.product_id', $productId)
      ->where('cart.variation_id', $variationId);
  }

  public function scopeByProductVariationOrAbort($productId, $variationId)
  {
    $item = self::byProductVariation($productId, $variationId)->first();
    if (!$item)
      abort(400, __('abortions.notInCart'));

    return $item;
  }

  public function takeAwayExtra(): array
  {
    $takenAway = [];

    // выбрать позиции по связке product_id + variation_id
    $productsVariationsIds = $this->map(function (self $item) {
      return [
        'product_id' => $item->product_id,
        'variation_id' => $item->variation_id
      ];
    })->unique(function (array $item) {
      return $item['product_id'] . $item['variation_id'];
    });

    // пройтись по каждой позиции
    foreach ($productsVariationsIds as $productVariationLink) {
      $prodId = $productVariationLink['product_id'];
      $varId = $productVariationLink['variation_id'];

      $cartItem = $this->filter(
        fn ($item) =>
        $item->product_id === $prodId && $item->variation_id === $varId
      )->first();

      $variation = ProductVariation::find($varId);
      $diff = $cartItem->quantity - $variation->quantity;
      // если товаров в корзине больше, чем товаров в наличии, отнять разницу у позиции
      if ($diff > 0) {
        $product = Product::find($prodId);
        $cartItem->update([
          'quantity' => $cartItem->quantity - $diff
        ]);
        $takenAway[] = $product->name . ' (' .  $variation->value . ')';
      }
    }

    return $takenAway;
  }

  public function plusOne()
  {
    $this->update([
      'quantity' => $this->quantity + 1
    ]);
  }

  public function minusOne()
  {
    $newQuantity = $this->quantity - 1;
    if ($newQuantity <= 0)
      $this->delete();
    else
      $this->update(['quantity' => $newQuantity]);
  }
}
