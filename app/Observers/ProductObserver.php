<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver
{
  public function deleting(Product $product): void
  {
    foreach($product->variations()->get() as $variation) {
      $variation->delete();
    }
    $product->image()->delete();
  }
}
