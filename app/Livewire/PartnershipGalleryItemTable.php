<?php

namespace App\Livewire;

use App\Filament\Resources\PartnershipGalleryItemResource;
use App\Livewire\Concerns\MutatesEmbeddedMediaPicker;
use App\Models\PartnershipGalleryItem;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

/**
 * Embedded Partnership Gallery CRUD table for the Global University Partners page.
 * Reuses PartnershipGalleryItemResource::form() and ::table().
 */
class PartnershipGalleryItemTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    use MutatesEmbeddedMediaPicker;

    public function table(Table $table): Table
    {
        return PartnershipGalleryItemResource::table(
            $table
                ->query(PartnershipGalleryItem::query())
                ->headerActions([
                    \Filament\Tables\Actions\CreateAction::make()
                        ->form(fn (Form $form) => PartnershipGalleryItemResource::form($form))
                        ->mutateFormDataUsing(fn (array $data) => $this->mutateEmbeddedMedia($data, 'image_url')),
                ])
                ->actions([
                    \Filament\Tables\Actions\EditAction::make()
                        ->form(fn (Form $form) => PartnershipGalleryItemResource::form($form))
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
