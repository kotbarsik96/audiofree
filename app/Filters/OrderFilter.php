<?php

namespace App\Filters;

class OrderFilter extends QueryFilter
{
  public function order_status($status = null)
  {
    if ($status) {
      $this->builder->where('order_status', $status);
    }
  }

  public function desired_payment_type($type = null)
  {
    if ($type) {
      $this->builder->where('desired_payment_type', $type);
    }
  }
}