<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // ─── Hero ───
        $this->migrator->add('pathway_programs.tag', 'PATHWAY PROGRAMS');
        $this->migrator->add('pathway_programs.heading', 'Global');
        $this->migrator->add('pathway_programs.heading_italic', 'Pathway Programs');
        $this->migrator->add('pathway_programs.description', 'Explore Maverick Business Academy London\'s structured global pathway programmes — flexible routes that let you begin your studies here and progress towards an internationally recognised qualification.');
        $this->migrator->add('pathway_programs.background_image', 'https://images.pexels.com/photos/256541/pexels-photo-256541.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=1600');
        $this->migrator->add('pathway_programs.background_image_asset_id', null);

        // ─── Overview ───
        $this->migrator->add('pathway_programs.overview_label', 'AN OVERVIEW');
        $this->migrator->add('pathway_programs.overview_heading', 'Why Students Choose');
        $this->migrator->add('pathway_programs.overview_heading_italic', 'Maverick Pathways');
        $this->migrator->add('pathway_programs.overview_body', 'Our pathway programmes are built for learners who want a globally focused education without waiting years to begin. Start with Maverick Business Academy London, build a strong academic foundation with structured support, then progress smoothly towards an internationally recognised qualification. Every pathway is designed around flexibility, affordability and real-world career readiness, with dedicated guidance at every step — from choosing the right route to completing your studies with confidence.');
    }
};
