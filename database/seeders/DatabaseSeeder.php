<?php

namespace Database\Seeders;

use App\Models\User;
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
        // User::factory(10)->create();

        if (!User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        $this->call(ProgramSeeder::class);
        $this->call(BscPsychologyProgramSeeder::class);
        $this->call(RushfordBbaProgramSeeder::class);
        $this->call(MasterProgramsSeeder::class);
        $this->call(AwardSeeder::class);
        $this->call(FacultyInsightSeeder::class);
        $this->call(TestimonialSeeder::class);
    }
}
