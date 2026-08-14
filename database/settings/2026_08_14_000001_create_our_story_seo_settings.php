<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('our_story_seo.meta_title', null);
        $this->migrator->add('our_story_seo.meta_description', null);
        $this->migrator->add('our_story_seo.meta_keywords', null);
        $this->migrator->add('our_story_seo.canonical_url', null);
        $this->migrator->add('our_story_seo.robots', 'index, follow');
        $this->migrator->add('our_story_seo.og_title', null);
        $this->migrator->add('our_story_seo.og_description', null);
        $this->migrator->add('our_story_seo.og_image_url', null);
        $this->migrator->add('our_story_seo.og_type', 'website');
        $this->migrator->add('our_story_seo.twitter_card', 'summary_large_image');
        $this->migrator->add('our_story_seo.twitter_title', null);
        $this->migrator->add('our_story_seo.twitter_description', null);
        $this->migrator->add('our_story_seo.twitter_image_url', null);
        $this->migrator->add('our_story_seo.schema_json', null);
        $this->migrator->add('our_story_seo.custom_head_scripts', null);
        $this->migrator->add('our_story_seo.custom_body_scripts', null);
    }
};
