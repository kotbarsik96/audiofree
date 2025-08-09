<?php

namespace App\Orchid\Screens\Seo;

use App\Models\Seo;
use App\Orchid\Layouts\Seo\SeoListTable;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;

class SeoListScreen extends Screen
{
    public function permission(): ?iterable
    {
        return [
            'platform.systems.seo'
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
            'seo' => Seo::all()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'SEO';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make(__('orchid.create'))
                ->icon('bs.plus')
                ->route('platform.seo.edit')
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
            SeoListTable::class
        ];
    }

    public function delete(Seo $seo)
    {
        $seo->delete();

        Alert(__('orchid.success'));
    }
}
