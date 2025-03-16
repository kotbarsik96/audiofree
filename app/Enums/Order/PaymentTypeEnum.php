<?php

namespace App\Enums\Order;

enum PaymentTypeEnum: string {
  case CASH = 'cash';
  case CARD = 'card';
}