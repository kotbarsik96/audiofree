<?php

namespace App\Enums;

enum SortEnum: string
{
  case CATALOG = 'catalog';
  case FAVORITES = 'favorites';
  case ORDERS = 'orders';
}