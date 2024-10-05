<?php

namespace App\Http\Requests\Image;

use App\Models\Image;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class ImageDeleteRequest extends FormRequest
{
  public Image $image;

  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    $this->image = Image::where('id', request()->image_id)
      ->orWhere('path', request()->path)
      ->first();
    if (!$this->image) abort('abortions.imageNotFound', 404);

    return Gate::allows('delete-image', $this->image);
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      //
    ];
  }
}
