<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class QueryFilter
{
  public $request;
  protected $builder;
  protected $delimeter = ",";

  public function __construct(Request $request)
  {
    $this->request = $request;
  }

  public function queries()
  {
    return $this->request->input();
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

  // для случая, когда ожидается массив, а приходит строка
  public function convertToArray(array | string | null $input)
  {
    if(!$input) return $input;
  }
}
