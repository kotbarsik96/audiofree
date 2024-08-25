<?php

namespace App\Orchid\Screens\Products;

use App\Models\Product;
use App\Orchid\Layouts\Products\ProductsListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class ProductsListScreen extends Screen
{
  /**
   * Fetch data to be displayed on the screen.
   *
   * @return array
   */
  public function query(): iterable
  {
    return [
      'products' => Product::all()
    ];
  }

  /**
   * The name of the screen displayed in the header.
   *
   * @return string|null
   */
  public function name(): ?string
  {
    return __('general.products');
  }

  /**
   * The screen's action buttons.
   *
   * @return \Orchid\Screen\Action[]
   */
  public function commandBar(): iterable
  {
    return [
      Link::make(__('orchid.product.create'))
        ->icon('bs.plus-circle')
        ->route('platform.product.edit'),
    ];
  }

  /**
   * The screen's layout elements.
   *
   * @return \Orchid\Screen\Layout[]|string[]
   */
  public function layout(): iterable
  {
    return [
      ProductsListLayout::class
    ];
  }

  public function delete(Product $product)
  {
    $product->deleteAndAlert();
  }
}
