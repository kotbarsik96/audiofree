<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use App\Models\User;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class UserRoleLayout extends Rows
{
  public function getRolesQuery()
  {
    $authUserId = auth()->user()->id;
    $isDeveloper = User::find($authUserId)->inRole('developer');

    if ($isDeveloper) {
      return Role::where('id', '!=', '0');
    } else {
      return Role::whereNotIn('slug', ['developer', 'administrator']);
    }
  }

  /**
   * The screen's layout elements.
   *
   * @return Field[]
   */
  public function fields(): array
  {
    return [
      Select::make('user.roles.')
        ->fromQuery($this->getRolesQuery(), 'name')
        ->multiple()
        ->title(__('Name role'))
        ->help('Specify which groups this account should belong to'),
    ];
  }
}
