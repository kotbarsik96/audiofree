<?php

namespace App\Orchid\Layouts\Products\Variations;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Rows;

class VariationGalleryLayout extends Rows
{
  /**
   * Used to create the title of a group of form elements.
   *
   * @var string|null
   */
  protected $title;

  /**
   * Get the fields elements to be displayed.
   *
   * @return Field[]
   */
  protected function fields(): iterable
  {
    $maxGalleryImages = config('constants.product.variation.max_gallery_images');

    return [
      Upload::make('gallery')
        ->title(__('orchid.gallery'))
        ->maxFiles($maxGalleryImages)
        ->acceptedFiles('image/*'),

      Button::make(__('orchid.save'))
        ->method('saveGallery')
    ];
  }
}
