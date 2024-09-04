<?php

namespace App\Orchid\Screens;

use App\Http\Requests\Taxonomy\TaxonomyValueRequest;
use App\Models\Taxonomy\Taxonomy;
use App\Models\Taxonomy\TaxonomyValue;
use Orchid\Screen\Actions\Button;
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
   * Fetch data to be displayed on the screen.
   *
   * @return array
   */
  public function query(Taxonomy $taxonomy, TaxonomyValue $tValue): iterable
  {
    return [
      'tValue' => $tValue,
      'value' => $tValue->value,
      'slug' => $taxonomy->slug,
      'value_slug' => $tValue->value_slug,
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
    return $this->tValue->exists ? 'Taxonomy value create' : 'Taxonomy value edit';
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

    return redirect()->back();
  }

  public function delete()
  {
    $this->tValue->delete();

    Alert::info(__('orchid.success'));
  }
}
