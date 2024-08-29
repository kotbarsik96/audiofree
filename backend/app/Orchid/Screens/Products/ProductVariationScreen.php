<?php

namespace App\Orchid\Screens\Products;

use App\Http\Requests\Product\ProductVariationRequest;
use App\Models\Product;
use App\Models\Product\ProductVariation;
use App\Orchid\Layouts\Products\Variations\VariationFormLayout;
use App\Orchid\Layouts\Products\Variations\VariationGalleryLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
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
    $variation->load('attachment');
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
    return [
      VariationFormLayout::class,
      // Layout::block(VariationGalleryLayout::class)
      //   ->vertical(true)
      //   ->title(__('orchid.gallery'))
      //   ->canSee($this->variation->exists),
      Layout::view('platform.product.VariationGallery')
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
      $this->variation->attachSingle(
        config('constants.product.variation.image_group'),
        $request->input('image')
      );
    } else
      $this->detachByGroup(config('constants.product.variation.image_group'));

    Alert::info(__('orchid.success'));
  }

  public function delete(ProductVariation $variation)
  {
    $variation->detachAll();
    $variation->delete();
  }

  public function saveGallery(Request $request)
  {
    $gallery = $request->input('gallery');

    $this->variation->attachManyWithDetaching(
      config('constants.product.variation.gallery_group'),
      $gallery ?? []
    );
  }
}
