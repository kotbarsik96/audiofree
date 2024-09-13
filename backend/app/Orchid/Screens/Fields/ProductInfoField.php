<?php

namespace App\Orchid\Screens\Fields;

use Orchid\Screen\Field;

class ProductInfoField extends Field
{
  protected $view = 'platform.ProductInfo';

  public $attributes = [
    'info' => []
  ];

  public function setInfo(iterable $info)
  {
    $this->attributes['info'] = $info;

    return $this;
  }
}
