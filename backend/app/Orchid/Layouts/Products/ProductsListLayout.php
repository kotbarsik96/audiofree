<?php

namespace App\Orchid\Layouts\Products;

use App\Models\Product;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ProductsListLayout extends Table
{
  /**
   * Data source.
   *
   * The name of the key to fetch it from the query.
   * The results of which will be elements of the table.
   *
   * @var string
   */
  protected $target = 'products';

  /**
   * Get the table cells to be displayed.
   *
   * @return TD[]
   */
  protected function columns(): iterable
  {
    return [
      TD::make('name')
        ->render(function (Product $product) {
          return Link::make($product->name)
            ->route('platform.product.edit', $product);
        }),
      TD::make(__('orchid.actions'))
        ->alignRight()
        ->render(function (Product $product) {
          return Button::make(__('orchid.product.delete'))
            ->confirm(__('orchid.product.confirmDelete'))
            ->method('delete', ['product' => $product->id]);
        }),
    ];
  }
}
