<?php

namespace App\Orchid\Screens\Taxonomy;

use App\Http\Requests\Taxonomy\TaxonomyRequest;
use App\Models\Taxonomy\Taxonomy;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class TaxonomyEditScreen extends Screen
{
  /**
   * @var Taxonomy
   */
  public $taxonomy;

  public function getAttr($attrName)
  {
    return $this->taxonomy->exists ? $this->taxonomy->$attrName : null;
  }

  /**
   * Fetch data to be displayed on the screen.
   *
   * @return array
   */
  public function query(Taxonomy $taxonomy): iterable
  {
    return [
      'taxonomy' => $taxonomy,
    ];
  }

  /**
   * The name of the screen displayed in the header.
   *
   * @return string|null
   */
  public function name(): ?string
  {
    return $this->taxonomy->exists ? 'Edit taxonomy' : 'Create taxonomy';
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
        ->canSee($this->taxonomy->exists),
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
        Input::make('name')
          ->title(__('Name'))
          ->set('value', $this->getAttr('name')),

        Input::make('slug')
          ->title(__('Slug'))
          ->set('value', $this->getAttr('slug')),

        Input::make('group')
          ->title(__('Group'))
          ->set('value', $this->getAttr('group')),

        Input::make('id')
          ->type('hidden')
          ->set('value', $this->getAttr('id')),
      ])
    ];
  }

  public function save(TaxonomyRequest $request)
  {
    $validated = $request->validated();

    $this->taxonomy->fill($validated);
    $this->taxonomy->save();

    Alert::info(__('orchid.success'));

    return redirect()->route('platform.taxonomies');
  }

  public function delete(Taxonomy $taxonomy)
  {
    $taxonomy->delete();

    Alert::info(__('orchid.success'));
  }
}
