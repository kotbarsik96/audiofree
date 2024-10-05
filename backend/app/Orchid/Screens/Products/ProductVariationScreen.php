<?php

namespace App\Orchid\Screens\Products;

use App\Http\Requests\Product\ProductVariationRequest;
use App\Models\Product;
use App\Models\Product\ProductVariation;
use App\Orchid\Layouts\Products\Variations\VariationFormLayout;
use App\Orchid\Layouts\Products\Variations\VariationsListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class ProductVariationScreen extends Screen
{
  public $variation;

  public $product;

  public $maxGalleryImages;

  public $image;

  public function permission(): ?iterable
  {
    return [
      'platform.systems.products'
    ];
  }

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
    $this->maxGalleryImages = config('constants.product.variation.max_gallery_images');

    return [
      'variation' => $variation,
      'product' => $product,
      'variations' => $product->variations()->get(),
      'image_id' => $variation->image_id,
      'gallery' => $variation
        ->attachment(config('constants.product.variation.gallery_group'))
        ->get()
        ->pluck('id')
        ->toArray()
    ];
  }

  /**
   * The name of the screen displayed in the header.
   *
   * @return string|null
   */
  public function name(): ?string
  {
    return __('orchid.product.variationFor') . $this->product->name;
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
        ->canSee($this->variation->exists),

      Link::make($this->product->name)
        ->route('platform.product.edit', [$this->product->id])
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

      Layout::rows([
        Upload::make('gallery')
          ->path('images/products')
          ->title(__('orchid.gallery'))
          ->maxFiles($this->maxGalleryImages)
          ->acceptedFiles('image/*')
          ->groups(config('constants.product.variation.gallery_group')),

        Button::make(__('orchid.save'))
          ->method('saveGallery')
      ])->title(__('orchid.gallery'))
        ->canSee($this->variation->exists),

      VariationsListLayout::class,
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

    Alert::info(__('orchid.success'));
  }

  public function delete()
  {
    $this->variation->detachAndDelete();

    Alert::info(__('orchid.success'));

    return redirect()->route(
      'platform.product.edit',
      ['product' => $this->product->id]
    );
  }

  public function saveGallery(Request $request)
  {
    $galleryMaxImages = config('constants.product.variation.max_gallery_images');

    $gallery = collect($request->input('gallery') ?? [])
      ->slice(0, $galleryMaxImages)
      ->toArray();

    $this->variation->attachment()->syncWithoutDetaching($gallery);

    Alert::info(__('orchid.success'));
  }
}
