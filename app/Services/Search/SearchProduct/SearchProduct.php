<?php

namespace App\Services\Search\SearchProduct;

use App\DTO\Enums\SearchProductEnum;
use App\DTO\ProductFilterCheckboxDTO;
use App\DTO\ProductFilterInfoDTO;
use App\DTO\ProductFilterRangeDTO;
use App\DTO\SearchProductDTO;
use App\Models\Product;
use Illuminate\Http\Request;
use App\DTO\Enums\ProductFilterEnum;

class SearchProduct
{
  /** @param array<SearchProductResult> $results */
  protected array $results = [];
  protected $regexp;
  protected int $resultsCount = 0;
  protected SearchProductDTO $searchSettings;

  public function __construct(
    protected $searchValue,
    public SearchProductEnum $type,
    public Request $request
  ) {
    $this->searchValue = trim($this->searchValue);
    $this->searchSettings = $type->dto();
  }

  public static function getSearchableSlugs()
  {
    return ['brand', 'category', 'type', 'price'];
  }

  public static function isSearchableFilterItem($filterItemSlug)
  {
    return in_array($filterItemSlug, static::getSearchableSlugs());
  }

  public function getRegexp()
  {
    $escaped = preg_quote($this->searchValue, '/');
    return '/('.$escaped.')/ui';
  }

  public static function search(
    string|null $searchValue,
    SearchProductEnum $type,
    Request $request
  ) {
    if (!$searchValue)
      return new static('', $type, $request);

    $search = new static(strip_tags($searchValue), $type, $request);

    $search
      ->byFilters()
      ->byProducts();

    return $search;
  }

  public function byProducts()
  {
    array_push(
      $this->results,
      ...$this->collectAndMapProducts()
    );

    return $this;
  }

  public function collectAndMapProducts()
  {
    $baseQuery = Product::join('product_variations', 'product_variations.product_id', '=', 'products.id')
      ->join('taxonomy_values as brands', 'brands.id', '=', 'products.brand_id')
      ->join('taxonomy_values as categories', 'categories.id', '=', 'products.category_id')
      ->join('taxonomy_values as types', 'types.id', '=', 'products.type_id');

    $productsQuery = (clone $baseQuery)
      ->select([
        'products.id as product_id',
        'products.name as product_name',
        'products.slug as product_slug',
        'products.description as description',
        'product_variations.id as variation_id',
        'product_variations.name as variation_name',
        'product_variations.slug as variation_slug',
        'brands.value as brand',
        'categories.value as category',
        'types.value as type'
      ])->where(function ($query) {
        $query->whereRaw('MATCH(brands.value) AGAINST(? IN BOOLEAN MODE)', [$this->searchValue])
          ->orWhereRaw('MATCH(categories.value) AGAINST(? IN BOOLEAN MODE)', [$this->searchValue])
          ->orWhereRaw('MATCH(types.value) AGAINST(? IN BOOLEAN MODE)', [$this->searchValue])
          ->orWhereRaw('MATCH(products.name, products.slug, products.description) AGAINST(+? IN BOOLEAN MODE)', [$this->searchValue])
          ->orWhereRaw('MATCH(product_variations.name, product_variations.slug) AGAINST(? IN BOOLEAN MODE)', [$this->searchValue]);
      });

    $this->resultsCount += (clone $productsQuery)->count();
    $products = $productsQuery->paginate($this->searchSettings->productResultsPerPage);

    return $products->map(function ($productResult) {
      $description = strip_tags($productResult->description);
      $regex = '/(\b\S+\b\s+)?(\S+)?'.$this->searchValue.'(\S+)?(\s+\b\S+\b)?/iu';

      preg_match_all($regex, $description, $matches);
      $firstDescriptionMatch = '';
      if (!empty($matches[0]) && !empty($matches[0][0])) {
        $firstDescriptionMatch = $matches[0][0];
        $firstDescriptionMatch = preg_replace(
          '/('.$this->searchValue.')/',
          '<span>$1</span>',
          '...'.$firstDescriptionMatch.'...'
        );
      }

      return new SearchProductResult(
        $productResult->product_name.' ('.$productResult->variation_name.')',
        __('general.product'),
        $firstDescriptionMatch,
        $this->buildLink('product/'.$productResult->product_slug.'/'.$productResult->variation_slug),
        Product::find($productResult->product_id)->image->url()
      );
    });
  }

