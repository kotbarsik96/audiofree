<?php

namespace App\Services\Image;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Orchid\Attachment\Models\Attachment;
use SplFileInfo;

class ImageService
{
  public SplFileInfo $imageInfo;

  public static function getImageManager()
  {
    return new ImageManager(Driver::class);
  }

  /**
   * @return ImageService
   */
  public static function imageToWebp(SplFileInfo $image): static
  {
    $imagePath = $image->getPathname();
    $imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION);

    // сформировать новый путь на основе переданного
    $imageName = pathinfo($imagePath, PATHINFO_FILENAME);
    $newImagePath = dirname($imagePath)."/$imageName.webp";
    $newImage = static::getImageManager()->read($newImagePath);

    if ($imageExtension !== 'webp') {
      // преобразовать в .webp, удалить изображение старого формата и сохранить новое
      unlink($imagePath);
      $newImage = $newImage->toWebp(75);
      $newImage->save($newImagePath);
    } else {
      $newImage = $newImage->encode();
    }

    return new static($newImage, $newImagePath);
  }

  /** 
   * Заменяет аттачмент с расширением .png, .jpg, .jpeg на .webp
   * Обновляет базу attachments
   * @param $attImage = attachment image
   */
  public static function attachmentToWebp(Attachment $attachment)
  {
    $isWebp = static::getExtensionFromURL($attachment->url()) === 'webp';
    if (!$isWebp) {
      // разбить url к изображению, чтобы выделить путь и название файла
      $parsedUrl = parse_url($attachment->url());
      $pathData = pathinfo($parsedUrl['path']);
      $newPath = $pathData['dirname'].'/'.$pathData['filename'].'.webp';

      // преобразовать изображение в webp
      $image = file_get_contents($attachment->url());
      $newImg = (string) static::getImageManager()->read($image)->toWebp();

      // удалить изображение в старом формате и сохранить в .webp
      Storage::delete($attachment->physicalPath());
      Storage::put($newPath, $newImg);

      // обновить запись в базе данных
      $attachment->update([
        'mime' => 'image/webp',
        'extension' => 'webp'
      ]);

      return $attachment;
    }
  }

  public static function getExtensionFromURL($url): string
  {
    return pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
  }

  public function __construct(
    public EncodedImageInterface $image,
    string $imagePath
  ) {
    $this->imageInfo = new SplFileInfo($imagePath);
  }

  public function getExtension()
  {
    return pathinfo($this->imageInfo->getRealPath(), PATHINFO_EXTENSION);
  }

  public function getFilename()
  {
    return pathinfo($this->imageInfo->getRealPath(), PATHINFO_FILENAME);
  }

  public function resizeDown(int|null $width = null, int|null $height = null)
  {
    $this->image = static::getImageManager()
      ->read((string) $this->image)
      ->resizeDown($width, $height)
      ->encode();

    return $this;
  }
}
