<?php

namespace App\Helpers;

class AppHelper
{
  public static function array_find($array, $callback, $defaultValue = false)
  {
    $item = current(array_filter($array, $callback));
    if(!$item) return $defaultValue;
  }
}
