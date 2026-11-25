<?php

namespace App\Filament\Concerns;

/**
 * Backwards-compatible name for image-field helpers used across existing
 * resources. Storage is now self-hosted; new code should use
 * HandlesStoredImageFields directly when touching these forms.
 */
trait HandlesCloudinaryImageFields
{
    use HandlesStoredImageFields;
}
