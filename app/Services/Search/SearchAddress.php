<?php

namespace App\Services\Search;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SearchAddress
{
  protected $results = [];

  public function __construct(
    protected $searchValue
  ) {
  }

  public static function search(string $value)
  {
    $search = new static(substr($value, 0, 290));

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

    throw_if(count($this->results) < 1, new NotFoundHttpException(__('abortions.noSearchResults')));

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