<?php

namespace App\Filament\Forms\Components;

use App\Models\MediaAsset;
use Closure;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Field;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class MediaPicker extends Field
{
    protected string $view = 'filament.forms.components.media-picker';

    protected string|Closure|null $folder = null;

    protected string|Closure|null $urlField = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->live();

        $this->afterStateUpdated(function (MediaPicker $component, $state, Set $set): void {
            $urlField = $component->getUrlField();

            if (! $urlField) {
                return;
            }

            if (! $state) {
                $set($urlField, null);

                return;
            }

            $asset = MediaAsset::query()->find($state);

            if ($asset) {
                $set($urlField, $asset->url);
            }
        });

        $this->registerActions([
            Action::make('chooseFromLibrary')
                ->label('Choose from Library')
                ->modalHeading('Media Library')
                ->modalWidth('5xl')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Close')
                ->modalContent(fn (MediaPicker $component): HtmlString => $component->renderModalContent('browse')),

            Action::make('uploadNew')
                ->label('Upload New')
                ->modalHeading('Upload Media')
                ->modalWidth('5xl')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Close')
                ->modalContent(fn (MediaPicker $component): HtmlString => $component->renderModalContent('upload')),
        ]);
    }

    public function folder(string|Closure|null $folder): static
    {
        $this->folder = $folder;

        return $this;
    }

    public function getFolder(): ?string
    {
        return $this->evaluate($this->folder);
    }

    public function urlField(string|Closure|null $urlField): static
    {
        $this->urlField = $urlField;

        return $this;
    }

    public function getUrlField(): ?string
    {
        return $this->evaluate($this->urlField);
    }

    protected function renderModalContent(string $initialTab): HtmlString
    {
        return new HtmlString(Blade::render(
            '@livewire(\App\Livewire\MediaLibraryModal::class, $attrs, key($key))',
            [
                'attrs' => [
                    'statePath' => $this->getStatePath(),
                    'folder' => $this->getFolder(),
                    'initialTab' => $initialTab,
                ],
                'key' => 'media-library-'.$this->getStatePath().'-'.$initialTab.'-'.uniqid(),
            ]
        ));
    }

    /**
     * Convention-based factory: state column is "{$fieldName}_asset_id",
     * the legacy URL column "$fieldName" is kept in sync for backward compat.
     *
     * Usage: MediaPicker::forField('image_url', 'programs')
     */
    public static function forField(string $fieldName, ?string $folder = null): static
    {
        return static::make("{$fieldName}_asset_id")
            ->label(str($fieldName)->replaceLast('_url', '')->headline()->toString())
            ->folder($folder)
            ->urlField($fieldName);
    }

    /**
     * Convention-based counterpart of syncUrlFromAsset() for forField() pickers.
     * Denormalizes "{$fieldName}_asset_id" into the legacy "$fieldName" column
     * before save. Leaves data untouched if the asset key is absent entirely.
     */
    public static function syncFieldFromAsset(array $data, string $fieldName): array
    {
        $assetKey = "{$fieldName}_asset_id";

        if (! array_key_exists($assetKey, $data)) {
            return $data;
        }

        if (! empty($data[$assetKey])) {
            $asset = MediaAsset::query()->find($data[$assetKey]);

            if ($asset) {
                $data[$fieldName] = $asset->url;
            }

            return $data;
        }

        $data[$assetKey] = null;
        $data[$fieldName] = null;

        return $data;
    }

    /**
     * Sync denormalized URL from media_asset_id before save.
     * Sets URL when an asset is selected; clears it when asset is removed.
     */
    public static function syncUrlFromAsset(array $data, string $urlField = 'photo'): array
    {
        if (! empty($data['media_asset_id'])) {
            $asset = MediaAsset::query()->find($data['media_asset_id']);

            if ($asset) {
                $data[$urlField] = $asset->url;
            }

            return $data;
        }

        $data['media_asset_id'] = null;
        $data[$urlField] = null;

        return $data;
    }
}
