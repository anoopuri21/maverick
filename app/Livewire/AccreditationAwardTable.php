<?php

namespace App\Livewire;

use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\AccreditationAwardResource;
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

class AccreditationAwardTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return AccreditationAwardResource::table(
            $table->query(AccreditationAwardResource::getEloquentQuery())
        )
            ->headerActions([
                CreateAction::make()
                    ->form(fn (Form $form) => AccreditationAwardResource::form($form))
                    ->mutateFormDataUsing(fn (array $data): array => MediaPicker::syncFieldFromAsset($data, 'logo_url')),
            ])
            ->actions([
                EditAction::make()
                    ->form(fn (Form $form) => AccreditationAwardResource::form($form))
                    ->mutateFormDataUsing(fn (array $data): array => MediaPicker::syncFieldFromAsset($data, 'logo_url')),
                DeleteAction::make(),
            ]);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.embedded-crud-table');
    }
}
