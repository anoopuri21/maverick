<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('our_story_impact.heading', null);
        $this->migrator->add('our_story_impact.description', null);
        $this->migrator->add('our_story_impact.stat_1_value', null);
        $this->migrator->add('our_story_impact.stat_1_label', null);
        $this->migrator->add('our_story_impact.stat_2_value', null);
        $this->migrator->add('our_story_impact.stat_2_label', null);
        $this->migrator->add('our_story_impact.stat_3_value', null);
        $this->migrator->add('our_story_impact.stat_3_label', null);
        $this->migrator->add('our_story_impact.stat_4_value', null);
        $this->migrator->add('our_story_impact.stat_4_label', null);
    }
};
