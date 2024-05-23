<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\Product\ProductRating;
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
    if (Role::isAdmin($user))
      return true;

    if (Role::isManager($user))
      return $product->created_by === $user->id;

    return false;
  }

  public function delete(User $user)
  {
    return Role::isAdmin($user);
  }

  public function setRating(User $user)
  {
    return Role::isUser($user);
  }

  public function removeRating(User $user, ProductRating $rating)
  {
    return $user->id === $rating->user_id;
  }
}
