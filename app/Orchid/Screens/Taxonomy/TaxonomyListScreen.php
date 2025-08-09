<?php

namespace App\Orchid\Screens\Taxonomy;

use App\Http\Requests\Taxonomy\TaxonomyRequest;
use App\Models\Taxonomy\Taxonomy;
use App\Orchid\Layouts\Taxonomy\TaxonomyListTable;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class TaxonomyListScreen extends Screen
{
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
  public function query(): iterable
  {
    return [
      'taxonomies' => Taxonomy::all()
    ];
  }

  /**
   * The name of the screen displayed in the header.
   *
   * @return string|null
   */
  public function name(): ?string
  {
    return 'Taxonomies list';
  }

  /**
   * The screen's action buttons.
   *
   * @return \Orchid\Screen\Action[]
   */
  public function commandBar(): iterable
  {
    return [
      Link::make(__('Create'))
        ->icon('bs.plus')
        ->route('platform.taxonomy.edit')
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
      TaxonomyListTable::class,
    ];
  }

  public function delete(Taxonomy $taxonomy)
  {
    $taxonomy->delete();

    Alert(__('orchid.success'));
  }
}
