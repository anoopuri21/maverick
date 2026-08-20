<?php
namespace App\Filament\Resources\GlobalPathwayResource\Pages;
use App\Filament\Resources\GlobalPathwayResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditGlobalPathway extends EditRecord
{
    protected static string $resource = GlobalPathwayResource::class;
    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
