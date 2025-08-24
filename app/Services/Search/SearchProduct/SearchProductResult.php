<?php

namespace App\Services\Search\SearchProduct;

class SearchProductResult
{
  /**
   * @param string $title Название найденной сущности
   * @param string $description Описание того, к чему принадлежит сущность, например, "Товар"
   * @param string $match Выделение совпадения. Например, при запросе 'тест': "Поэтому, авто-\<span\>тест\<\/span\>ы очень важны"
   * @param string $link Ссылка на сущность. Например, на товар
   * @param string|null $image Изображение, может отсутствовать
   */
  public function __construct(
    public string $title,
    public string $description,
    public string $match,
    public string $link,
    public $image,
  ) {

  }
}