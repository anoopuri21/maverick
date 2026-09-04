<?php

namespace App\Livewire;

use App\Filament\Resources\GupPartnerUniversityResource;
use App\Livewire\Concerns\MutatesEmbeddedMediaPicker;
use App\Models\GupPartnerUniversity;
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

class GupPartnerUniversityTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    use MutatesEmbeddedMediaPicker;

    public function table(Table $table): Table
    {
        return GupPartnerUniversityResource::table(
            $table->query(GupPartnerUniversityResource::getEloquentQuery())
        )
            ->headerActions([
                CreateAction::make()
                    ->form(fn (Form $form) => GupPartnerUniversityResource::form($form))
                    ->mutateFormDataUsing(fn (array $data) => $this->mutateEmbeddedMedia($data, 'logo_url')),
            ])
            ->actions([
                EditAction::make()
                    ->form(fn (Form $form) => GupPartnerUniversityResource::form($form))
                    ->mutateFormDataUsing(fn (array $data) => $this->mutateEmbeddedMedia($data, 'logo_url', $this->getMountedTableActionRecord())),
                DeleteAction::make(),
            ]);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.embedded-crud-table');
    }
}
