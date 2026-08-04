<?php

namespace App\Filament\Resources\MediaPressMentionResource\Pages;

use App\Filament\Resources\MediaPressMentionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMediaPressMention extends EditRecord
{
    protected static string $resource = MediaPressMentionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
