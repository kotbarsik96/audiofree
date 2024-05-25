<?php

namespace App\Models;

use App\Models\Gallery\Gallery;
use App\Models\Product\ProductVariation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Builder;

class Image extends Model
{
  use HasFactory;

  protected $fillable = [
    'path',
    'uploaded_by_user'
  ];

  // $path must NOT start with "/"
  public static function upload(UploadedFile $image, $path = null)
  {
    $user = auth()->user();
    if (!$path) $path = $user->id;
    if (!preg_match('/\/$/', $path)) $path .= '/';

    $imageName = md5(time() . $image->getClientOriginalName() . rand()) . '.' . $image->getClientOriginalExtension();
    $imagePathRelative = 'images/' . $path;
    $imagePath = public_path($imagePathRelative);
    $image->move($imagePath, $imageName);
    $stored = Image::create([
      'path' => $imagePathRelative . $imageName,
      'uploaded_by_user' => $user->id
    ]);

    return $stored;
  }

  public static function deleteImage(self $image)
  {
    File::delete(public_path($image->path));
    $image->delete();
  }
}
