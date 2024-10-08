<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use Orchid\Platform\Models\User;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Persona;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class UserListLayout extends Table
{
  /**
   * @var string
   */
  public $target = 'users';

  /**
   * @return TD[]
   */
  public function columns(): array
  {
    return [
      TD::make('name', __('Name'))
        ->sort()
        ->cantHide()
        ->filter(Input::make())
        ->render(fn(User $user) => new Persona($user->presenter())),

      TD::make('email', __('Email'))
        ->sort()
        ->cantHide()
        ->filter(Input::make())
        ->render(fn(User $user) => ModalToggle::make($user->email)
          ->modal('asyncEditUserModal')
          ->modalTitle($user->presenter()->title())
          ->method('saveUser')
          ->asyncParameters([
            'user' => $user->id,
          ])),

      TD::make('created_at', __('Created'))
        ->usingComponent(DateTimeSplit::class)
        ->align(TD::ALIGN_RIGHT)
        ->defaultHidden()
        ->sort(),

      TD::make('updated_at', __('Last edit'))
        ->usingComponent(DateTimeSplit::class)
        ->align(TD::ALIGN_RIGHT)
        ->sort(),
    ];
  }
}
