<?php

namespace App\Filament\Resources\GupPartnerUniversityResource\Pages;

use App\Filament\Resources\GupPartnerUniversityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGupPartnerUniversities extends ListRecords
{
    protected static string $resource = GupPartnerUniversityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
