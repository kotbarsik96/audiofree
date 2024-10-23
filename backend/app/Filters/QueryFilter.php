<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class QueryFilter
{
  public $request;
  protected $builder;
  protected $delimeter = ",";
  protected $excludedQueries = [];

  public function __construct(Request $request)
  {
    $this->request = $request;
  }

  public function excludeQueries(?array $queries)
  {
    if (!$queries) return;

    $this->excludedQueries = array_merge($this->excludedQueries, $queries);

    return $this;
  }

  public function queries()
  {
    return array_filter($this->request->query(), function($key){
      return !in_array($key, $this->excludedQueries);
    }, ARRAY_FILTER_USE_KEY);
  }

  public function apply(Builder $builder)
  {
    $this->builder = $builder;

    foreach ($this->queries() as $name => $value) {
      if (method_exists($this, $name)) {
        call_user_func_array([$this, $name], [$value]);
      }
    }

    return $this->builder;
  }

  public function paramToArray($param)
  {
    return explode($this->delimeter, $param);
  }
}
