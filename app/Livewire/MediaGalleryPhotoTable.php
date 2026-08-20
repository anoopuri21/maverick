<?php

namespace App\Livewire;

use App\Filament\Resources\MediaGalleryPhotoResource;
use App\Models\MediaGalleryPhoto;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

/**
 * Embedded Gallery Photos CRUD table for the Media Gallery page.
 * Reuses MediaGalleryPhotoResource::form() and ::table().
 */
class MediaGalleryPhotoTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return MediaGalleryPhotoResource::table(
            $table
                ->query(MediaGalleryPhoto::query())
                ->headerActions([
                    \Filament\Tables\Actions\CreateAction::make()
                        ->form(fn (Form $form) => MediaGalleryPhotoResource::form($form)),
                ])
                ->actions([
                    \Filament\Tables\Actions\EditAction::make()
                        ->form(fn (Form $form) => MediaGalleryPhotoResource::form($form)),
                    \Filament\Tables\Actions\DeleteAction::make(),
                ]),
        );
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.embedded-crud-table');
    }
}
