<?php

namespace App\DTO;

class SearchProductDTO
{
  public function __construct(
    public int $productResultsPerPage
  ) {
  }
}