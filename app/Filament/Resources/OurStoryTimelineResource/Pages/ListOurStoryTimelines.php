<?php

namespace App\Filament\Resources\OurStoryTimelineResource\Pages;

use App\Filament\Resources\OurStoryTimelineResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOurStoryTimelines extends ListRecords
{
    protected static string $resource = OurStoryTimelineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
