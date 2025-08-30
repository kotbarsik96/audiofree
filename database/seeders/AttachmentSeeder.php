<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\Image\ImageService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

use Orchid\Attachment\Models\Attachment;

class AttachmentSeeder extends Seeder
{
  public function productImagesPath(string|null $to = null): string
  {
    return config('constants.paths.images.products').$to ?? '';
  }

  public function getStoragePath(string $path)
  {
    return "seeders/$path";
  }

  public function productImagesRun()
  {
    // взять изображения из /storage
    $storagePath = storage_path($this->getStoragePath($this->productImagesPath()));
    $images = File::allFiles($storagePath);

    // получить названия групп для изображения товаров и для галереи
    $groups = [
      config('constants.product.image_group'),
      config('constants.product.variation.gallery_group'),
    ];

    foreach ($images as $image) {
      // преобразовать изображение в .webp формат, если оно не .webp
      $imageModified = ImageService::imageToWebp($image);

      // сохранить в хранилище изображений
      $imageModified->saveToStorage($this->productImagesPath());

      // связать аттачмент с базой данных
      $this->createAttachment(
        $imageModified,
        null,
        fake()->randomElement($groups)
      );
    }
  }

  public function taxonomiesImagesRun()
  {
    // для каждого taxonomy-slug'а есть свой каталог в /storage/seeders/audiofree/images. Необходимо указать каталоги с существующими изображениями тут
    $slugs = [
      'brand'
    ];

    foreach ($slugs as $slug) {
      $path = env('APP_NAME_SLUG').'/images/taxonomies/'.$slug;

      $imagesPath = storage_path(
        $this->getStoragePath($path)
      );
      $images = File::allFiles($imagesPath);

      foreach ($images as $image) {
        // преобразовать изображение в .webp формат, если оно не .webp
        $imageModified = ImageService::imageToWebp($image);

        // сохранить в хранилище изображений
        $imageModified->saveToStorage($path);

        // связать аттачмент с базой данных
        $this->createAttachment(
          $imageModified,
          "taxonomy_seeder_$slug",
          config('constants.taxonomy_values.image_group')
        );
      }
    }
  }

  public function createAttachment(
    ImageService $imageModified,
    string|null $description = null,
    string|null $group = null
  ) {
    $extension = $imageModified->getExtension();
    $filename = $imageModified->getFilename();

    Attachment::create(
      [
        'name' => $filename,
        'original_name' => "$filename.$extension",
        'mime' => "image/$extension",
        'description' => $description,
        'extension' => $extension,
        'size' => $imageModified->imageInfo->getSize(),
        'path' => $imageModified->getLastSavedPath().'/',
        'user_id' => User::all()->random()->first()->id,
        'sort' => 0,
        'hash' => $imageModified->makeImagePathHash(),
        'disk' => 's3',
        'group' => $group
      ]
    );
  }

  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $this->productImagesRun();
    $this->taxonomiesImagesRun();
  }
}
