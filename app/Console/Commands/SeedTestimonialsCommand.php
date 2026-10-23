<?php

namespace App\Console\Commands;

use Database\Seeders\TestimonialSeeder;
use Illuminate\Console\Command;

class SeedTestimonialsCommand extends Command
{
    protected $signature = 'testimonials:seed
                            {--download-from-doc : Attempt to download original images from Google Doc URLs}
                            {--force : Force execution without confirmation in production}';

    protected $description = 'Seed the 10 verified student testimonials into DB table `our_story_testimonials` for Global Content Admin management';

    public function handle(): int
    {
        $this->info('===========================================================');
        $this->info('  Maverick Business Academy — Testimonials DB Seeder');
        $this->info('===========================================================');

        $seeder = app(TestimonialSeeder::class);
        $seeder->setCommand($this);
        $seeder->run();

        $this->line('');
        $this->info('All 10 student testimonials are now stored in DB (`our_story_testimonials`) and managed exclusively from:');
        $this->line('  -> Admin Panel: Global Content -> Testimonials');

        return self::SUCCESS;
    }
}
