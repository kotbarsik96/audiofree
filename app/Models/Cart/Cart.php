<?php

namespace App\Models\Cart;

use App\Models\BaseModel;
use App\Models\Product\ProductVariation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Cart extends BaseModel
{
  use HasFactory;

  protected $table = 'cart';

  protected $fillable = [
    'user_id',
    'variation_id',
    'is_oneclick',
    'quantity'
  ];

  protected $casts = [
    'current_price' => 'integer'
  ];

  public function variation()
  {
    return $this->hasOne(
      ProductVariation::class,
      'id',
      'variation_id'
    );
  }

  public static function itemOrFail(int $variationId = null, $isOneclick = null)
  {
    if (empty($variationId))
      $variationId = request('variation_id');
    if (empty($isOneclick))
      $isOneclick = request('is_oneclick');

    $item = self::where('variation_id', $variationId)
      ->where('is_oneclick', (int) !!$isOneclick)
      ->where('user_id', auth()->user()->id)
      ->first();

    throw_if(!$item, new NotFoundHttpException(__('abortions.cartItemNotFound')));

    return $item;
  }

  public function scopeCurrentUser(Builder $query, bool $isOneclick = false)
  {
    return $query->where('user_id', auth()->user()->id)
      ->where('is_oneclick', (int) $isOneclick);
  }
}
