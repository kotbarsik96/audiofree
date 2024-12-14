<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use Orchid\Attachment\Models\Attachment;
use SplFileInfo;

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
   * @return \SplFileInfo - данные об изображении в формате .webp
   */
  public function imageToWebp(string $imagePath): SplFileInfo
  {
    // новый путь будет сформирован, если текущее изображение не в .webp
    $newImagePath = $imagePath;
    $imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION);

    if ($imageExtension !== 'webp') {
      // сформировать новый путь на основе переданного
      $imageName = pathinfo($imagePath, PATHINFO_FILENAME);
      $newImage = $this->imageManager->read($imagePath);
      $newImagePath = dirname($imagePath) . '/' . $imageName . '.webp';

      // преобразовать в .webp, сохранить и удалить изображение старого формата
      $newImage->toWebp(75)->save($newImagePath);
      unlink($imagePath);
    }

    return new SplFileInfo($imagePath);
    ;
  }

  public static function getExtensionFromURL($url): string
  {
    return pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
  }
}
