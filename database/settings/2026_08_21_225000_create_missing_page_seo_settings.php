<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->addGroup('pathway_programs_seo', [
            'meta_title' => 'Pathway Programs | Maverick Business Academy London',
            'meta_description' => 'Explore structured global pathway programmes at Maverick Business Academy London. Start closer to home, progress to internationally recognised partner universities, and build your future with flexible, career-focused learning.',
        ]);

        $this->addGroup('global_opportunities_seo', [
            'meta_title' => 'Global Opportunities | Maverick Business Academy London',
            'meta_description' => 'Explore global opportunities at Maverick Business Academy London — study abroad, student exchange, international internships and European partnership programmes. Build experience the world recognises.',
        ]);

        $this->addGroup('accreditations_seo', [
            'meta_title' => 'Accreditations & Recognitions - Maverick Business Academy',
            'meta_description' => "Explore Maverick Business Academy's accreditations, partnerships with leading universities, and industry recognition awards.",
        ]);

        $this->addGroup('media_gallery_seo', [
            'meta_title' => 'Media Gallery - Maverick Business Academy',
            'meta_description' => null,
        ]);

        $this->addGroup('contact_seo', [
            'meta_title' => 'Contact Us | Maverick Business Academy London',
            'meta_description' => 'Connect with the admissions and partnerships team at Maverick Business Academy London. Reach our Sharjah / Dubai campus for inquiries.',
        ]);

        $this->addGroup('events_seo', [
            'meta_title' => 'Events - Maverick Business Academy',
            'meta_description' => 'Explore upcoming events, webinars, workshops and masterclasses from Maverick Business Academy.',
        ]);

        $this->addGroup('student_success_seo', [
            'meta_title' => 'Student Success - Maverick Business Academy',
            'meta_description' => 'Real stories from Maverick students and graduates — their journeys, achievements and transformations.',
        ]);

        $this->addGroup('programs_listing_seo', [
            'meta_title' => 'Programmes | Maverick Business Academy',
            'meta_description' => 'Explore Maverick Business Academy programmes — Bachelors, Masters, MBA, Diplomas and professional courses.',
        ]);
    }

    private function addGroup(string $group, array $defaults): void
    {
        $this->migrator->add("{$group}.meta_title", $defaults['meta_title']);
        $this->migrator->add("{$group}.meta_description", $defaults['meta_description']);
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
