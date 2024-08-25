<?php

namespace App\Orchid\Screens\Products;

use App\Models\Product;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class ProductVariationScreen extends Screen
{
  public $product;

  /**
   * Fetch data to be displayed on the screen.
   *
   * @return array
   */
  public function query(Product $product): iterable
  {
    return [
      'product' => $product
    ];
  }

  /**
   * The name of the screen displayed in the header.
   *
   * @return string|null
   */
  public function name(): ?string
  {
    return __('orchid.product.variation');
  }

  /**
   * The screen's action buttons.
   *
   * @return \Orchid\Screen\Action[]
   */
  public function commandBar(): iterable
  {
    return [];
  }

  /**
   * The screen's layout elements.
   *
   * @return \Orchid\Screen\Layout[]|string[]
   */
  public function layout(): iterable
  {
    return [
      Layout::rows([
        Input::make('product.price')
          ->title(__('orchid.product.price'))
          ->mask([
            'alias' => 'currency',
            'suffix' => ' ₽',
            'groupSeparator' => ' ',
            'min' => 0,
            'max' => 999999
          ]),
        Input::make('product.discount')
          ->title(__('orchid.product.discount'))
          ->mask([
            'alias' => 'numeric',
            'suffix' => ' %',
            'min' => 0,
            'max' => 100
          ]),
      ])
    ];
  }
}
