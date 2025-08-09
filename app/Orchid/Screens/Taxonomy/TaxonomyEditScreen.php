<?php

namespace App\Orchid\Screens\Taxonomy;

use App\Http\Requests\Taxonomy\TaxonomyRequest;
use App\Http\Requests\Taxonomy\TaxonomyValueRequest;
use App\Models\Taxonomy\Taxonomy;
use App\Models\Taxonomy\TaxonomyValue;
use App\Orchid\Layouts\Taxonomy\TaxonomyValuesListTable;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
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
  public function query(Taxonomy $taxonomy): iterable
  {
    // $taxonomy->load('values');

    return [
      'taxonomy' => $taxonomy,
      'taxonomy_values' => $taxonomy->values()->get()
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
        ->canSee($this->taxonomy->exists)
        ->confirm(__('orchid.taxonomy.areYouSureToDeleteValue')),
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
      ]),

      Layout::block([
        Layout::rows([
          Link::make(__('Create'))
            ->route('platform.taxonomy.value.edit', [$this->taxonomy->id ?? 0])
            ->icon('pencil')
        ])->canSee($this->taxonomy->exists),

        TaxonomyValuesListTable::class,
      ])
        ->vertical()
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

  public function updateValue(TaxonomyValueRequest $request, TaxonomyValue $tValue)
  {
    $tValue->update($request->validated());
  }

  public function delete(Taxonomy $taxonomy)
  {
    $taxonomy->delete();

    Alert::info(__('orchid.success'));

    return redirect()->route('platform.taxonomies');
  }

  public function deleteValue(TaxonomyValue $tValue)
  {
    $tValue->delete();

    Alert::info(__('orchid.success'));
  }
}
