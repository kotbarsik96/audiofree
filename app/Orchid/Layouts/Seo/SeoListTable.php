<?php

namespace App\Orchid\Layouts\Seo;

use App\Models\Seo;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class SeoListTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'seo';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make(__('orchid.seo.slug'))
                ->render(function (Seo $seo) {
                    return Link::make($seo->slug)
                        ->route('platform.seo.edit', ['seoPage' => $seo->id]);
                }),

            TD::make(__('orchid.seo.title'))
                ->render(function (Seo $seo) {
                    return Link::make($seo->title)
                        ->route('platform.seo.edit', ['seoPage' => $seo->id])
                        ->style('max-width: 500px; text-overflow: ellipsis; overflow: hidden; display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 1;');
                }),

            TD::make(__('Actions'))
                ->render(function (Seo $seo) {
                    return Group::make([
                        Button::make(__('Delete'))
                            ->confirm(__('orchid.seo.areYouSureToDelete'))
                            ->method('delete', ['seo' => $seo->id])
                    ]);
                })->cantHide(),
        ];
    }
}
