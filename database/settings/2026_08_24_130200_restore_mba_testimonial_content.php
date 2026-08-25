<?php

use Illuminate\Support\Facades\DB;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $payload = DB::table('settings')
            ->where('group', 'mba_masters_testimonials')
            ->where('name', 'items')
            ->value('payload');

        $items = is_string($payload) ? json_decode($payload, true) : $payload;

        if (is_array($items) && $items !== []) {
            return;
        }

        $this->migrator->update('mba_masters_testimonials.items', fn () => [
            [
                'name' => 'Sara Al Maktoum',
                'role' => 'Operations Lead · Dubai',
                'quote' => 'The online rhythm fitted around my role. Advisors kept enrolment and fees clear from day one.',
                'photo' => null,
                'photo_asset_id' => null,
            ],
            [
                'name' => 'James Okonkwo',
                'role' => 'Finance Manager · Abu Dhabi',
                'quote' => 'I needed a recognised MBA without pausing my career. Live sessions plus recordings made that possible.',
                'photo' => null,
                'photo_asset_id' => null,
            ],
            [
                'name' => 'Priya Menon',
                'role' => 'HR Business Partner · Sharjah',
                'quote' => 'Clear pathway, solid partner universities, and a team that answered the hard questions on recognition.',
                'photo' => null,
                'photo_asset_id' => null,
            ],
        ]);

        $intro = DB::table('settings')
            ->where('group', 'mba_masters_testimonials')
            ->where('name', 'intro')
            ->value('payload');
        $intro = is_string($intro) ? json_decode($intro, true) : $intro;

        if (! is_string($intro) || str_contains(strtolower($intro), 'placeholder')) {
            $this->migrator->update(
                'mba_masters_testimonials.intro',
                fn () => 'Perspectives from professionals who chose to keep learning alongside their work.'
            );
        }
    }
};
