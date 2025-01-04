<?php

namespace App\Orchid\Layouts\Products\Variations;

use App\Models\Product\ProductVariation;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class VariationsListLayout extends Table
{
  /**
   * Data source.
   *
   * The name of the key to fetch it from the query.
   * The results of which will be elements of the table.
   *
   * @var string
   */
  protected $target = 'variations';

  /**
   * Get the table cells to be displayed.
   *
   * @return TD[]
   */
  protected function columns(): iterable
  {
    $product = $this->query->get('product');

    return [
      TD::make(__('orchid.product.variation'))
        ->render(function (ProductVariation $variation) use ($product) {
          return Link::make($variation->name)
            ->route('platform.product.variation.edit', [$product->id, $variation->id]);
        }),
      TD::make(__('orchid.actions'))
        ->render(function (ProductVariation $variation) {
          return Button::make(__('orchid.delete'))
            ->confirm(__('orchid.product.areYouSureToDeleteVariation'))
            ->method('deleteVariation', ['variation' => $variation->id]);
        })
    ];
  }
}
