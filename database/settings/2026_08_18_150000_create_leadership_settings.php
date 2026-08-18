<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // ─── Hero ───
        $this->migrator->add('leadership_hero.tag', 'LEADERSHIP & GOVERNANCE');
        $this->migrator->add('leadership_hero.heading_line1', 'The Visionaries Behind');
        $this->migrator->add('leadership_hero.heading_italic', 'Maverick Academy');
        $this->migrator->add('leadership_hero.description', 'Meet the distinguished leaders and board members who guide our mission to transform lives through accessible, world-class business education. Our leadership team brings decades of experience from top universities, Fortune 500 companies, and global education institutions.');
        $this->migrator->add('leadership_hero.background_image', 'https://images.pexels.com/photos/3183197/pexels-photo-3183197.jpeg?auto=compress&cs=tinysrgb&w=1920');
        $this->migrator->add('leadership_hero.background_image_asset_id', null);

        // ─── Leaders ───
        $this->migrator->add('leadership_leaders.label', 'LEADERSHIP');
        $this->migrator->add('leadership_leaders.heading', 'Executive ');
        $this->migrator->add('leadership_leaders.heading_italic', 'Leadership Team');
        $this->migrator->add('leadership_leaders.subheading', 'The visionary leaders driving our mission to transform lives through education.');
        $this->migrator->add('leadership_leaders.items', [
            ['name' => 'Dr. Elizabeth Chen', 'designation' => 'Chief Academic Officer', 'bio' => 'Former Dean at London School of Economics with 25+ years in higher education. Dr. Chen oversees all academic programmes and faculty development, ensuring world-class educational standards.', 'image_url' => 'https://images.pexels.com/photos/774909/pexels-photo-774909.jpeg?auto=compress&cs=tinysrgb&w=800', 'linkedin_url' => '#'],
            ['name' => "Michael O'Brien", 'designation' => 'Chief Operating Officer', 'bio' => 'Previously led operations at Pearson Education for 15 years. Michael ensures seamless delivery of our programmes across all global touchpoints and drives operational excellence.', 'image_url' => 'https://images.pexels.com/photos/220453/pexels-photo-220453.jpeg?auto=compress&cs=tinysrgb&w=800', 'linkedin_url' => '#'],
            ['name' => 'Amara Okonkwo', 'designation' => 'Chief Strategy Officer', 'bio' => "Former McKinsey partner and Harvard MBA. Amara leads our strategic initiatives, global expansion efforts, and corporate partnerships that shape Maverick's future.", 'image_url' => 'https://images.pexels.com/photos/733872/pexels-photo-733872.jpeg?auto=compress&cs=tinysrgb&w=800', 'linkedin_url' => '#'],
            ['name' => 'Robert Williams', 'designation' => 'Chief Financial Officer', 'bio' => 'Chartered accountant with experience at KPMG and Deloitte. Robert oversees financial strategy, investor relations, and ensures sustainable growth for the academy.', 'image_url' => 'https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&w=800', 'linkedin_url' => '#'],
            ['name' => 'Dr. Sarah Mitchell', 'designation' => 'Chief Digital Officer', 'bio' => 'Tech visionary and former Google executive. Sarah drives our digital transformation, online learning platform development, and AI-powered educational innovations.', 'image_url' => 'https://images.pexels.com/photos/3760263/pexels-photo-3760263.jpeg?auto=compress&cs=tinysrgb&w=800', 'linkedin_url' => '#'],
            ['name' => 'David Oyelaran', 'designation' => 'Chief Marketing Officer', 'bio' => "Brand strategist with 20+ years building global education brands. David leads our marketing, communications, and student recruitment efforts worldwide.", 'image_url' => 'https://images.pexels.com/photos/1681010/pexels-photo-1681010.jpeg?auto=compress&cs=tinysrgb&w=800', 'linkedin_url' => '#'],
        ]);

        // ─── SEO ───
        $this->migrator->add('leadership_seo.meta_title', 'Leadership & Board - Maverick Business Academy');
        $this->migrator->add('leadership_seo.meta_description', 'Meet the distinguished leaders and board members guiding Maverick Business Academy.');
        $this->migrator->add('leadership_seo.meta_keywords', null);
        $this->migrator->add('leadership_seo.canonical_url', null);
        $this->migrator->add('leadership_seo.robots', 'index, follow');
        $this->migrator->add('leadership_seo.og_title', null);
        $this->migrator->add('leadership_seo.og_description', null);
        $this->migrator->add('leadership_seo.og_image_url', null);
        $this->migrator->add('leadership_seo.og_type', 'website');
        $this->migrator->add('leadership_seo.twitter_card', 'summary_large_image');
        $this->migrator->add('leadership_seo.twitter_title', null);
        $this->migrator->add('leadership_seo.twitter_description', null);
        $this->migrator->add('leadership_seo.twitter_image_url', null);
        $this->migrator->add('leadership_seo.schema_json', null);
        $this->migrator->add('leadership_seo.custom_head_scripts', null);
        $this->migrator->add('leadership_seo.custom_body_scripts', null);
    }
};
