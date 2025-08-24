<?php

namespace App\Services\Search\SearchProduct;

use App\DTO\Enums\SearchProductEnum;
use App\DTO\SearchProductDTO;
use App\Models\Product;
use App\Models\Product\ProductVariation;
use Illuminate\Support\Collection;

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
      ...$this->getProductVariations()->toArray(),
      ...$this->getProducts()->toArray()
    );

    return $this;
  }

  /**
   * @return Collection<SearchProductResult>
   */
  public function getProducts()
  {
    return Product::whereFullText(
      ['name', 'slug', 'description'],
      $this->searchValue
    )
      ->paginate($this->searchSettings->productResultsPerPage)
      ->map(function (Product $product) {
        $description = strip_tags($product->description);
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
          $product->name,
          __('general.product'),
          $firstDescriptionMatch,
          $this->buildLink('product/'.$product->slug.'/'.$product->firstVariation->slug),
          $product->image
        );
      });
  }

  /**
   * @return Collection<SearchProductResult>
   */
  public function getProductVariations()
  {
    return ProductVariation::whereFullText(['name', 'slug'], $this->searchValue)
      ->paginate($this->searchSettings->productResultsPerPage)
      ->map(function (ProductVariation $variation) {
        return new SearchProductResult(
          $variation->product->name.' ('.$variation->name.')',
          __('general.product'),
          '',
          $this->buildLink('product/'.$variation->product->slug.'/'.$variation->slug),
          $variation->image
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