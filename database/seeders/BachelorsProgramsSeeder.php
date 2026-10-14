<?php

namespace Database\Seeders;

/**
 * Seed only the source-verified Bachelor pages. The parent seeder resolves the
 * existing `bachelors` category and GAU partner without creating either.
 *
 * Staging:    php artisan db:seed --class=BachelorsProgramsSeeder
 * Production: php artisan db:seed --class=BachelorsProgramsSeeder --force
 */
class BachelorsProgramsSeeder extends CatalogProgramsSeeder
{
    protected function sources(): array
    {
        return ['bachelors_programs.php'];
    }

    protected function existingReferencesOnly(): bool
    {
        return true;
    }
}
