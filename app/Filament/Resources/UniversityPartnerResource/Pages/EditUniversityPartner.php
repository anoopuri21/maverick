<?php

namespace App\Filament\Resources\UniversityPartnerResource\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\UniversityPartnerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUniversityPartner extends EditRecord
{
    use HandlesCloudinaryImageFields;

    protected static string $resource = UniversityPartnerResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $logos = $data['recognition_logos'] ?? null;
        unset($data['recognition_logos']);

        $data = MediaPicker::syncFieldFromAsset($data, 'logo_url');
        $data = MediaPicker::syncFieldFromAsset($data, 'campus_image_url');
        $data = $this->preserveExistingImageFields($data, $this->record);

        if ($logos !== null) {
            $data['recognition_logos'] = $logos;
        }

        return UniversityPartnerResource::cleanRecognitionLogos($data);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
