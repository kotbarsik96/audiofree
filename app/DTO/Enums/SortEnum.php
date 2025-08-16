<?php

namespace App\DTO\Enums;

use App\DTO\SortDTO;
use App\DTO\Enums\Catalogs\SortCatalog;

enum SortEnum: string
{
  case CATALOG = 'catalog';
  case FAVORITES = 'favorites';
  case ORDERS = 'orders';

  public function dto()
  {
    return match ($this) {
      SortEnum::FAVORITES => new SortDTO([
        new SortCatalog('Дата добавления', 'created_at'),
        new SortCatalog('Цена', 'current_price'),
        new SortCatalog('Популярность', 'rating_count'),
        new SortCatalog('Рейтинг', 'rating_value'),
      ]),

      SortEnum::CATALOG => new SortDTO([
        new SortCatalog('Популярность', 'rating_count'),
        new SortCatalog('Цена', 'min_price'),
        new SortCatalog('Рейтинг', 'rating_value'),
      ]),

      SortEnum::ORDERS => new SortDTO([
        new SortCatalog('Дата', 'created_at'),
        new SortCatalog('Стоимость', 'total_cost'),
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