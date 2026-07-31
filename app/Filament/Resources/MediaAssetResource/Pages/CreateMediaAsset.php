<?php

namespace App\Filament\Resources\MediaAssetResource\Pages;

use App\Filament\Resources\MediaAssetResource;
use App\Models\MediaAsset;
use App\Services\MediaLibraryService;
use Filament\Resources\Pages\CreateRecord;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class CreateMediaAsset extends CreateRecord
{
    protected static string $resource = MediaAssetResource::class;

    protected function handleRecordCreation(array $data): MediaAsset
    {
        $state = $this->data['upload'] ?? null;
        $file = is_array($state) ? ($state[0] ?? null) : $state;

        if (! $file instanceof TemporaryUploadedFile) {
            throw new \RuntimeException('Please upload an image.');
        }

        $folder = $this->data['folder'] ?? 'library';
        if (! is_string($folder) || $folder === '') {
            $folder = 'library';
        }

        $asset = app(MediaLibraryService::class)->store(
            $file,
            $folder,
            $file->getClientOriginalName(),
        );

        $asset->update([
            'original_name' => $data['original_name'] ?? $asset->original_name,
            'alt' => $data['alt'] ?? $asset->alt,
        ]);

        return $asset;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
