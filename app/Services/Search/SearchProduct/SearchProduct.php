<?php

namespace App\Services\Search\SearchProduct;

use App\DTO\Enums\SearchProductEnum;
use App\DTO\SearchProductDTO;
use App\Models\Product;

class SearchProduct
{
  /** @param array<SearchProductResult> $results */
  protected array $results = [];

  protected SearchProductDTO $searchSettings;

  public function __construct(
    protected $searchValue,
    public SearchProductEnum $type
  ) {
    $this->searchSettings = $type->dto();
  }

  public static function search(string|null $searchValue, SearchProductEnum $type)
  {
    if (!$searchValue)
      return [];

    $search = new static(strip_tags($searchValue), $type);

    return $search
      ->byProducts()
      ->getResults();
  }

  public function byProducts()
  {
    array_push(
      $this->results,
      ...$this->searchAndMapProducts()
    );

    return $this;
  }

  public function searchAndMapProducts()
  {
    $products = Product::join('product_variations', 'product_variations.product_id', '=', 'products.id')
      ->join('taxonomy_values as brands', 'brands.id', '=', 'products.brand_id')
      ->join('taxonomy_values as categories', 'categories.id', '=', 'products.category_id')
      ->join('taxonomy_values as types', 'types.id', '=', 'products.type_id')
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
      ])
      ->whereRaw('MATCH(brands.value) AGAINST(? IN BOOLEAN MODE)', [$this->searchValue])
      ->orWhereRaw('MATCH(categories.value) AGAINST(? IN BOOLEAN MODE)', [$this->searchValue])
      ->orWhereRaw('MATCH(types.value) AGAINST(? IN BOOLEAN MODE)', [$this->searchValue])
      ->orWhereRaw('MATCH(products.name, products.slug, products.description) AGAINST(+? IN BOOLEAN MODE)', [$this->searchValue])
      ->orWhereRaw('MATCH(product_variations.name, product_variations.slug) AGAINST(? IN BOOLEAN MODE)', [$this->searchValue])
      ->paginate($this->searchSettings->productResultsPerPage);

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
          $firstDescriptionMatch
        );
      }

      return new SearchProductResult(
        $productResult->product_name.'('.$productResult->variation_name.')',
        __('general.product'),
        $firstDescriptionMatch,
        $this->buildLink('product/'.$productResult->product_slug.'/'.$productResult->variation_slug),
        Product::find($productResult->product_id)->image->url()
      );
    });
  }

  public function byFilters()
  {
    return $this;
  }

  public function buildLink(string $to)
  {
    return env('APP_FRONTEND_LINK').'/'.$to;
  }

  public function getResults()
  {
    return $this->results;
  }
}