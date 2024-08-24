<?php

namespace App\Orchid\Screens\Products;

use App\Http\Requests\Product\ProductRequest;
use App\Models\Product;
use App\Models\Taxonomy\Taxonomy;
use App\Validations\ProductValidation;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class ProductsScreen extends Screen
{
  protected $productTaxonomies = [
    'product_status',
    'brand',
    'type',
    'category'
  ];

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
      ModalToggle::make(__('orchid.product.create'))
        ->modal('productModal')
        ->method('store')
        ->icon('plus')
    ];
  }

  /**
   * The screen's layout elements.
   *
   * @return \Orchid\Screen\Layout[]|string[]
   */
  public function layout(): iterable
  {
    $taxonomies = Taxonomy::whereIn('taxonomies.type', $this->productTaxonomies)->get();

    return [
      Layout::table('products', [
        TD::make('name'),
        TD::make(__('orchid.actions'))
          ->alignRight()
          ->render(function(Product $product) {
            return Button::make(__('orchid.product.delete'))
              ->confirm(__('orchid.product.confirmDelete'))
              ->method('delete', ['product' => $product->id]);
          })
      ]),

      Layout::modal('productModal', Layout::rows([
        Input::make('product.name')
          ->title(__('orchid.product.name')),
        // Input::make('product.price')
        //   ->title(__('orchid.product.price'))
        //   ->mask([
        //     'alias' => 'currency',
        //     'suffix' => ' ₽',
        //     'groupSeparator' => ' ',
        //     'min' => 0,
        //     'max' => 999999
        //   ]),
        // Input::make('product.discount')
        //   ->title(__('orchid.product.discount'))
        //   ->mask([
        //     'alias' => 'numeric',
        //     'suffix' => ' %',
        //     'min' => 0,
        //     'max' => 100
        //   ]),
        Select::make('product.status')
          ->options($this->getTaxonomySelectOptions($taxonomies, 'product_status', 'status')),
        Select::make('product.type')
          ->options($this->getTaxonomySelectOptions($taxonomies, 'type', 'type')),
        Select::make('product.brand')
          ->options($this->getTaxonomySelectOptions($taxonomies, 'brand')),
        Select::make('product.category')
          ->options($this->getTaxonomySelectOptions($taxonomies, 'category', 'category')),
      ]))
        ->title(__('orchid.product.creation'))
        ->applyButton(__('orchid.product.create'))
    ];
  }

  /** 
   * Опции списков для таксономий при создании/обновлении товара
   */
  public function getTaxonomySelectOptions($taxonomies, $taxonomyTypeName, $translationKeyPrefix = null)
  {
    return $taxonomies->filter(fn($item) => $item['type'] === $taxonomyTypeName)
      ->mapWithKeys(fn($item) => [
        $item['name'] => $this->getOptionTranslation($item['name'], $translationKeyPrefix)
      ])->toArray();
  }

  /**
   * Перевод для опции таксономии (если не нужен - не передавать $transitionKeyPrefix)
   */
  public function getOptionTranslation($taxonomyType, $translationKeyPrefix = null)
  {
    if ($translationKeyPrefix) {
      return __('db.product.' . $translationKeyPrefix . '.' . $taxonomyType);
    }
    return $taxonomyType;
  }

  /**
   * Создание товара
   */
  public function store(Request $request)
  {
    $taxonomyValidation = ProductValidation::taxonomy();

    $validated = array_merge($request->validate([
      'product.name' => ProductValidation::name(),
      'product.status' => $taxonomyValidation,
      'product.type' => $taxonomyValidation,
      'product.brand' => $taxonomyValidation,
      'product.category' => $taxonomyValidation
    ]));

    $validated = array_merge($validated['product'], [
      'created_by' => auth()->user()->id
    ]);

    Product::create($validated);
  }

  public function delete(Product $product)
  {
    $product->delete();
  }
}
