<?php

namespace App\DTO\Enums;

use App\DTO\SortDTO;
use App\DTO\SortItem;

enum SortEnum: string
{
  case CATALOG = 'catalog';
  case FAVORITES = 'favorites';
  case ORDERS = 'orders';

  public function dto()
  {
    return match ($this) {
      SortEnum::FAVORITES => new SortDTO([
        new SortItem('Дата добавления', 'created_at'),
        new SortItem('Цена', 'current_price'),
        new SortItem('Популярность', 'rating_count'),
        new SortItem('Рейтинг', 'rating_value'),
      ]),

      SortEnum::CATALOG => new SortDTO([
        new SortItem('Популярность', 'rating_count'),
        new SortItem('Цена', 'min_price'),
        new SortItem('Рейтинг', 'rating_value'),
      ]),

      SortEnum::ORDERS => new SortDTO([
        new SortItem('Дата', 'created_at'),
        new SortItem('Стоимость', 'total_cost'),
      ])
    };
  }

  public static function getSortsFromRequest(
    SortEnum $sortEntity,
    $sortBy = 'sort',
    $orderKey = 'sort_order'
  ) {
    $sortsArr = $sortEntity->dto()->sorts;
    $sort = trim(strtolower(request($sortBy, $sortsArr[0]->value)));
    $sortOrder = trim(strtolower(request($orderKey)));
    if ($sortOrder !== 'asc' && $sortOrder !== 'desc')
      $sortOrder = 'asc';

    return [
      'sort' => $sort,
      'sortOrder' => $sortOrder
    ];
  }
}