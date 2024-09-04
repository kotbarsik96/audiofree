<?php

namespace App\Orchid\Screens\Taxonomy;

use App\Http\Requests\Taxonomy\TaxonomyRequest;
use App\Models\Taxonomy\Taxonomy;
use App\Orchid\Layouts\Taxonomy\TaxonomyListTable;
use Orchid\Screen\Screen;

class TaxonomyListScreen extends Screen
{
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
    return [];
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
}
