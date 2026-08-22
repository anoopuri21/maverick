<?php

namespace App\Livewire;

use App\Filament\Resources\AccreditationAwardResource;
use App\Livewire\Concerns\MutatesEmbeddedMediaPicker;
use App\Models\PartnerLogo;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

/**
 * Embedded Awards & Recognition CRUD table for the Accreditation page.
 * Reuses AccreditationAwardResource::form() and ::table() (model scoped to
 * type='award' via the resource's getEloquentQuery).
 */
class AccreditationAwardTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    use MutatesEmbeddedMediaPicker;

    public function table(Table $table): Table
    {
        return AccreditationAwardResource::table(
            $table
                ->query(\App\Filament\Resources\AccreditationAwardResource::getEloquentQuery())
                ->headerActions([
                    \Filament\Tables\Actions\CreateAction::make()
                        ->form(fn (Form $form) => AccreditationAwardResource::form($form))
                        ->mutateFormDataUsing(fn (array $data) => $this->mutateEmbeddedMedia($data, 'logo_url', extra: ['type' => 'award'])),
                ])
                ->actions([
                    \Filament\Tables\Actions\EditAction::make()
                        ->form(fn (Form $form) => AccreditationAwardResource::form($form))
                        ->mutateFormDataUsing(fn (array $data) => $this->mutateEmbeddedMedia($data, 'logo_url', $this->getMountedTableActionRecord(), ['type' => 'award'])),
                    \Filament\Tables\Actions\DeleteAction::make(),
                ]),
        );
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.embedded-crud-table');
    }
}
