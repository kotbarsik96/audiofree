<?php

namespace App\DTO;

use App\Models\ProductInfoValue;

class ProductFilterInfoDTO
{
  public $type = 'info';

  public function __construct(
    public string $slug,
    public string $name,
    public array $values
  )
  {
  }

  public static function load()
  {
    $infoRaw = ProductInfoValue::all(['name', 'value', 'slug']);
    $infoValues = $infoRaw->unique('name');
    $info = collect();
    foreach ($infoValues as $item) {
      $name = $item->name;
      $itemWithValues = [
        'name' => $name,
        'slug' => $item->slug,
        'values' => $infoRaw->where('name', $name)->pluck('value')
      ];
      $info->push($itemWithValues);
    }

    return new static(
      'info',
      'Характеристики',
      $info->toArray()
    );
  }
}