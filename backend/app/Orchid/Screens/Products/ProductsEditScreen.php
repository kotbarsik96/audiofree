<?php

namespace App\Orchid\Screens\Products;

use Orchid\Screen\Screen;

class ProductsEditScreen extends Screen
{
  /**
   * Fetch data to be displayed on the screen.
   *
   * @return array
   */
  public function query(): iterable
  {
    return [];
  }

  /**
   * The name of the screen displayed in the header.
   *
   * @return string|null
   */
  public function name(): ?string
  {
    return 'ProductsEdit';
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
    return [];
  }
}
