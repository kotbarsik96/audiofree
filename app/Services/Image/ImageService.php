<?php

namespace App\Services\Image;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Orchid\Attachment\Models\Attachment;
use SplFileInfo;
use Illuminate\Support\Facades\Hash;

class ImageService
{
  public SplFileInfo $imageInfo;

  /**
   * Обновляется при вызове saveToStorage. Путь к последнему сохранённому изображению без слеша в конце
   */
  protected string|null $lastSavedPath;

  /**
   * Обновляется при вызове saveToStorage. Название последнего сохранённого изображения (без расширения)
   */
  protected string|null $lastSavedName;

  /**
   * Обновляется при вызове saveToStorage. Расширение последнего сохранённого изображения
   */
  protected string|null $lastSavedExtension;

  public static function getImageManager()
  {
    return new ImageManager(Driver::class);
  }

  /**
   * @return ImageService
   */
  public static function imageToWebp(SplFileInfo $image): static
  {
    $imageFilePath = $image->getPathname();
    $imageExtension = pathinfo($imageFilePath, PATHINFO_EXTENSION);

    // сформировать новый путь на основе переданного
    $imageName = pathinfo($imageFilePath, PATHINFO_FILENAME);
    $webpImagePath = dirname($imageFilePath)."/$imageName.webp";
    $processedImage = static::getImageManager()->read($imageFilePath);

    if ($imageExtension !== 'webp') {
      // преобразовать в .webp, удалить изображение старого формата и сохранить новое
      $encoded = $processedImage->toWebp(75);
      $encoded->save($webpImagePath);
      if (is_file($webpImagePath)) {
        unlink($imageFilePath);
      }
      $processedImage = $encoded;
    } else {
      $processedImage = $processedImage->encode();
    }

    return new static($processedImage, $webpImagePath);
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

  /**
   * @param string $path путь без указания названия
   * @param string|null $name название без расширения. Если не указан, сформирует автоматически
   * @return static
   */
  public function saveToStorage(string $path, string|null $name = null)
  {
    /** Без слеша в конце */
    $filename = $this->getFilename();
    $extension = $this->getExtension();

    $_path = str_ends_with($path, '/') ? substr($path, 0, strlen($path) - 1) : $path;
    $_name = !!$name ? "$name.$extension" : "$filename.$extension";

    $pathWithName = "$_path/$_name";

    Storage::put($pathWithName, (string) $this->image);

    $this->lastSavedPath = $_path;
    $this->lastSavedName = $name ? $name : $filename;
    $this->lastSavedExtension = $extension;

    return $this;
  }

  public function getLastSavedPath()
  {
    return $this->lastSavedPath;
  }

  public function getLastSavedName()
  {
    return $this->lastSavedName;
  }

  public function getLastSavedExtension()
  {
    return $this->lastSavedExtension;
  }

  public function makeImagePathHash()
  {
    return Hash::make($this->imageInfo->getRealPath());
  }
}
