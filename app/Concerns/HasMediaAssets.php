<?php

namespace App\Concerns;

use App\Models\MediaAsset;

/**
 * Convention-based media asset resolution for Eloquent models.
 *
 * For a field named "image_url", the model may have an "image_url_asset_id"
 * column pointing to a MediaAsset. getMediaUrl() prefers the asset URL and
 * falls back to the legacy denormalized column. Zero per-model configuration;
 * supports any number of media fields per model. Not for Spatie Settings.
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait HasMediaAssets
{
    /** @var array<string, MediaAsset|null> */
    protected array $resolvedMediaAssets = [];

    public function getMediaUrl(string $fieldName): ?string
    {
        $asset = $this->mediaAsset($fieldName);

        if ($asset && $asset->url) {
            return $asset->url;
        }

        // Legacy fallback: missing columns simply return null.
        return $this->getAttribute($fieldName);
    }

    public function mediaAsset(string $fieldName): ?MediaAsset
    {
        if (array_key_exists($fieldName, $this->resolvedMediaAssets)) {
            return $this->resolvedMediaAssets[$fieldName];
        }

        $assetId = $this->getAttribute("{$fieldName}_asset_id");

        return $this->resolvedMediaAssets[$fieldName] = $assetId
            ? MediaAsset::query()->find($assetId)
            : null;
    }
}
