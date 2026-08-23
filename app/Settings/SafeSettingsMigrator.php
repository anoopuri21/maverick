<?php

namespace App\Settings;

use Spatie\LaravelSettings\Migrations\SettingsMigrator;

/**
 * Idempotent SettingsMigrator — `add()` never throws SettingAlreadyExists.
 *
 * Spatie's SettingsMigrator::add() throws if the setting already exists, which
 * breaks `php artisan migrate` on environments where the settings table already
 * holds rows (e.g. after a fresh copy, or a settings migration re-run). This
 * subclass makes `add()` a no-op when the property already exists, so settings
 * migrations are safe to run repeatedly.
 *
 * Bound in AppServiceProvider: `SettingsMigrator::class` -> this class.
 * No migration files need changes.
 */
class SafeSettingsMigrator extends SettingsMigrator
{
    public function add(string $property, $value = null, bool $encrypted = false): void
    {
        if ($this->exists($property)) {
            return;
        }

        parent::add($property, $value, $encrypted);
    }
}
