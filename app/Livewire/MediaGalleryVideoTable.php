<?php

namespace App\Livewire;

use App\Filament\Resources\MediaGalleryVideoResource;
use App\Models\MediaGalleryVideo;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

/**
 * Embedded Featured Videos CRUD table for the Media Gallery page.
 * Reuses MediaGalleryVideoResource::form() and ::table().
 */
class MediaGalleryVideoTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return MediaGalleryVideoResource::table(
            $table
                ->query(MediaGalleryVideo::query())
                ->headerActions([
                    \Filament\Tables\Actions\CreateAction::make()
                        ->form(fn (Form $form) => MediaGalleryVideoResource::form($form)),
                ])
                ->actions([
                    \Filament\Tables\Actions\EditAction::make()
                        ->form(fn (Form $form) => MediaGalleryVideoResource::form($form)),
                    \Filament\Tables\Actions\DeleteAction::make(),
                ]),
        );
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.embedded-crud-table');
    }
}
