<?php

namespace App\Orchid\Screens\Products;

use App\Http\Requests\Product\ProductVariationRequest;
use App\Models\Product;
use App\Models\Product\ProductVariation;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class ProductVariationScreen extends Screen
{
  public $variation;
  public $product;

  /**
   * Fetch data to be displayed on the screen.
   *
   * @return array
   */
  public function query(Product $product, ProductVariation $variation): iterable
  {
    $this->variation = $variation;
    $this->product = $product;

    return [
      'variation' => $variation,
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
    return [
      Button::make(__('orchid.delete'))
        ->method('delete')
        ->confirm(__('orchid.product.areYouSureToDeleteVariation'))
        ->canSee($this->variation->exists)
    ];
  }

  /**
   * The screen's layout elements.
   *
   * @return \Orchid\Screen\Layout[]|string[]
   */
  public function layout(): iterable
  {
    $id = $this->variation->exists ? $this->variation->id : null;
    $name = $this->variation->exists ? $this->variation->value : '';
    $price = $this->variation->exists ? $this->variation->price : 0;
    $discount = $this->variation->exists ? $this->variation->discount : 0;
    $quantity = $this->variation->exists ? $this->variation->quantity : 0;

    return [
      Layout::rows([
        Input::make('value')
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
          ->width(300)
          ->height(300)
          ->groups(config('constants.product.variation.image_group'))
          ->targetId(),
        Upload::make('gallery')
          ->title(__('orchid.gallery'))
          ->maxFiles(5)
          ->groups('product_variation_gallery')
          ->acceptedFiles('image/*'),
        Input::make('id')
          ->type('hidden')
          ->set('value', $id)
          ->canSee($this->variation->exists),
        Input::make('product_id')
          ->type('hidden')
          ->set('value', $this->product->id),

        Button::make(__('orchid.create'))
          ->method('create')
          ->icon('pencil')
          ->canSee(!$this->variation->exists),
        Button::make(__('orchid.save'))
          ->method('update')
          ->icon('pencil')
          ->canSee($this->variation->exists),
      ]),
    ];
  }

  public function create(ProductVariationRequest $request)
  {
    $request->merge([
      'created_by' => auth()->user()->id,
      'updated_by' => auth()->user()->id,
    ]);
    $validated = $request->validated();

    $variation = ProductVariation::create($validated);
    $variation->attachSingle(
      config('constants.product.variation.image_group'),
      $request->input('image')
    );
    $variation->attachMany($request->input('gallery'));
    // $variation->attachManyWithDetaching($request->input('images'));

    Alert::info(__('orchid.success'));

    return redirect()->route('platform.product.variation.edit', [$this->product->id, $variation->id]);
  }

  public function update(ProductVariationRequest $request)
  {
    $request->merge([
      'updated_by' => auth()->user()->id,
    ]);
    $validated = $request->validated();

    $this->variation->update($validated);
    if ($request->input('image')) {
      $this->attachSingle(
        config('constants.product.variation.image_group'),
        $request->input('image')
      );
    } else
      $this->detachByGroup(config('constants.product.variation.image_group'));
  }

  public function delete(ProductVariation $variation)
  {
    $variation->detachAll();
    $variation->delete();
  }
}
