<?php

namespace App\Services\Image;

use App\Enums\ImageManager\PositionEnum;
use Exception;
use Hash;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;
use Orchid\Attachment\Models\Attachment;
use Storage;

class CollageService
{
  /**
   * @param array $imageUrls урлы изображений, которые поместить в коллаж
   * @param string $pathToSave путь, куда сохранять итоговый файл
   * @param string $nameToSave название итогового файла
   */
  public function __construct(
    public array $imageUrls,
    public string $pathToSave,
    public string $nameToSave,
    public ImageManager $manager,
    public ?string $attachmentGroup = null,
    public int $canvasWidth = 300,
    public int $canvasHeight = 300,
  ) {
  }

  protected function loadImage(string $path)
  {
    if (Storage::exists($path)) {
      return $this->manager->read($path);
    }

    if (filter_var($path, FILTER_VALIDATE_URL)) {
      return $this->manager->read(file_get_contents($path));
    }

    throw new Exception("Invalid path: . $path");
  }

  protected function getSavePath()
  {
    return $this->pathToSave.'/'.$this->nameToSave.'.webp';
  }

  protected function initCanvas(?string $collageName = null)
  {
    $canvas = null;

    if (!$collageName) {
      $canvas = $this->manager->create($this->canvasWidth, $this->canvasHeight);
    } else {
      $path = storage_path("images/collage/$collageName.webp");
      $canvas = $this->manager
        ->read(file_get_contents($path))
        ->scale($this->canvasWidth, $this->canvasHeight);
    }
    return $canvas;
  }

  public function createCollage()
  {
    $collage = $this->fillCanvas();

    $attachment = $this->saveCollage($collage);

    return $attachment;
  }

  public function fillCanvas()
  {
    $collage = null;

    switch (count($this->imageUrls)) {
      case 1:
        $collage = $this->placeSingleImage();
        break;
      case 2:
        $collage = $this->placeMultipleImages(
          $this->canvasWidth / 2.25,
          [PositionEnum::LEFT->value => [], PositionEnum::RIGHT->value => []]
        );
        break;
      case 3:
        $threeCanvasWidth = $this->canvasWidth / 3;
        $threeOffsets = [
          'offset_x' => $this->canvasWidth / 20,
          'offset_y' => $this->canvasHeight / 20,
        ];
        $collage = $this->placeMultipleImages(
          $threeCanvasWidth,
          [
            PositionEnum::TOP_LEFT->value => $threeOffsets,
            PositionEnum::TOP_RIGHT->value => $threeOffsets,
            PositionEnum::BOTTOM->value => [
              'offset_x' => 0
            ]
          ]
        );
        break;
      case 4:
        $fourOffsets = [
          'offset_x' => $this->canvasWidth / 25,
          'offset_y' => $this->canvasHeight / 25
        ];
        $collage = $this->placeMultipleImages(
          $this->canvasWidth / 3,
          [
            PositionEnum::TOP_LEFT->value => $fourOffsets,
            PositionEnum::TOP_RIGHT->value => $fourOffsets,
            PositionEnum::BOTTOM_LEFT->value => $fourOffsets,
            PositionEnum::BOTTOM_RIGHT->value => $fourOffsets
          ]
        );
        break;
    }

    return $collage;
  }

  public function saveCollage(ImageInterface $collage)
  {
    $collage = $collage->encodeByExtension('webp');
    $savePath = $this->getSavePath();
    Storage::put($savePath, (string) $collage);
    
    return Attachment::create([
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
  }

  protected function placeSingleImage()
  {
    $img = $this->loadImage($this->imageUrls[0]);
    $img->scale($this->canvasWidth);

    return $this->initCanvas()
      ->place($img, PositionEnum::CENTER->value);
  }

  /**
   * @param int $sizeOfImage размер одного изображения в коллаже
   * @param array $placings расположения. 
   * Например, [PositionEnum::LEFT->value, PositionEnum::RIGHT->value] - изображение слева, изображение справа. 
   * Также можно указать оффсеты: [PositionEnum::LEFT->value => ['offset_x' => 5, 'offset_y' => 10]]
   */
  protected function placeMultipleImages(int $sizeOfImage, array $placings)
  {
    $count = count($this->imageUrls);
    $canvas = $this->initCanvas("collage-$count");
    $placingKeys = array_keys($placings);
    $placingValues = array_values($placings);

    foreach ($this->imageUrls as $index => $url) {
      $img = $this->loadImage($url);
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