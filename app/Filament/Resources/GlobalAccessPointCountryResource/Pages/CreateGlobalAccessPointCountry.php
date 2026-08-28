<?php

namespace App\Filament\Resources\GlobalAccessPointCountryResource\Pages;

use App\Filament\Resources\GlobalAccessPointCountryResource;
use App\Models\GlobalAccessPointCountry;
use App\Support\IsoCountries;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateGlobalAccessPointCountry extends CreateRecord
{
    protected static string $resource = GlobalAccessPointCountryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (filled($data['iso_numeric'] ?? null)) {
            $country = IsoCountries::find($data['iso_numeric']);
            if ($country) {
                $data['iso2'] = $country['iso2'];
                $data['name'] = $data['name'] ?? $country['name'];
            }
        }

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
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

        return parent::handleRecordCreation($data);
    }
}
