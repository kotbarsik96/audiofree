<?php

namespace App\Orchid\Layouts\Taxonomy;

use App\Models\Taxonomy\TaxonomyValue;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class TaxonomyValuesListTable extends Table
{
  /**
   * Data source.
   *
   * The name of the key to fetch it from the query.
   * The results of which will be elements of the table.
   *
   * @var string
   */
  protected $target = 'taxonomy_values';

  /**
   * Get the table cells to be displayed.
   *
   * @return TD[]
   */
  protected function columns(): iterable
  {
    return [
      TD::make(__('Name'))
        ->render(function (TaxonomyValue $tValue) {
          return Link::make($tValue->value)
            ->route('platform.taxonomy.value.edit', [$this->query->get('taxonomy')->id, $tValue->id]);
        }),

      TD::make(__('Actions'))
        ->render(function (TaxonomyValue $tValue) {
          return Button::make(__('Delete'))
            ->icon('trash')
            ->method('delete', ['taxonomyValue' => $tValue->id])
            ->confirm(__('Are you sure to delete taxonomy?' . ' (' . $tValue->value . ')'));
        })
    ];
  }
}
