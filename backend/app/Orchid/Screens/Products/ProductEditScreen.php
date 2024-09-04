<?php

namespace App\Orchid\Screens\Products;

use App\Http\Requests\Product\ProductRequest;
use App\Models\Product;
use App\Models\Product\ProductVariation;
use App\Models\Taxonomy\Taxonomy;
use App\Models\Taxonomy\TaxonomyValue;
use App\Orchid\Layouts\Products\Variations\VariationsListLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class ProductEditScreen extends Screen
{
  public $product;

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
  public function query(Product $product): iterable
  {
    $product->load('attachment');
    $image = $product->attachment(config('constants.product.image_group'))->first();

    return [
      'product' => $product,
      'variations' => $product->variations()->get(),
      'image' => $image ? $image->url() : null
    ];
  }

  /**
   * @var $attrName - название атрибута товара
   */
  public function getAttr($attrName)
  {
    return $this->product->exists ? $this->product->$attrName : null;
  }

  /**
   * The name of the screen displayed in the header.
   *
   * @return string|null
   */
  public function name(): ?string
  {
    return $this->product->exists ? __('orchid.product.editing') : __('orchid.product.creation');
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
        ->icon('trash')
        ->method('delete')
        ->confirm(__('orchid.product.areYouSureToDelete'))
        ->canSee($this->product->exists),
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
      Layout::rows([
        Input::make('name')
          ->set('value', $this->getAttr('name'))
          ->title(__('orchid.product.name')),

        Select::make('status')
          ->options($this->getTaxonomyOptions('product_status'))
          ->set('value', $this->getAttr('status'))
          ->title(__('Status')),

        Select::make('type')
          ->options($this->getTaxonomyOptions('type'))
          ->set('value', $this->getAttr('type'))
          ->title(__('Type')),

        Select::make('brand')
          ->options($this->getTaxonomyOptions('brand'))
          ->set('value', $this->getAttr('brand'))
          ->title(__('Brand')),

        Select::make('category')
          ->options($this->getTaxonomyOptions('category'))
          ->set('value', $this->getAttr('category'))
          ->title(__('Category')),

        Quill::make('description')
          ->title(__('Description'))
          ->set('value', $this->getAttr('description')),

        Cropper::make('image')
          ->title(__('orchid.product.image'))
          ->path('images/products')
          ->width(300)
          ->height(300)
          ->groups(config('constants.product.image_group'))
          ->targetId(),

        Button::make(__('orchid.create'))
          ->icon('pencil')
          ->method('create')
          ->canSee(!$this->product->exists),

        Button::make(__('orchid.save'))
          ->icon('pencil')
          ->method('update')
          ->canSee($this->product->exists),

        Input::make('id')
          ->type('hidden')
          ->set('value', $this->getAttr('id'))
          ->canSee($this->product->exists),

      ])->title(__('orchid.product.generalInfo')),

      Layout::block([
        Layout::rows([
          Link::make(__('orchid.product.variation'))
            ->route('platform.product.variation.edit', [$this->getAttr('id') ?? 0])
            ->icon('plus')
            ->canSee($this->product->exists)
        ]),

        VariationsListLayout::class
      ])->vertical()
        ->title(__('orchid.product.variations')),
    ];
  }

  public function getTaxonomyOptions(string $slug)
  {
    $taxonomy = Taxonomy::where('slug', $slug)
      ->first();
    return $taxonomy
      ? $taxonomy->values()->get()->pluck('value')
      : [];
  }

  public function create(ProductRequest $request)
  {
    $request->merge([
      'created_by' => auth()->user()->id,
      'updated_by' => auth()->user()->id,
    ]);
    $validated = $request->validated();
    $product = Product::create($validated);

    $product->attachSingle(config('constants.product.image_group'), $request->input('image'));

    Alert::info(__('orchid.success'));

    return redirect()->route('platform.product.edit', ['product' => $product]);
  }

  public function update(ProductRequest $request)
  {
    $request->merge([
      'updated_by' => auth()->user()->id,
    ]);
    $validated = $request->validated();

    $this->product->update($validated);

    if ($request->input('image')) {
      $this->product->attachSingle(
        config('constants.product.image_group'),
        $request->input('image')
      );
    } else
      $this->product->detachByGroup(config('constants.product.image_group'));

    Alert::info(__('orchid.success'));
  }

  public function delete(Product $product)
  {
    $product->deleteAndAlert();

    return redirect()->route('platform.products');
  }

  public function deleteVariation(ProductVariation $variation)
  {
    $variation->detachAndDelete();

    Alert::info(__('orchid.success'));
  }
}
