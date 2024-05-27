<?php

namespace App\Models\Product\Cart;

use App\Models\Product;
use App\Models\Product\ProductVariation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

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

  protected $casts = [
    'current_price' => 'integer'
  ];

  public function scopeByProductVariation(Builder $query, $productId, $variationId, $isOneclick = false)
  {
    $user = User::authUser();

    return $query
      ->where('cart.user_id', $user->id)
      ->where('cart.product_id', $productId)
      ->where('cart.variation_id', $variationId)
      ->where('cart.is_oneclick', $isOneclick);
  }

  public function scopeByProductVariationOrAbort(Builder $query, $productId, $variationId, $isOneclick = false)
  {
    $item = self::byProductVariation($productId, $variationId, $isOneclick)->first();
    if (!$item)
      abort(400, __('abortions.notInCart'));

    return $item;
  }

  public function scopeGetCart(Builder $query, $userId, $isOneclick)
  {
    $query->select([
      'cart.id',
      'cart.variation_id',
      'cart.product_id',
      'cart.quantity',
      'cart.is_oneclick',
      'cart.created_at',
      'cart.updated_at',
      'products.name',
      'product_variation_values.value',
      'product_variation_values.price',
      DB::raw(ProductVariation::getCurrentPriceQuery()),
      'product_variation_values.discount',
      'product_variation_values.price',
      'product_variation_values.image_path'
    ])->leftJoin(
      'product_variation_values',
      'product_variation_values.id',
      '=',
      'cart.variation_id'
    )->leftJoin(
      'products',
      'products.id',
      '=',
      'cart.product_id'
    )
      ->where('cart.user_id', $userId)
      ->where('cart.is_oneclick', $isOneclick);
  }

  public static function takeAwayExtra($cartItems): array
  {
    $takenAway = [];

    // выбрать позиции по связке product_id + variation_id
    $productsVariationsIds = $cartItems->map(function (self $item) {
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

      $cartItem = $cartItems->filter(
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

  public function plusOneOrMore(int $plusQuantity = 1, ProductVariation | null $variation = null)
  {
    if (!$variation)
      $variation = ProductVariation::find($this->variation_id);

    if ($this->quantity + $plusQuantity > $variation->quantity)
      abort(403, __('abortions.notEnoughInStock'));

    $this->update([
      'quantity' => $this->quantity + $plusQuantity
    ]);
  }

  public function minusOne()
  {
    $newQuantity = $this->quantity - 1;
    if ($newQuantity < 1)
      $this->delete();
    else
      $this->update(['quantity' => $newQuantity]);
  }
}
