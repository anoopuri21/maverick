<?php

namespace App\Livewire;

use App\Filament\Resources\GlobalAccessPointCountryResource;
use App\Models\GlobalAccessPointCountry;
use App\Support\IsoCountries;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class GlobalAccessPointCountryTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return GlobalAccessPointCountryResource::table(
            $table->query(GlobalAccessPointCountryResource::getEloquentQuery())
        )
            ->headerActions([
                CreateAction::make()
                    ->form(fn (Form $form) => GlobalAccessPointCountryResource::form($form))
                    ->using(function (array $data): GlobalAccessPointCountry {
                        $data = $this->fillCountryCodes($data);
                        $existing = GlobalAccessPointCountry::withTrashed()
                            ->where('iso_numeric', $data['iso_numeric'])
                            ->first();

                        if ($existing) {
                            if ($existing->trashed()) {
                                $existing->restore();
                            }
                            $existing->fill($data)->save();

                            return $existing->refresh();
                        }

                        return GlobalAccessPointCountry::query()->create($data);
                    }),
            ])
            ->actions([
                EditAction::make()
                    ->form(fn (Form $form) => GlobalAccessPointCountryResource::form($form))
                    ->mutateFormDataUsing(fn (array $data): array => $this->fillCountryCodes($data)),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ]);
    }

    /** @param  array<string, mixed>  $data */
    protected function fillCountryCodes(array $data): array
    {
        if (! filled($data['iso_numeric'] ?? null)) {
            return $data;
        }

        $country = IsoCountries::find($data['iso_numeric']);
        if (! $country) {
            return $data;
        }

        $data['iso2'] = $country['iso2'];
        if (! filled($data['name'] ?? null)) {
            $data['name'] = $country['name'];
        }

        return $data;
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.embedded-crud-table');
    }
}
