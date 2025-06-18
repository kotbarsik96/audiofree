<?php

namespace App\Filters;

use App\Filters\QueryFilter;
use App\Models\ProductInfoValue;
use App\Models\Taxonomy\TaxonomyValue;
use Illuminate\Database\Query\Builder;

class ProductFilter extends QueryFilter
{
  protected $possibleInfos = null;

  public function handleDynamicQuery(string $slug, $values)
  {
    if (!$values)
      return;

    if (is_string($values)) {
      $values = $this->paramToArray($values);
    }

    // получить и сохранить список существующих характеристик
    if (!$this->possibleInfos) {
      $this->possibleInfos = ProductInfoValue::all(['slug'])
        ->pluck('slug')
        ->toArray();
    }

    // если текущий $slug является названием характеристики, фильтровать по ней
    if (in_array($slug, $this->possibleInfos)) {
      $this->builder
        ->whereIn('products.id', function (Builder $query) use ($slug, $values) {
          $query->select('products.id')
            ->from('products')
            ->join('products_info', 'products_info.product_id', '=', 'products.id')
            ->where('products_info.slug', $slug)
            ->whereIn('products_info.value', $values);
        });
    }
  }

  public function name($value)
  {
    if (!$value)
      return;

    $this->builder->where('name', 'LIKE', '%'.$value.'%');
  }

  public function search($value)
  {
    if (!$value)
      return;

    $this->builder->having('full_name', 'LIKE', '%'.$value.'%');
  }

  public function taxonomy($taxonomies, string $slug)
  {
    if (!$taxonomies)
      return;

    if (is_string($taxonomies))
      $taxonomies = $this->paramToArray($taxonomies);
    if (count($taxonomies) < 1)
      return;

    $taxonomyValues = TaxonomyValue::where('slug', $slug)
      ->whereIn('value_slug', $taxonomies)
      ->get();

    $this->builder->whereIn($slug.'_id', $taxonomyValues->pluck('id'));
  }

  public function brand($values)
  {
    $this->taxonomy($values, 'brand');
  }

  public function category($values)
  {
    $this->taxonomy($values, 'category');
  }

  public function type($values)
  {
    $this->taxonomy($values, 'type');
  }

  public function status($values)
  {
    $this->taxonomy($values, 'status');
  }

  public function has_discount(bool|null $hasDiscount = null)
  {
    if ($hasDiscount === null)
      return;
    elseif ($hasDiscount === true)
      $this->builder->where('discount', '>', 0);
    elseif ($hasDiscount === false)
      $this->builder->where('discount', 0)->orWhereNull('discount');
  }

  public function min_price($value)
  {
    if (is_string($value))
      $value = (int) $value;
    $this->builder->having('min_price', '>=', $value);
  }

  public function max_price($value)
  {
    if (is_string($value))
      $value = (int) $value;
    $this->builder->having('max_price', '<=', $value);
  }
}
