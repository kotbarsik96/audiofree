<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;

class ImageService
{
  protected ImageManager $imageManager;

  public function __construct()
  {
    $this->imageManager = new ImageManager(Driver::class);
  }

  /** 
   * Заменяет аттачмент с расширением .png, .jpg, .jpeg на .webp
   * Обновляет базу attachments
   * @param $attImage = attachment image
   */
  public function attachmentToWebp($attImage)
  {
    if ($attImage->extension !== 'webp') {
      $path = Storage::path($attImage->disk . '/' . $attImage->path . $attImage->name);
      $oldImagePath = $path  . '.' . $attImage->extension;

      $image = $this->imageManager->read($oldImagePath);
      $image->toWebp(75)->save($path . '.webp');

      $attImage->update([
        'extension' => 'webp',
        'mime' => 'image/webp'
      ]);
      
      unlink($oldImagePath);
    }
  }

  public function imageToWebp($imagePath)
  {
    $newImagePath = $imagePath;
    $imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION);

    if($imageExtension !== 'webp') {
      $imageName = pathinfo($imagePath, PATHINFO_FILENAME);
      $newImage = $this->imageManager->read($imagePath);
      $newImagePath = dirname($imagePath) . '/' . $imageName . '.webp';
      $newImage->toWebp(75)->save($newImagePath);
      unlink($imagePath);
    }

    return $newImagePath;
  }
}
