<?php

namespace App\Providers;

use App\Policies\ImagePolicy;
use App\Policies\OrderPolicy;
use App\Policies\ProductPolicy;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    App::setLocale(request()->header('Locale') ?? config('app.fallback_locale'));

    // product
    Gate::define('create-product', [ProductPolicy::class, 'create']);
    Gate::define('update-product', [ProductPolicy::class, 'update']);
    Gate::define('delete-product', [ProductPolicy::class, 'delete']);
    Gate::define('set-rating', [ProductPolicy::class, 'setRating']);
    Gate::define('remove-rating', [ProductPolicy::class, 'removeRating']);

    // image
    Gate::define('upload-image', [ImagePolicy::class, 'upload']);
    Gate::define('delete-image', [ImagePolicy::class, 'delete']);

    // order
    Gate::define('cancel-third-person-order', [OrderPolicy::class, 'cancelThirdPersonOrder']);
  }
}
