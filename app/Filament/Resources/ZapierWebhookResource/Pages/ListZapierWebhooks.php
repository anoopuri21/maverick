<?php

namespace App\Filament\Resources\ZapierWebhookResource\Pages;

use App\Filament\Resources\ZapierWebhookResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListZapierWebhooks extends ListRecords
{
    protected static string $resource = ZapierWebhookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
