<?php

namespace App\Filament\Resources\OurStoryAwardResource\Pages;

use App\Filament\Resources\OurStoryAwardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOurStoryAwards extends ListRecords
{
    protected static string $resource = OurStoryAwardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
