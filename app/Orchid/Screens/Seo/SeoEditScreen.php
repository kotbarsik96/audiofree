<?php

namespace App\Orchid\Screens\Seo;

use App\Http\Requests\SeoRequest;
use App\Models\Seo;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class SeoEditScreen extends Screen
{
    /**
     * @var Seo
     */
    public $seoPage;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Seo $seoPage): iterable
    {
        return [
            'seoPage' => $seoPage
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->seoPage
            ? __('orchid.seo.edit', ['slug' => $this->seoPage->slug])
            : __('orchid.seo.create');
    }

    public function getAttr(string $name)
    {
        return $this->seoPage->exists ? $this->seoPage->$name : null;
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
                ->method('delete', ['seo' => $this->seoPage])
                ->icon('trash')
                ->canSee($this->seoPage->exists)
                ->confirm(__('orchid.seo.areYouSureToDelete')),
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
                Input::make('slug')
                    ->set('value', $this->getAttr('slug'))
                    ->title(__('orchid.seo.slug')),

                Input::make('title')
                    ->set('value', $this->getAttr('title'))
                    ->title(__('orchid.seo.title')),

                TextArea::make('description')
                    ->set('value', $this->getAttr('description'))
                    ->title(__('orchid.seo.description'))
                    ->rows(4)
            ]),
        ];
    }

    public function save(SeoRequest $request)
    {
        $validated = $request->validated();

        $this->seoPage->fill($validated);
        $this->seoPage->save();

        Alert::info(__('orchid.success'));

        return redirect()->route('platform.seo');
    }

    public function delete(Seo $seo)
    {
        $seo->delete();

        Alert::info(__('orchid.success'));

        return redirect()->route('platform.seo');
    }
}
