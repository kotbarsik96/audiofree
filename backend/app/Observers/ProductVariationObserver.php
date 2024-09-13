<?php

namespace App\Observers;

use App\Models\Product\ProductVariation;

class ProductVariationObserver
{
  public function deleting(ProductVariation $variation)
  {
    $variation->image()->delete();
    $variation->gallery()->delete();
  }
}
