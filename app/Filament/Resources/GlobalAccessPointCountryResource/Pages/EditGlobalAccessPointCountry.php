<?php

namespace App\Filament\Resources\GlobalAccessPointCountryResource\Pages;

use App\Filament\Resources\GlobalAccessPointCountryResource;
use App\Support\IsoCountries;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGlobalAccessPointCountry extends EditRecord
{
    protected static string $resource = GlobalAccessPointCountryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (filled($data['iso_numeric'] ?? null)) {
            $country = IsoCountries::find($data['iso_numeric']);
            if ($country) {
                $data['iso2'] = $country['iso2'];
                if (! filled($data['name'] ?? null)) {
                    $data['name'] = $country['name'];
                }
            }
        }

        return $data;
    }
}
