<?php

namespace App\Livewire;

use App\Filament\Resources\OurStoryGalleryImageResource;
use App\Livewire\Concerns\MutatesEmbeddedMediaPicker;
use App\Models\OurStoryGalleryImage;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

/**
 * Embedded Gallery CRUD table for the Our Story page.
 * Reuses OurStoryGalleryImageResource::form() and ::table().
 */
class OurStoryGalleryImageTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    use MutatesEmbeddedMediaPicker;

    public function table(Table $table): Table
    {
        return OurStoryGalleryImageResource::table(
            $table
                ->query(OurStoryGalleryImage::query())
                ->headerActions([
                    \Filament\Tables\Actions\CreateAction::make()
                        ->form(fn (Form $form) => OurStoryGalleryImageResource::form($form))
                        ->mutateFormDataUsing(fn (array $data) => $this->mutateEmbeddedMedia($data, 'image_url')),
                ])
                ->actions([
                    \Filament\Tables\Actions\EditAction::make()
                        ->form(fn (Form $form) => OurStoryGalleryImageResource::form($form))
                        ->mutateFormDataUsing(fn (array $data) => $this->mutateEmbeddedMedia($data, 'image_url', $this->getMountedTableActionRecord())),
                    \Filament\Tables\Actions\DeleteAction::make(),
                ]),
        );
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.embedded-crud-table');
    }
}
