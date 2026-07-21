<?php

namespace App\Filament\Resources\FacultyInsightResource\Pages;

use App\Filament\Resources\FacultyInsightResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFacultyInsight extends EditRecord
{
    protected static string $resource = FacultyInsightResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
