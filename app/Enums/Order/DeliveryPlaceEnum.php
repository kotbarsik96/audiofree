<?php

namespace App\Enums\Order;

enum DeliveryPlaceEnum: string {
  case PICKUP_POINT = 'pickup_point';
  case TO_DOOR = 'to_door';
}