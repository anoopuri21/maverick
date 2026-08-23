<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $pageKeys = [
            'faculty_voice_page.hero_tag',
            'faculty_voice_page.hero_heading',
            'faculty_voice_page.hero_heading_italic',
            'faculty_voice_page.hero_description',
            'faculty_voice_page.hero_background_image',
            'faculty_voice_page.hero_background_image_asset_id',
            'faculty_voice_page.empty_message',
        ];

        foreach ($pageKeys as $key) {
            if ($this->migrator->exists($key)) {
                $this->migrator->delete($key);
            }
        }

        $seoKeys = [
            'faculty_voice_seo.meta_title',
            'faculty_voice_seo.meta_description',
            'faculty_voice_seo.meta_keywords',
            'faculty_voice_seo.canonical_url',
            'faculty_voice_seo.robots',
            'faculty_voice_seo.og_title',
            'faculty_voice_seo.og_description',
            'faculty_voice_seo.og_image_url',
            'faculty_voice_seo.og_image_url_asset_id',
            'faculty_voice_seo.og_type',
            'faculty_voice_seo.twitter_card',
            'faculty_voice_seo.twitter_title',
            'faculty_voice_seo.twitter_description',
            'faculty_voice_seo.twitter_image_url',
            'faculty_voice_seo.twitter_image_url_asset_id',
            'faculty_voice_seo.schema_json',
            'faculty_voice_seo.custom_head_scripts',
            'faculty_voice_seo.custom_body_scripts',
        ];

        foreach ($seoKeys as $key) {
            if ($this->migrator->exists($key)) {
                $this->migrator->delete($key);
            }
        }
    }
};
