<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // ─── Basic SEO ───
        $this->migrator->add('homepage_seo.meta_title', null);
        $this->migrator->add('homepage_seo.meta_description', null);
        $this->migrator->add('homepage_seo.meta_keywords', null);
        $this->migrator->add('homepage_seo.canonical_url', null);
        $this->migrator->add('homepage_seo.robots', 'index, follow');

        // ─── Open Graph ───
        $this->migrator->add('homepage_seo.og_title', null);
        $this->migrator->add('homepage_seo.og_description', null);
        $this->migrator->add('homepage_seo.og_image_url', null);
        $this->migrator->add('homepage_seo.og_image_url_asset_id', null);
        $this->migrator->add('homepage_seo.og_type', 'website');

        // ─── Twitter ───
        $this->migrator->add('homepage_seo.twitter_card', 'summary_large_image');
        $this->migrator->add('homepage_seo.twitter_title', null);
        $this->migrator->add('homepage_seo.twitter_description', null);
        $this->migrator->add('homepage_seo.twitter_image_url', null);
        $this->migrator->add('homepage_seo.twitter_image_url_asset_id', null);

        // ─── Advanced ───
        $this->migrator->add('homepage_seo.schema_json', null);
        $this->migrator->add('homepage_seo.custom_head_scripts', null);
        $this->migrator->add('homepage_seo.custom_body_scripts', null);
    }
};
