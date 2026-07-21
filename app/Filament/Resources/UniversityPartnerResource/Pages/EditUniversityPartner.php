<?php

namespace App\Filament\Resources\UniversityPartnerResource\Pages;

use App\Filament\Resources\UniversityPartnerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUniversityPartner extends EditRecord
{
    protected static string $resource = UniversityPartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
