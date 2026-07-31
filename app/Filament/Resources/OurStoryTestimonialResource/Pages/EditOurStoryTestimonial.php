<?php

namespace App\Filament\Resources\OurStoryTestimonialResource\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Resources\OurStoryTestimonialResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOurStoryTestimonial extends EditRecord
{
    use HandlesCloudinaryImageFields;

    protected static string $resource = OurStoryTestimonialResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->preserveExistingImageFields($data, $this->record);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
