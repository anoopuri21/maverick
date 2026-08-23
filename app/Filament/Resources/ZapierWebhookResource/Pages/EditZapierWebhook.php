<?php

namespace App\Filament\Resources\ZapierWebhookResource\Pages;

use App\Filament\Resources\ZapierWebhookResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditZapierWebhook extends EditRecord
{
    protected static string $resource = ZapierWebhookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
