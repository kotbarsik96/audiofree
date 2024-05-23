<?php

namespace App\Filters;

use App\Filters\QueryFilter;

class ProductFilter extends QueryFilter
{
  public function name($value)
  {
    if(!$value) return;

    $this->builder->where('name', $value);
  }
}
