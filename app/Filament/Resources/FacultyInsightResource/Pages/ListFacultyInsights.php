<?php

namespace App\Filament\Resources\FacultyInsightResource\Pages;

use App\Filament\Resources\FacultyInsightResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFacultyInsights extends ListRecords
{
    protected static string $resource = FacultyInsightResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
