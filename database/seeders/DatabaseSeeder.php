<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Demo content only. Admin accounts are never seeded: create one with
        // `php artisan make:filament-user` (or Filament's admin UI) instead.

        $this->call(ProgramSeeder::class);
        $this->call(BscPsychologyProgramSeeder::class);
        $this->call(RushfordBbaProgramSeeder::class);
        $this->call(MasterProgramsSeeder::class);
        $this->call(CatalogProgramsSeeder::class);
        $this->call(AwardSeeder::class);
        $this->call(FacultyInsightSeeder::class);
        $this->call(TestimonialSeeder::class);
        $this->call(UpcomingEventsSeeder::class);
    }
}
