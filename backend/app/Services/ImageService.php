<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Orchid\Attachment\Models\Attachment;
use SplFileInfo;

class ImageService
{
  public ImageManager $imageManager;

  public function __construct()
  {
    $this->imageManager = new ImageManager(Driver::class);
  }

  /** 
   * Заменяет аттачмент с расширением .png, .jpg, .jpeg на .webp
   * Обновляет базу attachments
   * @param $attImage = attachment image
   */
  public function attachmentToWebp(Attachment $attachment)
  {
    $isWebp = self::getExtensionFromURL($attachment->url()) === 'webp';
    if (!$isWebp) {
      // разбить url к изображению, чтобы ввыделить путь и название файла
      $parsedUrl = parse_url($attachment->url());
      $pathData = pathinfo($parsedUrl['path']);
      $newPath = $pathData['dirname'] . '/' . $pathData['filename'] . '.webp';

      // преобразовать изображение в webp
      $image = file_get_contents($attachment->url());
      $newImg = (string) $this->imageManager->read($image)->toWebp();

      // удалить изображение в старом формате и сохранить в .webp
      Storage::delete($attachment->physicalPath());
      Storage::put($newPath, $newImg);

      // обновить запись в базе данных
      $attachment->update([
        'mime' => 'image/webp',
        'extension' => 'webp'
      ]);
    }
  }

  /**
   * 
   * @param mixed $imagePath - путь к изображению, строка
   * @return ImageService
   */
  public function imageToWebp(SplFileInfo $image): ImageServiceModified
  {
    $imagePath = $image->getPathname();
    $imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION);

    // сформировать новый путь на основе переданного
    $imageName = pathinfo($imagePath, PATHINFO_FILENAME);
    $newImagePath = dirname($imagePath) . "/$imageName.webp";
    $newImage = $this->imageManager->read($newImagePath);

    if ($imageExtension !== 'webp') {
      // преобразовать в .webp, удалить изображение старого формата и сохранить новое
      unlink($imagePath);
      $newImage = $newImage->toWebp(75);
      $newImage->save($newImagePath);
    } else {
      $newImage = $newImage->encode();
    }

    return new ImageServiceModified($newImage, $newImagePath);
  }

  public static function getExtensionFromURL($url): string
  {
    return pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
  }
}

class ImageServiceModified
{
  public EncodedImageInterface $image;

  public SplFileInfo $imageInfo;

  public function __construct(EncodedImageInterface $image, string $imagePath)
  {
    $this->image = $image;
    $this->imageInfo = new SplFileInfo($imagePath);
  }
}