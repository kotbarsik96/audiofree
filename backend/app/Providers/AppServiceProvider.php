<?php

namespace App\Providers;

use App\Policies\ImagePolicy;
use App\Policies\ProductPolicy;
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
    // product
    Gate::define('create-product', [ProductPolicy::class, 'create']);
    Gate::define('update-product', [ProductPolicy::class, 'update']);
    Gate::define('delete-product', [ProductPolicy::class, 'delete']);
    Gate::define('set-rating', [ProductPolicy::class, 'setRating']);
    Gate::define('remove-rating', [ProductPolicy::class, 'removeRating']);
    
    // image
    Gate::define('upload-image', [ImagePolicy::class, 'upload']);
    Gate::define('delete-image', [ImagePolicy::class, 'delete']);
  }
}
