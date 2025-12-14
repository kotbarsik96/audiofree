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

  public function search($value)
  {
    if ($value) {
      $this->builder->whereLike('users.name', "%$value%")
        ->orWhereLike('users.email', "%$value%")
        ->orWhereLike('users.phone_number', "%$value%");
    }
  }
}