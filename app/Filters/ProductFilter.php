<?php

namespace App\Filters;

use App\Filters\QueryFilter;
use App\Models\Taxonomy\TaxonomyValue;

class ProductFilter extends QueryFilter
{
  public function name($value)
  {
    if (!$value) return;

    $this->builder->where('name', 'LIKE', '%' . $value . '%');
  }

  public function search($value)
  {
    if (!$value) return;

    $this->builder->having('full_name', 'LIKE', '%' . $value . '%');
  }

  public function taxonomy($taxonomies, string $slug)
  {
    if (!$taxonomies) return;

    if (is_string($taxonomies)) $taxonomies = $this->paramToArray($taxonomies);
    if (count($taxonomies) < 1) return;

    $taxonomyValues = TaxonomyValue::where('slug', $slug)
      ->whereIn('value_slug', $taxonomies)
      ->get();

    $this->builder->whereIn($slug . '_id', $taxonomyValues->pluck('id'));
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

  public function has_discount(bool | null $hasDiscount = null)
  {
    if ($hasDiscount === null)
      return;
    elseif ($hasDiscount === true)
      $this->builder->where('discount', '>', 0);
    elseif ($hasDiscount === false)
      $this->builder->where('discount', 0)->orWhereNull('discount');
  }

  public function price($range)
  {
    if (!$range) return;

    if (is_string($range)) $range = $this->paramToArray($range);

    $this->builder->having('min_price', '>=', $range[0])
      ->having('max_price', '<=', $range[1]);
  }
}
