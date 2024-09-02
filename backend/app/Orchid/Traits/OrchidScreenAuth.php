<?php

namespace App\Orchid\Traits;

use App\Models\User;

trait OrchidScreenAuth
{
  public function currentUserCan(string|iterable $permissions): bool
  {
    return User::current()->hasAnyAccess($permissions);
  }

  public function canCreate(string $permissionKey)
  {
    return $this->currentUserCan('platform.' . $permissionKey . '.create');
  }

  /**
   * @param $permissionKey === $queryKey, если оставить null
   */
  public function canUpdate(string $queryKey, string|null $permissionKey = null)
  {
    if (!$permissionKey) $permissionKey = $queryKey;

    return $this->$queryKey->exists
      && $this->currentUserCan('platform.' . $permissionKey . '.update');
  }

  /**
   * @param $permissionKey === $queryKey, если оставить null
   */
  public function canSave(string $queryKey, string|null $permissionKey = null): bool
  {
    if (!$permissionKey) $permissionKey = $queryKey;

    if ($this->$queryKey->exists) {
      return $this->canUpdate($queryKey, $permissionKey);
    } else {
      return $this->canCreate($permissionKey);
    }
  }

  /**
   * @param $permissionKey === $queryKey, если оставить null
   */
  public function canDelete(string $queryKey, string|null $permissionKey = null): bool
  {
    if (!$permissionKey) $permissionKey = $queryKey;

    return $this->$queryKey->exists
      && $this->currentUserCan('platform.' . $permissionKey . '.delete');
  }
}
