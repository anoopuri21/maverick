<?php

namespace App\Filament\Resources\MediaPressMentionResource\Pages;

use App\Filament\Resources\MediaPressMentionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMediaPressMentions extends ListRecords
{
    protected static string $resource = MediaPressMentionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
