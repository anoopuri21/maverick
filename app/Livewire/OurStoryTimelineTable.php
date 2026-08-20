<?php

namespace App\Livewire;

use App\Filament\Resources\OurStoryTimelineResource;
use App\Models\OurStoryTimeline;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

/**
 * Embedded Timeline CRUD table for the Our Story page.
 * Reuses OurStoryTimelineResource::form() and ::table() so there is a single
 * source of truth for the field/column definitions.
 */
class OurStoryTimelineTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return OurStoryTimelineResource::table(
            $table
                ->query(OurStoryTimeline::query())
                ->headerActions([
                    \Filament\Tables\Actions\CreateAction::make()
                        ->form(fn (Form $form) => OurStoryTimelineResource::form($form)),
                ])
                ->actions([
                    \Filament\Tables\Actions\EditAction::make()
                        ->form(fn (Form $form) => OurStoryTimelineResource::form($form)),
                    \Filament\Tables\Actions\DeleteAction::make(),
                ]),
        );
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.embedded-crud-table');
    }
}
