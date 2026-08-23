<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('global_partners_hero.tag', 'GLOBAL PARTNERSHIPS');
        $this->migrator->add('global_partners_hero.heading_line1', 'Global');
        $this->migrator->add('global_partners_hero.heading_italic', 'University Partners');
        $this->migrator->add('global_partners_hero.description', 'Maverick Business Academy collaborates with internationally recognized universities and educational institutions across five continents, creating academic bridges that connect ambitious learners with globally respected qualifications, cutting-edge research opportunities, and transformative career pathways that transcend geographical boundaries.');
        $this->migrator->add('global_partners_hero.background_image', 'https://images.pexels.com/photos/5725589/pexels-photo-5725589.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=1600');
        $this->migrator->add('global_partners_hero.background_image_asset_id', null);

        $this->migrator->add('global_partners_overview.tag', 'GLOBAL PARTNERSHIPS');
        $this->migrator->add('global_partners_overview.heading', 'Building Global Pathways Through ');
        $this->migrator->add('global_partners_overview.heading_italic', 'Strategic Academic Partnerships');
        $this->migrator->add('global_partners_overview.paragraph', 'Maverick Business Academy collaborates with internationally recognized universities and educational institutions to provide learners with access to globally respected qualifications, flexible learning opportunities, and career-focused academic pathways');
        $this->migrator->add('global_partners_overview.image', 'https://res.cloudinary.com/i08gwudw/image/upload/v1785340343/maverick-academy/our-story/timeline/qpg8khpl9f0tg6xzyhz5.jpg');
        $this->migrator->add('global_partners_overview.image_asset_id', null);

        $this->migrator->add('global_partners_cards.label', 'PARTNER UNIVERSITIES');
        $this->migrator->add('global_partners_cards.heading', 'Our Global ');
        $this->migrator->add('global_partners_cards.heading_italic', 'Academic Network');
        $this->migrator->add('global_partners_cards.subheading', 'Maverick Business Academy partners with world-class universities across Europe and beyond, offering students internationally recognised pathways to academic and career success.');

        $this->migrator->add('global_partners_why.tag', 'OUR VALUE');
        $this->migrator->add('global_partners_why.heading', 'Why Our Partnerships');
        $this->migrator->add('global_partners_why.heading_italic', 'Matter');
        $this->migrator->add('global_partners_why.quote', 'Every partnership we sign is measured against one question — does it open a new door for our students?');
        $this->migrator->add('global_partners_why.items', [
            ['icon' => 'graduation-cap', 'title' => 'Internationally Recognized Qualifications', 'description' => 'Access degrees and certifications respected by employers and institutions worldwide.'],
            ['icon' => 'globe', 'title' => 'Global Learning Opportunities', 'description' => 'Study across borders through partner campuses and international exchange programs.'],
            ['icon' => 'book-open', 'title' => 'Flexible Study Pathways', 'description' => 'Choose from full-time, part-time, online, and hybrid learning formats.'],
            ['icon' => 'award', 'title' => 'Academic Excellence', 'description' => 'Learn from world-class faculty and follow curricula aligned with global standards.'],
            ['icon' => 'rocket', 'title' => 'Career Advancement', 'description' => 'Unlock opportunities with qualifications that accelerate professional growth.'],
        ]);

        $this->migrator->add('global_partners_benefits.tag', 'Student Benefits');
        $this->migrator->add('global_partners_benefits.heading', 'Benefits of Studying Through ');
        $this->migrator->add('global_partners_benefits.heading_italic', 'Maverick Partnerships');
        $this->migrator->add('global_partners_benefits.main_image', 'https://res.cloudinary.com/i08gwudw/image/upload/v1784534077/maverick-academy/programs/igxpmziapl3v5xaqozki.jpg');
        $this->migrator->add('global_partners_benefits.main_image_asset_id', null);
        $this->migrator->add('global_partners_benefits.secondary_image', 'https://res.cloudinary.com/i08gwudw/image/upload/v1784441348/mba_sa4pmo.jpg');
        $this->migrator->add('global_partners_benefits.secondary_image_asset_id', null);
        $this->migrator->add('global_partners_benefits.stat_number', '30k+');
        $this->migrator->add('global_partners_benefits.stat_label', 'GLOBAL ALUMNI');
        $this->migrator->add('global_partners_benefits.items', [
            ['title' => 'Access to internationally recognized qualifications', 'description' => 'Degrees and certifications valued by employers across the globe.', 'highlighted' => false],
            ['title' => 'Flexible Learning Formats', 'description' => 'Full-time, part-time, online and hybrid — learning that fits your life.', 'highlighted' => false],
            ['title' => 'Global Alumni Networks', 'description' => 'Join 30,000+ graduates building careers in over 50 countries.', 'highlighted' => true],
            ['title' => 'Industry-Relevant Curriculum', 'description' => 'Programmes shaped with employers and updated for the real world of work.', 'highlighted' => false],
            ['title' => 'Career Progression Opportunities', 'description' => 'Qualifications designed to accelerate your professional growth.', 'highlighted' => false],
            ['title' => 'Diverse International Learning Environment', 'description' => 'Learn alongside peers from every corner of the world.', 'highlighted' => false],
        ]);

        $this->migrator->add('global_partners_journey.label', 'MOMENTS');
        $this->migrator->add('global_partners_journey.heading', 'Our Partnership ');
        $this->migrator->add('global_partners_journey.heading_italic', 'Journey');
        $this->migrator->add('global_partners_journey.subheading', 'A visual journey through our global collaborations, milestones, and academic partnerships.');

        $this->migrator->add('global_partners_seo.meta_title', 'Global University Partners - Maverick Business Academy');
        $this->migrator->add('global_partners_seo.meta_description', 'Explore Maverick Business Academy\'s global university partnerships and academic pathways across five continents.');
        $this->migrator->add('global_partners_seo.meta_keywords', null);
        $this->migrator->add('global_partners_seo.canonical_url', null);
        $this->migrator->add('global_partners_seo.robots', 'index, follow');
        $this->migrator->add('global_partners_seo.og_title', null);
        $this->migrator->add('global_partners_seo.og_description', null);
        $this->migrator->add('global_partners_seo.og_image_url', null);
        $this->migrator->add('global_partners_seo.og_type', 'website');
        $this->migrator->add('global_partners_seo.twitter_card', 'summary_large_image');
        $this->migrator->add('global_partners_seo.twitter_title', null);
        $this->migrator->add('global_partners_seo.twitter_description', null);
        $this->migrator->add('global_partners_seo.twitter_image_url', null);
        $this->migrator->add('global_partners_seo.schema_json', null);
        $this->migrator->add('global_partners_seo.custom_head_scripts', null);
        $this->migrator->add('global_partners_seo.custom_body_scripts', null);
    }
};
