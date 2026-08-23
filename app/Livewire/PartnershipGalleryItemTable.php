<?php

namespace App\Livewire;

use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\PartnershipGalleryItemResource;
use App\Models\PartnershipGalleryItem;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class PartnershipGalleryItemTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return PartnershipGalleryItemResource::table(
            $table->query(PartnershipGalleryItem::query())
        )
            ->headerActions([
                CreateAction::make()
                    ->form(fn (Form $form) => PartnershipGalleryItemResource::form($form))
                    ->mutateFormDataUsing(fn (array $data): array => MediaPicker::syncFieldFromAsset($data, 'image_url')),
            ])
            ->actions([
                EditAction::make()
                    ->form(fn (Form $form) => PartnershipGalleryItemResource::form($form))
                    ->mutateFormDataUsing(fn (array $data): array => MediaPicker::syncFieldFromAsset($data, 'image_url')),
                DeleteAction::make(),
            ]);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.embedded-crud-table');
    }
}
