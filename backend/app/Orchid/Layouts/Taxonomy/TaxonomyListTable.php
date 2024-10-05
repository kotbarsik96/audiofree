<?php

namespace App\Orchid\Layouts\Taxonomy;

use App\Models\Taxonomy\Taxonomy;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class TaxonomyListTable extends Table
{
  /**
   * Data source.
   *
   * The name of the key to fetch it from the query.
   * The results of which will be elements of the table.
   *
   * @var string
   */
  protected $target = 'taxonomies';

  /**
   * Get the table cells to be displayed.
   *
   * @return TD[]
   */
  protected function columns(): iterable
  {
    return [
      TD::make(__('Type'))
        ->render(function (Taxonomy $taxonomy) {
          return Link::make($taxonomy->name)
            ->route('platform.taxonomy.edit', ['taxonomy' => $taxonomy->id]);
        }),
      TD::make(__('Group'))
        ->render(function (Taxonomy $taxonomy) {
          return $taxonomy->group ?? '—';
        }),
      TD::make(__('Actions'))
        ->render(function (Taxonomy $taxonomy) {
          return Group::make([
            Button::make(__('Delete'))
              ->confirm(__('Are you sure to delete taxonomy?' . '(' . $taxonomy->name . ')'))
              ->method('delete', ['taxonomy' => $taxonomy->id])
          ]);
        })->cantHide(),
    ];
  }
}
