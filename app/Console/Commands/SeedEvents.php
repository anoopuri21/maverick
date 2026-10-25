<?php

namespace App\Console\Commands;

use Database\Seeders\UpcomingEventsSeeder;
use Illuminate\Console\Command;

class SeedEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed only the events data from Upcoming-Events.docx without touching other database tables';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Seeding events database table...');

        $seeder = new UpcomingEventsSeeder();
        $seeder->setCommand($this);
        $seeder->run();

        $this->info('Events seeded successfully!');

        return Command::SUCCESS;
    }
}
