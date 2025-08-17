<?php

namespace App\DTO\Enums;

use App\DTO\ProductFilterCheckboxDTO;
use App\DTO\ProductFilterInfoDTO;
use App\DTO\ProductFilterRangeDTO;

enum ProductFilterEnum: string
{
  case BRAND = 'brand';
  case CATEGORY = 'category';
  case PRODUCT_STATUS = 'product_status';
  case TYPE = 'type';
  case PRICE = 'price';
  case INFO = 'info';
  case HAS_DISCOUNT = 'has_discount';

  public function dto()
  {
    return match ($this) {
      static::BRAND => ProductFilterCheckboxDTO::loadFromTaxonomy('brand'),

      static::CATEGORY => ProductFilterCheckboxDTO::loadFromTaxonomy('category'),

      static::PRODUCT_STATUS => ProductFilterCheckboxDTO::loadFromTaxonomy('product_status'),

      static::TYPE => ProductFilterCheckboxDTO::loadFromTaxonomy('type'),

      static::PRICE => ProductFilterRangeDTO::loadPrice(),

      static::INFO => ProductFilterInfoDTO::load(),

      static::HAS_DISCOUNT => ProductFilterCheckboxDTO::initHasDiscount()
    };
  }
}