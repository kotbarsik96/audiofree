<?php

namespace App\Policies;

use App\Models\Image;
use App\Models\Role;
use App\Models\User;

class ImagePolicy
{
  public function upload(User $user)
  {
    return $user ? true : false;
  }

  public function delete(User $user, Image $image)
  {
    $uploader = User::find($image->uploaded_by_user);

    // if admin - can remove managers' and users' images or their own image, or images without uploader
    if (Role::isAdmin($user)) {
      if ($uploader && Role::isAdmin($uploader) && $uploader->id !== $user->id)
        return false;

      return true;
    }
    // otherwise can remove only their own image
    elseif ($uploader && $user->id === $uploader->id)
      return true;

    return false;
  }
}
