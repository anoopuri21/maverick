<?php

namespace App\Filament\Concerns;

use Filament\Notifications\Notification;

trait HasStrictValidation
{
    /**
     * Intercept validation errors and show detailed popup.
     */
    protected function onValidationError(\Illuminate\Validation\ValidationException $exception): void
    {
        $errors = $exception->validator->errors()->all();
        
        Notification::make()
            ->title('⚠️ Validation Failed')
            ->body('Please fix the following errors before saving:')
            ->danger()
            ->persistent()
            ->send();

        foreach (array_slice($errors, 0, 5) as $error) {
            Notification::make()
                ->title('Error')
                ->body($error)
                ->danger()
                ->send();
        }
    }
}