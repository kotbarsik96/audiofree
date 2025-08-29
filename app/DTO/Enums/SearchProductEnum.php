<?php

namespace App\DTO\Enums;

use App\DTO\Enums\Traits\EnumHelper;
use App\DTO\SearchProductDTO;
use App\Services\StringsService;

enum SearchProductEnum: string
{
  use EnumHelper;

  /**
   * Поиск на отдельной странице сайта
   */
  case FULL = 'full';

  /**
   * Поиск через поисковую строку в шапке сайта
   */
  case SEARCHBAR = 'searchbar';

  public function dto()
  {
    return match ($this) {
      static::FULL => new SearchProductDTO(10),

      static::SEARCHBAR => new SearchProductDTO(3)
    };
  }

  public static function fromValue(string $value)
  {
    return match ($value) {
      'full' => static::FULL,

      'searchbar' => static::SEARCHBAR
    };
  }
}