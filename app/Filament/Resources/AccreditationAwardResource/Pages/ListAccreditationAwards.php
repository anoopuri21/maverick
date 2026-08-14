<?php

namespace App\Filament\Resources\AccreditationAwardResource\Pages;

use App\Filament\Resources\AccreditationAwardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAccreditationAwards extends ListRecords
{
    protected static string $resource = AccreditationAwardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
