<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // ─── Hero ───
        $this->migrator->add('global_opportunities_page.tag', 'GLOBAL OPPORTUNITIES');
        $this->migrator->add('global_opportunities_page.heading', 'Learning Without');
        $this->migrator->add('global_opportunities_page.heading_italic', 'Borders');
        $this->migrator->add('global_opportunities_page.description', 'From study-abroad semesters to international internships and student exchange — Maverick Business Academy London opens doors to a truly global education. Step beyond the classroom and build experience the world will recognise.');
        $this->migrator->add('global_opportunities_page.background_image', 'https://images.pexels.com/photos/3184292/pexels-photo-3184292.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=1600');
        $this->migrator->add('global_opportunities_page.background_image_asset_id', null);

        // ─── Overview ───
        $this->migrator->add('global_opportunities_page.overview_label', 'Go Global');
        $this->migrator->add('global_opportunities_page.overview_heading', 'Experience that takes you');
        $this->migrator->add('global_opportunities_page.overview_heading_italic', 'beyond the classroom');
        $this->migrator->add('global_opportunities_page.overview_body', 'A global education is about more than a qualification — it is about how you see the world, how you work with people from different cultures, and how confidently you can move between markets. At Maverick, we build real international experiences into your journey so that when you graduate, you are not just ready for a job abroad — you are ready to lead anywhere.\n\nThrough study-abroad semesters, student exchange, international internships and exclusive European partnership tracks, our opportunities are designed to be practical, affordable and genuinely life-changing.');
    }
};
