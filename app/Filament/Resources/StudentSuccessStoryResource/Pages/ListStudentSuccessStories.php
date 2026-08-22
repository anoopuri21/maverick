<?php

namespace App\Filament\Resources\StudentSuccessStoryResource\Pages;

use App\Filament\Resources\StudentSuccessStoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudentSuccessStories extends ListRecords
{
    protected static string $resource = StudentSuccessStoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
