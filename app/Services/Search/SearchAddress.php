<?php

namespace App\Services\Search;

use App\Models\Product;

class SearchAddress
{
  protected $results = [];

  public function __construct(
    protected $searchValue
  ) {
  }

  public static function search(string $value)
  {
    $search = new static($value);

    return $search
      ->searchDadata()
      ->getResults();
  }

  public function searchDadata()
  {
    $token = env('DADATA_API_KEY');
    $secret = env('DADATA_SECRET_KEY');
    $dadata = new \Dadata\DadataClient($token, $secret);

    $this->results = array_map(
      fn($item) => $item['value'],
      $dadata->suggest("address", $this->searchValue)
    );

    return $this;
  }

  /**
   * Результат формируется посредством вызова методов поиска
   */
  public function getResults()
  {
    return $this->results;
  }
}