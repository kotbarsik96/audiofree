<?php

namespace App\Orchid\Traits;

use App\Models\User;

trait OrchidScreenAuth
{
  public function currentUserCan(string|iterable $permissions): bool
  {
    return User::current()->hasAnyAccess($permissions);
  }
}
