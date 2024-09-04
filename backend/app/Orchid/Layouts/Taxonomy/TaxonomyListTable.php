<?php

namespace App\Orchid\Layouts\Taxonomy;

use App\Models\Taxonomy\TaxonomyType;
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
  protected $target = 'taxonomies_types';

  /**
   * Get the table cells to be displayed.
   *
   * @return TD[]
   */
  protected function columns(): iterable
  {
    return [
      TD::make(__('Type'))
        ->render(function (TaxonomyType $ttype) {
          return Link::make(__('orchid.taxonomy.' . $ttype->type))
            ->route('platform.taxonomy.edit', ['taxonomy' => $ttype->id]);
        }),
      TD::make(__('Group'))
        ->render(function (TaxonomyType $ttype) {
          return $ttype->group;
        }),
      TD::make(__('Actions'))
        ->render(function (TaxonomyType $ttype) {
          return Group::make([
            Button::make(__('Delete'))
              ->confirm(__('Are you sure to delete taxonomy?'))
              ->method('delete', ['taxonomyType' => $ttype])
          ]);
        })->cantHide(),
    ];
  }
}
