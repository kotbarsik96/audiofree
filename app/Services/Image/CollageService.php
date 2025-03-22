<?php

namespace App\Services\Image;

use Hash;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
use Orchid\Attachment\Models\Attachment;
use Storage;

class CollageService
{
  /** Ширина и высота холста */
  public $canvasSize = [300, 300];
  public ?ImageManager $manager = null;

  /**
   * @param array $imageUrls урлы изображений, которые поместить в коллаж
   * @param string $pathToSave путь, куда сохранять итоговый файл
   * @param string $nameToSave название итогового файла
   */
  public function __construct(
    public array $imageUrls,
    public string $pathToSave,
    public string $nameToSave,
    public string|null $attachmentGroup = null
  ) {
    $this->manager = new ImageManager(Driver::class);
  }

  protected function getSavePath()
  {
    return $this->pathToSave.'/'.$this->nameToSave.'.webp';
  }

  protected function createCanvas(string|null $collageName = null)
  {
    $canvas = null;
    $width = $this->canvasSize[0];
    $height = $this->canvasSize[1];

    if (!$collageName) {
      $canvas = $this->manager->create($width, $height);
    } else {
      $path = storage_path("images/collage/$collageName.webp");
      $canvas = $this->manager
        ->read(file_get_contents($path))
        ->scale($width, $height);
    }
    return $canvas;
  }

  public function createCollage()
  {
    $collage = null;
    $canvasWidth = $this->canvasSize[0];
    $canvasHeight = $this->canvasSize[1];

    switch (count($this->imageUrls)) {
      case 1:
        $collage = $this->placeSingle();
        break;
      case 2:
        $collage = $this->placeMany(
          $canvasWidth / 2.25,
          ['left' => [], 'right' => []]
        );
        break;
      case 3:
        $threeCanvasWidth = $canvasWidth / 3;
        $threeOffsets = [
          'offset_x' => $canvasWidth / 15,
          'offset_y' => $canvasHeight / 15,
        ];
        $collage = $this->placeMany(
          $threeCanvasWidth,
          [
            'top-left' => $threeOffsets,
            'top-right' => $threeOffsets,
            'bottom' => [
              'offset_x' => 0
            ]
          ]
        );
        break;
      case 4:
        $fourOffsets = [
          'offset_x' => $canvasWidth / 25,
          'offset_y' => $canvasHeight / 25
        ];
        $collage = $this->placeMany(
          $canvasWidth / 3,
          [
            'top-left' => $fourOffsets,
            'top-right' => $fourOffsets,
            'bottom-left' => $fourOffsets,
            'bottom-right' => $fourOffsets
          ]
        );
        break;
    }

    $collage = $collage->encodeByExtension('webp');
    $savePath = $this->getSavePath();
    Storage::put($savePath, (string) $collage);
    $attachment = Attachment::create([
      'name' => $this->nameToSave,
      'original_name' => "$this->nameToSave.webp",
      'mime' => 'image/webp',
      'extension' => 'webp',
      'size' => $collage->size(),
      'path' => "$this->pathToSave/",
      'user_id' => null,
      'hash' => Hash::make($this->nameToSave),
      'disk' => env('FILESYSTEM_DISK', 's3'),
      'group' => $this->attachmentGroup
    ]);

    return $attachment;
  }

  protected function placeSingle()
  {
    $imageSize = $this->canvasSize;
    $img = $this->manager->read(file_get_contents($this->imageUrls[0]));
    $img->scale($imageSize[0]);

    return $this->createCanvas()
      ->place($img, 'center');
  }

  /**
   * @param int $sizeOfImage размер одного изображения в коллаже
   * @param array $placings расположения. Например, ['left', 'right'] - изображение слева, изображение справа. Также можно указать оффсеты: ['left' => ['offset_x' => 5, 'offset_y' => 10]]
   */
  protected function placeMany(int $sizeOfImage, array $placings)
  {
    $count = count($this->imageUrls);
    $canvas = $this->createCanvas("collage-$count");
    $placingKeys = array_keys($placings);
    $placingValues = array_values($placings);

    foreach ($this->imageUrls as $index => $url) {
      $img = $this->manager->read(file_get_contents($url));
      $img->scale($sizeOfImage);

      $placingKey = $placingKeys[$index];
      $placingValue = $placingValues[$index];

      // без оффсета ('top_right')
      if (is_string($placingValue)) {
        $canvas->place($img, $placingValue);
      }
      // с оффсетом ('top_right' => [...])
      else {
        $offset_x = array_key_exists('offset_x', $placingValue)
          ? $placingValue['offset_x'] : 0;
        $offset_y = array_key_exists('offset_y', $placingValue)
          ? $placingValue['offset_y'] : 0;

        $canvas->place(
          $img,
          $placingKey,
          $offset_x,
          $offset_y
        );
      }
    }

    return $canvas;
  }
}