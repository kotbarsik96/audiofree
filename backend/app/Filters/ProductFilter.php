<?php

namespace App\Filters;

use App\Filters\QueryFilter;

class ProductFilter extends QueryFilter
{
  public function name($value)
  {
    if (!$value) return;

    $this->builder->where('name', 'LIKE', '%' . $value . '%');
  }

  public function taxonomy(array | null $taxonomies, string $type)
  {
    if (!$taxonomies) return;
    if (count($taxonomies) < 1) return;

    $this->builder->whereIn($type, $taxonomies);
  }

  public function brand(array $values)
  {
    $this->taxonomy($values, 'brand');
  }

  public function category(array $values)
  {
    $this->taxonomy($values, 'category');
  }

  public function type(array $values)
  {
    $this->taxonomy($values, 'type');
  }

  public function status(array $values)
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
}
