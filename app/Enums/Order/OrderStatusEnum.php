<?php

namespace App\Enums\Order;

enum OrderStatusEnum: string
{
  case PREPARING = 'preparing';
  case DELIVERING = 'delivering';
  case AWAITING_PICKUP = 'awaiting_pickup';
  case PICKED_UP = 'picked_up';
  case CANCELLED = 'cancelled';
}