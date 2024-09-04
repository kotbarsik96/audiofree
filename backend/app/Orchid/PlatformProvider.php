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
        ->permission('platform.systems.users')
        ->title(__('Access Controls')),

      Menu::make(__('general.roles'))
        ->icon('bs.shield')
        ->route('platform.systems.roles')
        ->permission('platform.systems.roles'),

      Menu::make(__('orchid.taxonomy.taxonomies'))
        ->icon('bs.shield')
        ->route('platform.taxonomies')
        ->permission('platform.systems.products'),

      Menu::make(__('orchid.product.products'))
        ->icon('bs.shield')
        ->route('platform.products')
        ->divider()
        ->permission('platform.systems.products'),
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
      ItemPermission::group(__('System'))
        ->addPermission('platform.systems.roles', __('orchid.roles'))
        ->addPermission('platform.systems.users', __('orchid.users'))
        ->addPermission('platform.systems.products', __('orchid.product.products')),
    ];
  }
}
