<?php

namespace App\Services;

class SortService
{
  public static function getSortsFromQuery($sortsArr, $sortKey = 'sort', $orderKey = 'sort_order')
  {
    $defaultSort = self::getDefaultSort($sortsArr);
    $sort = request($sortKey, $defaultSort);
    $sortOrder = trim(strtolower(request($orderKey)));
    if ($sortOrder !== 'asc' && $sortOrder !== 'desc') $sortOrder = 'asc';

    return [
      'sort' => $sort,
      'sortOrder' => $sortOrder
    ];
  }

  public static function getDefaultSort(iterable $arr): string
  {
    return $arr[0]['value'];
  }
}
