<?php

namespace App\Filament\Concerns;

use Filament\Notifications\Notification;
use Throwable;

trait SavesSettingsGroups
{
    use EnsuresSettingsRowsExist, HandlesCloudinaryImageFields;

    /** @param  class-string  $settingsClass */
    protected function saveSettingsGroup(string $settingsClass, array $payload): bool
    {
        try {
            $settings = app($settingsClass);
            $payload = $this->ensureAllSettingsProperties($settings, $payload);
            $payload = $this->preserveExistingImageFields($payload, $settings);
            $this->ensureSettingsRowsExist($settings);
            app()->forgetInstance($settingsClass);
            app($settingsClass)->fill($payload)->save();

            return true;
        } catch (Throwable $e) {
            report($e);

            Notification::make()
                ->title('Could not save settings')
                ->body('Please check your input and try again.')
                ->danger()
                ->send();

            return false;
        }
    }
}
