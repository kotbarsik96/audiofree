<?php

namespace App\Orchid\Screens\Taxonomy;

use App\Http\Requests\Taxonomy\TaxonomyValueRequest;
use App\Models\Taxonomy\Taxonomy;
use App\Models\Taxonomy\TaxonomyValue;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class TaxonomyValueEditScreen extends Screen
{
  /**
   * @var TaxonomyValue
   */
  public $tValue;

  /**
   * @var Taxonomy
   */
  public $taxonomy;

  public function permission(): ?iterable
  {
    return [
      'platform.systems.products'
    ];
  }

  /**
   * Fetch data to be displayed on the screen.
   *
   * @return array
   */
  public function query(Taxonomy $taxonomy, TaxonomyValue $tValue): iterable
  {
    return [
      'tValue' => $tValue,
      'taxonomy' => $taxonomy,
      'value' => $tValue->value,
      'slug' => $taxonomy->slug,
      'value_slug' => $tValue->value_slug,
      'image_id' => $tValue->image_id,
      'id' => $tValue->id,
    ];
  }

  /**
   * The name of the screen displayed in the header.
   *
   * @return string|null
   */
  public function name(): ?string
  {
    return $this->tValue->exists
      ? __('Taxonomy value edit').': '.$this->taxonomy->name
      : 'Taxonomy value create';
  }

  /**
   * The screen's action buttons.
   *
   * @return \Orchid\Screen\Action[]
   */
  public function commandBar(): iterable
  {
    return [
      Button::make(__('Save'))
        ->method('save')
        ->icon('pencil'),

      Button::make(__('Delete'))
        ->method('delete')
        ->icon('trash')
        ->confirm(__('orchid.taxonomy.areYouSureToDeleteValue'))
        ->canSee($this->tValue->exists),
    ];
  }

  /**
   * The screen's layout elements.
   *
   * @return \Orchid\Screen\Layout[]|string[]
   */
  public function layout(): iterable
  {
    return [
      Layout::rows([
        Input::make('value')
          ->title(__('orchid.taxonomy.value')),

        Input::make('value_slug')
          ->title(__('orchid.taxonomy.valueSlug')),

        Cropper::make('image_id')
          ->title(__('orchid.taxonomy.image'))
          ->path(config('constants.paths.images.taxonomies').'/'.$this->taxonomy->slug)
          ->groups(config('constants.taxonomy_values.image_group'))
          ->width(500)
          ->height(500)
          ->value($this->taxonomy->image_id)
          ->targetId(),

        Input::make('slug')
          ->type('hidden'),

        Input::make('id')
          ->type('hidden')
      ])
    ];
  }

  public function save(TaxonomyValueRequest $request)
  {
    $this->tValue->fill($request->validated());
    $this->tValue->save();

    Alert::info(__('orchid.success'));

    return redirect()->route('platform.taxonomy.edit', [$this->taxonomy->id]);
  }

  public function delete()
  {
    $this->tValue->delete();

    Alert::info(__('orchid.success'));

    return redirect()->route('platform.taxonomy.edit', [$this->taxonomy->id]);
  }
}
