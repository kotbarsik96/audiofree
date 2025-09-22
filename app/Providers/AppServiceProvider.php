<?php

namespace App\Providers;

use App\Models\SupportChat;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
  }
}
