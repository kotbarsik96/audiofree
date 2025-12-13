<?php

namespace App\Filters;

use App\Http\Requests\SupportChat\SupportChatGetListRequest;
use Illuminate\Database\Eloquent\Builder;

class SupportChatsListFilter extends QueryFilter
{
  public function prepareBuilder(Builder $builder)
  {
    $builder->join('users', 'users.id', '=', 'support_chats.user_id');
    parent::prepareBuilder($builder);
  }

  public function status($values)
  {
    if (!!$values) {
      if (is_array($values))
        $this->builder->whereIn('status', $values);
      else
        $this->builder->where('status', $values);
    }
  }

  public function user_name($value)
  {
    if ($value) {
      $this->builder->whereLike('users.name', "%$value%");
    }
  }

  public function user_email($value)
  {
    if ($value) {
      $this->builder->whereLike('users.email', "%$value%");
    }
  }

  public function user_phone_number($value)
  {
    if ($value) {
      $this->builder->whereLike('users.phone_number', "%$value%");
    }
  }
}