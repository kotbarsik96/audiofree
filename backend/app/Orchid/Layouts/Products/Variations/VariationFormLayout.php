<?php

namespace App\Orchid\Layouts\Products\Variations;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class VariationFormLayout extends Rows
{
  /**
   * Used to create the title of a group of form elements.
   *
   * @var string|null
   */
  protected $title;

  public function __construct()
  {
    $this->title = __('orchid.generalInfo');
  }

  /**
   * Get the fields elements to be displayed.
   *
   * @return Field[]
   */
  protected function fields(): iterable
  {
    $variation = $this->query->get('variation') ?? null;
    $product = $this->query->get('product');

    $id = $variation ? $variation->id : null;
    $name = $variation ? $variation->name : '';
    $price = $variation ? $variation->price : 0;
    $discount = $variation ? $variation->discount : 0;
    $quantity = $variation ? $variation->quantity : 0;
    $image = $variation->exists ? $variation->attachment()->first() : null;

    return [
      Input::make('name')
        ->title(__('orchid.name'))
        ->set('value', $name),
      Input::make('price')
        ->title(__('orchid.product.price'))
        ->mask([
          'alias' => 'currency',
          'suffix' => ' ₽',
          'groupSeparator' => ' ',
          'min' => 0,
          'max' => 999999
        ])
        ->set('value', $price),
      Input::make('discount')
        ->title(__('orchid.product.discount'))
        ->mask([
          'alias' => 'numeric',
          'suffix' => ' %',
          'min' => 0,
          'max' => 100
        ])
        ->set('value', $discount),
      Input::make('quantity')
        ->title(__('orchid.quantity'))
        ->mask([
          'alias' => 'numeric',
          'min' => 0,
          'max' => 1000
        ])
        ->set('value', $quantity),
      Cropper::make('image')
        ->title(__('orchid.product.variationImage'))
        ->path('images/products')
        ->width(300)
        ->height(300)
        ->groups(config('constants.product.variation.image_group'))
        ->set('value', $image ? $image->url : null)
        ->targetId(),
      Input::make('id')
        ->type('hidden')
        ->set('value', $id)
        ->canSee(!!$variation),
      Input::make('product_id')
        ->type('hidden')
        ->set('value', $product->id),

      Button::make(__('orchid.create'))
        ->method('create')
        ->icon('pencil')
        ->canSee(!$variation->exists),
      Button::make(__('orchid.save'))
        ->method('update')
        ->icon('pencil')
        ->canSee($variation->exists),
    ];
  }
}
