<?php

namespace App\Livewire;

use App\Filament\Resources\GupPartnerUniversityResource;
use App\Models\GupPartnerUniversity;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

/**
 * Embedded Partner Universities CRUD table for the Global University Partners page.
 * Reuses GupPartnerUniversityResource::form() and ::table() (single source).
 */
class GupPartnerUniversityTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return GupPartnerUniversityResource::table(
            $table
                ->query(GupPartnerUniversity::query())
                ->headerActions([
                    \Filament\Tables\Actions\CreateAction::make()
                        ->form(fn (Form $form) => GupPartnerUniversityResource::form($form)),
                ])
                ->actions([
                    \Filament\Tables\Actions\EditAction::make()
                        ->form(fn (Form $form) => GupPartnerUniversityResource::form($form)),
                    \Filament\Tables\Actions\DeleteAction::make(),
                ]),
        );
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.embedded-crud-table');
    }
}
