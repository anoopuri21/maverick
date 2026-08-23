<?php

namespace App\Filament\Resources\StudentSuccessVideoResource\Pages;

use App\Filament\Resources\StudentSuccessVideoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudentSuccessVideos extends ListRecords
{
    protected static string $resource = StudentSuccessVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
