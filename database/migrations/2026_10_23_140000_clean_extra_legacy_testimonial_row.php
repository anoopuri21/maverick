<?php

use App\Support\PublicContentCache;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('our_story_testimonials')) {
            return;
        }

        // Remove any leftover legacy placeholder rows imported from old settings migrations
        // so the DB table cleanly starts with the exact 10 verified student testimonials.
        DB::table('our_story_testimonials')
            ->whereNull('country')
            ->whereIn('name', [
                'MBA graduate',
                'MSc graduate',
                'Sara Al Maktoum',
                'James Okonkwo',
                'Priya Menon',
                'Mohammad Taha',
                'Sanika',
                'Jack Boath',
                'Student',
            ])
            ->delete();

        try {
            PublicContentCache::flush();
        } catch (\Throwable) {
        }
    }

    public function down(): void
    {
    }
};
