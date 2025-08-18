<?php

namespace App\DTO;

use App\DTO\Enums\ProductFilterCheckboxTypeEnum;
use App\Models\Taxonomy\Taxonomy;

class ProductFilterCheckboxDTO
{
  public function __construct(
    public ProductFilterCheckboxTypeEnum $type,
    public string $slug,
    public string $name,
    /** @param array{value: string, value_slug: string} $values */
    public array $values = [],
  ) {
  }

  public static function loadFromTaxonomy(string $slug)
  {
    $taxonomy = Taxonomy::select(['id', 'slug', 'name'])
      ->where('slug', $slug)
      ->with('values:id,slug,value,value_slug')
      ->first();

    return new static(
      ProductFilterCheckboxTypeEnum::CHECKBOX,
      $taxonomy->slug,
      $taxonomy->name,
      $taxonomy->values->toArray()
    );
  }

  public static function initHasDiscount()
  {
    return new static(
      ProductFilterCheckboxTypeEnum::CHECKBOX_BOOLEAN,
      'has_discount',
      'Скидка',
      [
        [
          'value' => 'Есть скидка',
          'value_slug' => 'has_discount'
        ]
      ]
    );
  }
}