<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('mba_masters_hero.eyebrow', 'Online MBA & Master\'s · UAE');
        $this->migrator->add('mba_masters_hero.headline', 'Affordable Online MBA & Master\'s Programs in UAE');
        $this->migrator->add('mba_masters_hero.subheading', 'Flexible online learning for working professionals — 10–12 month / 1-year routes, fee support, and MBA & Master\'s specializations designed around your career.');
        $this->migrator->add('mba_masters_hero.background_image', 'assets/images/edutainment/hero-cinematic.jpg');
        $this->migrator->add('mba_masters_hero.background_image_asset_id', null);
        $this->migrator->add('mba_masters_hero.cta_primary_label', 'Apply Now');
        $this->migrator->add('mba_masters_hero.cta_primary_url', '#mlp-enquire');
        $this->migrator->add('mba_masters_hero.cta_secondary_label', 'Speak to an Advisor');
        $this->migrator->add('mba_masters_hero.cta_secondary_url', '#mlp-enquire');
        $this->migrator->add('mba_masters_hero.cta_tertiary_label', 'Request Fee Details');
        $this->migrator->add('mba_masters_hero.cta_tertiary_url', '#mlp-enquire');
        $this->migrator->add('mba_masters_hero.form_title', 'Start your enquiry');

        $this->migrator->add('mba_masters_trust.label', 'Trusted by learners across the UAE & beyond');
        $this->migrator->add('mba_masters_trust.stats', [
            ['value' => '4500+', 'label' => 'Students Supported'],
            ['value' => '1500+', 'label' => 'Alumni Graduated'],
            ['value' => '10+', 'label' => 'University Partners'],
            ['value' => '120+', 'label' => 'Corporate Events'],
            ['value' => '4.8', 'label' => 'Rating · 400+ Reviews'],
        ]);

        $this->migrator->add('mba_masters_seo.meta_title', 'Online MBA & Master\'s in UAE | Maverick Business Academy London');
        $this->migrator->add('mba_masters_seo.meta_description', 'Affordable Online MBA & Master\'s programs in UAE for working professionals. Flexible study, 1-year routes, scholarships & university partners. Enquire today.');
        $this->migrator->add('mba_masters_seo.meta_keywords', 'Online MBA UAE, Online MBA in Dubai, Online MBA in UAE fees, Master degree in UAE online, MBA in UAE for working professionals');
        $this->migrator->add('mba_masters_seo.canonical_url', null);
        $this->migrator->add('mba_masters_seo.robots', 'index, follow');
        $this->migrator->add('mba_masters_seo.og_title', null);
        $this->migrator->add('mba_masters_seo.og_description', null);
        $this->migrator->add('mba_masters_seo.og_image_url', null);
        $this->migrator->add('mba_masters_seo.og_image_url_asset_id', null);
        $this->migrator->add('mba_masters_seo.og_type', 'website');
        $this->migrator->add('mba_masters_seo.twitter_card', 'summary_large_image');
        $this->migrator->add('mba_masters_seo.twitter_title', null);
        $this->migrator->add('mba_masters_seo.twitter_description', null);
        $this->migrator->add('mba_masters_seo.twitter_image_url', null);
        $this->migrator->add('mba_masters_seo.twitter_image_url_asset_id', null);
        $this->migrator->add('mba_masters_seo.schema_json', null);
        $this->migrator->add('mba_masters_seo.custom_head_scripts', null);
        $this->migrator->add('mba_masters_seo.custom_body_scripts', null);
    }
};
