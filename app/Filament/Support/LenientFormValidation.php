<?php

namespace App\Filament\Support;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class LenientFormValidation
{
    /** Fields where an inferred format/length rule must never be applied. */
    private const EXCLUDED_FIELDS = [
        'password',
        'password_confirmation',
        'current_password',
        'secret',
        'client_secret',
        'api_secret',
        'api_key',
        'refresh_token',
        'token',
    ];

    public static function register(): void
    {
        if (! (bool) config('app.filament_lenient_validation', true)) {
            return;
        }

        TextInput::configureUsing(function (TextInput $component): void {
            self::applyToTextInput($component);
        });

        Textarea::configureUsing(function (Textarea $component): void {
            self::applyToTextarea($component);
        });
    }

    protected static function applyToTextInput(TextInput $component): void
    {
        $name = self::fieldName($component);

        if ($name === '' || self::isExcluded($name)) {
            return;
        }

        if (self::matchesEmailField($name)) {
            $component->email()->nullable();

            return;
        }

        if (self::matchesPhoneField($name)) {
            $component->tel()->nullable();

            return;
        }

        if (self::matchesCoordinateField($name)) {
            $component->numeric()->nullable();

            return;
        }

        if (self::matchesNumericField($name)) {
            $component->numeric()->minValue(0)->nullable();

            return;
        }

        if (! self::hasMaxLength($component)) {
            $component->maxLength(255);
        }
    }

    protected static function applyToTextarea(Textarea $component): void
    {
        $name = self::fieldName($component);

        if ($name === '' || self::isExcluded($name)) {
            return;
        }

        if ($name === 'schema_json') {
            $component->rules(['nullable', 'json']);

            return;
        }

        if (! self::hasMaxLength($component) && ! str_contains($name, 'content') && ! str_contains($name, 'answer')) {
            $component->maxLength(65535);
        }
    }

    protected static function isExcluded(string $name): bool
    {
        return in_array($name, self::EXCLUDED_FIELDS, true)
            || str_contains($name, 'password')
            || str_ends_with($name, '_secret')
            || str_ends_with($name, '_token');
    }

    protected static function fieldName(Component $component): string
    {
        $name = (string) $component->getName();

        return str_contains($name, '.') ? (string) str($name)->afterLast('.') : $name;
    }

    protected static function matchesEmailField(string $name): bool
    {
        return $name === 'email' || str_ends_with($name, '_email');
    }

    protected static function matchesPhoneField(string $name): bool
    {
        return in_array($name, ['phone', 'mobile', 'whatsapp'], true)
            || str_ends_with($name, '_phone')
            || str_ends_with($name, '_mobile');
    }

    protected static function matchesCoordinateField(string $name): bool
    {
        return in_array($name, ['latitude', 'longitude', 'lat', 'lng'], true);
    }

    protected static function matchesNumericField(string $name): bool
    {
        return in_array($name, ['sort_order', 'stars', 'reading_time', 'port', 'rating', 'width', 'height', 'size'], true)
            || str_ends_with($name, '_order')
            || str_ends_with($name, '_count');
    }

    protected static function hasMaxLength(TextInput|Textarea $component): bool
    {
        foreach ($component->getValidationRules() as $rule) {
            if (is_string($rule) && str_starts_with($rule, 'max:')) {
                return true;
            }
        }

        return false;
    }
}
