<?php 
  use Orchid\Screen\Actions\Button;
?>

<fieldset class="mb-3">
  <div class="col p-0 px-3">
    <legend class="text-blank">
      {{ __('orchid.gallery') }}
    </legend>
  </div>
  <div class="bg-white rounded shadow-sm p-4 py-4 d-flex flex-column gap-3">
    <div class="form-group" data-elname="gallery" data-params='{ "hello": true, "world": false }'>
      <div class="border-dashed p-3 text-end cropper-actions">
        <div class="fields-cropper-container d-flex gap-3">
          @foreach ($variation->gallery()->get() as $image)
          <div class="fields-cropper-img" data-gallery-id="{{ $image->id }}">
            <img class="cropper-preview img-fluid img-full mb-2 border" src="{{ $image->url }}" alt="">
          </div>
          @endforeach
        </div>
        <span class="mt-1 float-start">
          {{ __('orchid.upload') }}
        </span>
        <div class="btn-group">
          <label class="btn btn-default m-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="me-2"
              viewBox="0 0 16 16" role="img" id="field-image-0771fc2863b1f8364905cedfe17d25e6599230a0"
              path="bs.cloud-arrow-up" componentname="orchid-icon">
              <path fill-rule="evenodd"
                d="M7.646 5.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708z">
              </path>
              <path
                d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383m.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z">
              </path>
            </svg>
            Browse
            <input type="file" accept="image/*" class="d-none" data-elchild="fileInput">
          </label>
        </div>
      </div>

      <div class="d-none" data-elchild="galleryHidden">
        @foreach ($variation->gallery()->get() as $image)
        <input type="hidden" value="{{ $image->id }}" name="gallery[]">
        @endforeach
      </div>
    </div>
    {!! Button::make(__('orchid.save'))
    ->method('saveGallery')->icon('pencil') !!}
  </div>
</fieldset>