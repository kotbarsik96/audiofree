<?php

namespace App\Filters;

class SupportChatsListFilter extends QueryFilter
{
  public function status($values)
  {
    if (!!$values) {
      if (is_array($values))
        $this->builder->whereIn('status', $values);
      else
        $this->builder->where('status', $values);
    }
  }

  public function search($value)
  {
    if ($value) {
      $this->builder->whereLike('users.name', "%$value%")
        ->orWhereLike('users.email', "%$value%")
        ->orWhereLike('users.phone_number', "%$value%")
        ->orWhereLike('users.telegram', "%$value%");
    }
  }
}