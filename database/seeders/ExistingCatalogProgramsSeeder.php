<?php

namespace Database\Seeders;

/**
 * Seed the 67 approved catalogue pages without creating categories or partners.
 * Refuse the whole run if any declared reference is missing on the server.
 * The 59 legacy Master/BBA/GAU programmes are not seeded by this class.
 *
 * Staging:    php artisan db:seed --class=ExistingCatalogProgramsSeeder
 * Production: php artisan db:seed --class=ExistingCatalogProgramsSeeder --force
 */
class ExistingCatalogProgramsSeeder extends CatalogProgramsSeeder
{
    protected function existingReferencesOnly(): bool
    {
        return true;
    }
}
