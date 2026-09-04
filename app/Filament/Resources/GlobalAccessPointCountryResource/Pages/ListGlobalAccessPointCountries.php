<?php

namespace App\Filament\Resources\GlobalAccessPointCountryResource\Pages;

use App\Filament\Resources\GlobalAccessPointCountryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGlobalAccessPointCountries extends ListRecords
{
    protected static string $resource = GlobalAccessPointCountryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
