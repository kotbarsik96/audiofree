<?php

namespace App\Orchid\Screens\Taxonomy;

use App\Http\Requests\Taxonomy\TaxonomyTypeRequest;
use App\Models\Taxonomy\TaxonomyType;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class TaxonomyEditScreen extends Screen
{
  /**
   * @var TaxonomyType
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
  public function query(TaxonomyType $ttype): iterable
  {
    dd(request()->path());
    return [
      'taxonomy' => $ttype,
    ];
  }

  /**
   * The name of the screen displayed in the header.
   *
   * @return string|null
   */
  public function name(): ?string
  {
    return 'Edit taxonomy';
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
        Input::make('type')
          ->title(__('Type'))
          ->set('value', $this->getAttr('type')),
        Input::make('group')
          ->title(__('Group'))
          ->set('value', $this->getAttr('group')),

        Input::make('id')
          ->type('hidden')
          ->set('value', $this->getAttr('id')),
      ])
    ];
  }

  public function save(TaxonomyTypeRequest $request) {}

  public function delete(TaxonomyType $ttype) {}
}
