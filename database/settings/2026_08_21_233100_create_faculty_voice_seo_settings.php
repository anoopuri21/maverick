<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $group = 'faculty_voice_seo';

        $this->migrator->add("{$group}.meta_title", 'Faculty Voice | Maverick Business Academy');
        $this->migrator->add("{$group}.meta_description", 'Perspectives from Maverick faculty and industry experts — leadership, strategy, global careers and the thinking that shapes business education.');
        $this->migrator->add("{$group}.meta_keywords", null);
        $this->migrator->add("{$group}.canonical_url", null);
        $this->migrator->add("{$group}.robots", 'index, follow');
        $this->migrator->add("{$group}.og_title", null);
        $this->migrator->add("{$group}.og_description", null);
        $this->migrator->add("{$group}.og_image_url", null);
        $this->migrator->add("{$group}.og_image_url_asset_id", null);
        $this->migrator->add("{$group}.og_type", 'website');
        $this->migrator->add("{$group}.twitter_card", 'summary_large_image');
        $this->migrator->add("{$group}.twitter_title", null);
        $this->migrator->add("{$group}.twitter_description", null);
        $this->migrator->add("{$group}.twitter_image_url", null);
        $this->migrator->add("{$group}.twitter_image_url_asset_id", null);
        $this->migrator->add("{$group}.schema_json", null);
        $this->migrator->add("{$group}.custom_head_scripts", null);
        $this->migrator->add("{$group}.custom_body_scripts", null);
    }
};
