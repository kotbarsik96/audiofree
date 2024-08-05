<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class OrderPolicy
{
  public function cancelThirdPersonOrder(User $user)
  {
    return Role::isAdmin($user);
  }
}