  public function byFilters()
  {
    $byFiltersResults = $this->collectFiltersResults();

    $page = $this->request->get('page') ? intval($this->request->get('page')) : 1;

    if ($page === 1) {
      array_push(
        $this->results,
        ...$byFiltersResults
      );
    }

    $this->resultsCount = $this->resultsCount + count($byFiltersResults);

    return $this;
  }

  public function collectFiltersResults()
  {
    $results = [];

    collect(ProductFilterEnum::dtoCases($this->request))
      ->each(
        function (ProductFilterCheckboxDTO|ProductFilterRangeDTO|ProductFilterInfoDTO $filterItem) use (&$results) {
          if ($filterItem instanceof ProductFilterCheckboxDTO) {
            array_push($results, ...$this->collectFilterResultsByCheckboxes($filterItem));
          }
          if ($filterItem instanceof ProductFilterRangeDTO) {
            array_push($results, ...$this->collectFilterResultsByRanges($filterItem));
          }
        }
      );

    return $results;
  }

  public function collectFilterResultsByCheckboxes(ProductFilterCheckboxDTO $filterItem)
  {
    $results = [];

    if ($this->isSearchableFilterItem($filterItem->slug)) {
      foreach ($filterItem->values as $valueData) {
        $matchValueOrSlug = preg_match(
          $this->getRegexp(),
          $valueData['value']
        ) || preg_match(
          $this->getRegexp(),
          $valueData['value_slug']
        );

        if ($matchValueOrSlug) {
          $newResult = new SearchProductResult(
            $filterItem->name.' '.$valueData['value'],
            __('general.catalog'),
            '',
            $this->buildLink('catalog?'.$filterItem->slug.'='.$valueData['value_slug']),
            $valueData['image']
          );

          array_push($results, $newResult);
        }
      }
    }

    return $results;
  }

  public function collectFilterResultsByRanges(ProductFilterRangeDTO $filterItem)
  {
    $results = [];
    $searchNumber = intval(preg_replace('/\D+/', '', $this->searchValue));
    $isNumberSearched = !!$searchNumber;
    $units = $filterItem->units ?? '';

    if ($this->isSearchableFilterItem($filterItem->slug) && $isNumberSearched) {
      if ($searchNumber <= $filterItem->max) {
        $newResult = new SearchProductResult(
          __('general.upToNumber', ['number' => $searchNumber]).' '.$units,
          __('general.catalog'),
          '',
          $this->buildLink('catalog?max_'.$filterItem->slug.'='.$searchNumber),
          null
        );

        array_push($results, $newResult);
      }

      if ($searchNumber >= $filterItem->min) {
        $newResult = new SearchProductResult(
          __('general.fromNumber', ['number' => $searchNumber]).' '.$units,
          __('general.catalog'),
          '',
          $this->buildLink('catalog?min_'.$filterItem->slug.'='.$searchNumber),
          null
        );

        array_push($results, $newResult);
      }
    }

    return $results;
  }

  public function buildLink(string $to)
  {
    // return env('APP_FRONTEND_LINK').'/'.$to;
    return "/$to";
  }

  public function getResults()
  {
    return $this->results;
  }

  public function getPaginationData()
  {
    return [
      'current_page' => intval($this->request->get('page') ?? 1),
      'total_items' => $this->resultsCount,
      'total_pages' => round(
        $this->resultsCount / $this->searchSettings->productResultsPerPage,
        0,
        PHP_ROUND_HALF_UP
      ),
      'per_page' => $this->searchSettings->productResultsPerPage
    ];
  }
}