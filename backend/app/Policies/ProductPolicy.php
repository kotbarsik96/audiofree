<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\Role;
use App\Models\User;

class ProductPolicy
{
  public function create(?User $user)
  {
    return Role::isManager($user) || Role::isAdmin($user);
  }

  public function update(User $user, Product $product = null)
  {
    if (Role::isManager($user)) {
      return $product->created_by === $user->id;
    }

    return Role::isAdmin($user);
  }

  public function delete(User $user)
  {
    return Role::isAdmin($user);
  }
}
