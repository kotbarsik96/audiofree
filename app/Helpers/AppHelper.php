<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;

class AppHelper
{
  public static function array_find($array, $callback, $defaultValue = false)
  {
    $item = current(array_filter($array, $callback));
    if (!$item)
      return $defaultValue;
    return $item;
  }

  public static function isJoined(Builder $query, string $table)
  {
    $joins = collect($query->getQuery()->joins);
    return $joins->pluck('table')->contains($table);
  }
}
