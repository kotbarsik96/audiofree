<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\ImageService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Orchid\Attachment\Models\Attachment;

class AttachmentSeeder extends Seeder
{
  public function productImagesPath(): string
  {
    return config('constants.paths.images.products');
  }

  public function getStoragePath($path)
  {
    return 'seeders/' . $path;
  }

  public function productImagesRun()
  {
    // взять изображения из /storage/app/public/images/products
    $storagePath = storage_path($this->getStoragePath($this->productImagesPath()));
    $images = File::allFiles($storagePath);
    $imageService = new ImageService();

    // получить названия групп для изображения товаров и для галереи
    $groups = [
      config('constants.product.image_group'),
      config('constants.product.variation.gallery_group'),
    ];

    foreach ($images as $image) {
      // преобразовать изображение в .webp формат, если оно не .webp
      $image = $imageService->imageToWebp($image->getPathname());

      // получить расширение и имя файла
      $extension = pathinfo($image->getRealPath(), PATHINFO_EXTENSION);
      $filename = pathinfo($image->getRealPath(), PATHINFO_FILENAME);

      // связать аттачмент с базой данных
      Attachment::create(
        [
          'name' => $filename,
          'original_name' => $filename . '.' . $extension,
          'mime' => 'image/' . $extension,
          'extension' => $extension,
          'size' => $image->getSize(),
          'path' => $this->productImagesPath() . '/',
          'user_id' => User::all()->random()->first()->id,
          'sort' => 0,
          'hash' => Hash::make($image->getRealPath()),
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
