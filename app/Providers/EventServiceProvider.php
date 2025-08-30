<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\Product\ProductVariation;
use App\Observers\ProductObserver;
use App\Observers\ProductVariationObserver;
use App\Services\Image\ImageService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Orchid\Platform\Events\UploadFileEvent;

class EventServiceProvider extends ServiceProvider
{
  /**
   * The event to listener mappings for the application.
   *
   * @var array<class-string, array<int, class-string>>
   */
  protected $listen = [
    Registered::class => [
      SendEmailVerificationNotification::class,
    ],
  ];

  /**
   * Register any events for your application.
   */
  public function boot(): void
  {
    Product::observe(ProductObserver::class);
    ProductVariation::observe(ProductVariationObserver::class);

    Event::listen(function (UploadFileEvent $event) {
      $att = $event->attachment;
      ImageService::attachmentToWebp($att);
    });
  }

  /**
   * Determine if events and listeners should be automatically discovered.
   */
  public function shouldDiscoverEvents(): bool
  {
    return false;
  }
}
