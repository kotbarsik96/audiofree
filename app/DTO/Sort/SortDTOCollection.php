<?php

namespace App\DTO\Sort;

use App\DTO\DTOCollection;
use App\Enums\SortEnum;
use App\DTO\Sort\SortDTO;

/**
 * @extends DTOCollection<SortDTO>
 */
class SortDTOCollection extends DTOCollection
{
  public static function getDTO($key)
  {
    return match ($key) {
      SortEnum::FAVORITES => new SortDTO([
        [
          'label' => 'Дата добавления',
          'value' => 'created_at'
        ],
        [
          'label' => 'Цена',
          'value' => 'current_price'
        ],
        [
          'label' => 'Популярность',
          'value' => 'rating_count'
        ],
        [
          'label' => 'Рейтинг',
          'value' => 'rating_value'
        ],
      ]),
      SortEnum::CATALOG => new SortDTO([
        [
          'label' => 'Популярность',
          'value' => 'rating_count'
        ],
        [
          'label' => 'Цена',
          'value' => 'min_price'
        ],
        [
          'label' => 'Рейтинг',
          'value' => 'rating_value'
        ]
      ]),
      SortEnum::ORDERS => new SortDTO([
        [
          'label' => 'Дата',
          'value' => 'created_at'
        ],
        [
          'label' => 'Стоимость',
          'value' => 'total_cost'
        ]
      ])
    };
  }

  public static function getSortsFromRequest(
    SortEnum|string $sortEntity,
    $sortBy = 'sort',
    $orderKey = 'sort_order'
  ) {
    $sortsArr = static::getDTO($sortEntity)->sorts;
    $sort = trim(strtolower(request($sortBy, $sortsArr[0]['value'])));
    $sortOrder = trim(strtolower(request($orderKey)));
    if ($sortOrder !== 'asc' && $sortOrder !== 'desc')
      $sortOrder = 'asc';

    return [
      'sort' => $sort,
      'sortOrder' => $sortOrder
    ];
  }
}