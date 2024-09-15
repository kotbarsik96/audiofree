<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\ImageService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Orchid\Attachment\Models\Attachment;
use SplFileInfo;

class AttachmentSeeder extends Seeder
{
  protected $storagePath = 'app/public';

  protected $productImagesPath = 'images/products';

  public function getStoragePath($path)
  {
    return $this->storagePath . '/' . $path;
  }

  public function productImagesRun()
  {
    $storagePath = storage_path($this->getStoragePath($this->productImagesPath));
    $images = File::allFiles($storagePath);
    $imageService = new ImageService();

    $groups = [
      config('constants.product.image_group'),
      config('constants.product.variation.gallery_group'),
    ];

    foreach ($images as $image) {
      $imagePath = $imageService->imageToWebp($image->getPathname());
      $image = new SplFileInfo($imagePath);
      
      $extension = pathinfo($imagePath, PATHINFO_EXTENSION);
      $filename = pathinfo($imagePath, PATHINFO_FILENAME);

      Attachment::create(
        [
          'name' => $filename,
          'original_name' => $filename . '.' . $extension,
          'mime' => 'image/' . $extension,
          'extension' => $extension,
          'size' => $image->getSize(),
          'path' => $this->productImagesPath . '/',
          'user_id' => User::all()->random()->first()->id,
          'sort' => 0,
          'hash' => Hash::make($imagePath),
          'disk' => 'public',
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
