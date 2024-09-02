<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
  /**
   * Bootstrap the application services.
   *
   * @param Dashboard $dashboard
   *
   * @return void
   */
  public function boot(Dashboard $dashboard): void
  {
    parent::boot($dashboard);

    // ...
  }

  /**
   * Register the application menu.
   *
   * @return Menu[]
   */
  public function menu(): array
  {
    return [
      Menu::make('Get Started')
        ->icon('bs.book')
        ->title('Navigation')
        ->route(config('platform.index')),

      Menu::make(__('general.users'))
        ->icon('bs.people')
        ->route('platform.systems.users')
        ->permission('platform.user.*')
        ->title(__('Access Controls')),

      Menu::make(__('general.roles'))
        ->icon('bs.shield')
        ->route('platform.systems.roles')
        ->permission('platform.role.*'),

      Menu::make(__('orchid.product.products'))
        ->icon('bs.shield')
        ->route('platform.products')
        ->divider()
        ->permission('platform.product.*'),
    ];
  }

  /**
   * Register permissions for the application.
   *
   * @return ItemPermission[]
   */
  public function permissions(): array
  {
    return [
      // ItemPermission::group(__('System'))
      //   ->addPermission('platform.systems.roles', __('general.roles'))
      //   ->addPermission('platform.systems.users', __('general.users'))
      //   ->addPermission('platform.systems.products', __('general.products')),

      ItemPermission::group(__('Users'))
        ->addPermission('platform.user.create', __('To create'))
        ->addPermission('platform.user.update', __('To update'))
        ->addPermission('platform.user.delete', __('To delete')),

      ItemPermission::group(__('Roles'))
        ->addPermission('platform.role.create', __('To create'))
        ->addPermission('platform.role.update', __('To update'))
        ->addPermission('platform.role.delete', __('To delete')),

      ItemPermission::group(__('Products'))
        ->addPermission('platform.product.create', __('To create'))
        ->addPermission('platform.product.update', __('To update'))
        ->addPermission('platform.product.delete', __('To delete')),
    ];
  }
}
