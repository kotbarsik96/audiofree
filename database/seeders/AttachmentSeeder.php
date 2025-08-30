<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\Image\ImageService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Orchid\Attachment\Models\Attachment;
use SplFileInfo;
use Storage;

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
    // взять изображения из /storage/app/public/images/products
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
      $image = $imageModified->image;
      $imageInfo = $imageModified->imageInfo;

      // получить расширение и имя файла
      $extension = $imageModified->getExtension();
      $filename = $imageModified->getFilename();

      // сохранить в хранилище изображений
      $path = $this->productImagesPath("/$filename.$extension");
      Storage::put($path, (string) $image);

      // связать аттачмент с базой данных
      Attachment::create(
        [
          'name' => $filename,
          'original_name' => "$filename.$extension",
          'mime' => "image/$extension",
          'extension' => $extension,
          'size' => $imageInfo->getSize(),
          'path' => $this->productImagesPath().'/',
          'user_id' => User::all()->random()->first()->id,
          'sort' => 0,
          'hash' => Hash::make($imageInfo->getRealPath()),
          'disk' => 's3',
          'group' => fake()->randomElement($groups)
        ]
      );
    }
  }

  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $this->productImagesRun();
  }
}
