<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class SeedHomepageSettings extends SettingsMigration
{
    public function up(): void
    {
        // Hero
        $this->migrator->add('hero.eyebrow', 'Globally Recognized Education 2');
        $this->migrator->add('hero.headline_line1', 'Transforming');
        $this->migrator->add('hero.headline_line2', 'Learners Into');
        $this->migrator->add('hero.headline_line3', 'Global Leaders');
        $this->migrator->add('hero.subheading', 'Globally recognized qualifications, international pathways, and career-focused learning designed for the leaders of tomorrow.');
        $this->migrator->add('hero.cta_primary_text', 'Explore Programs');
        $this->migrator->add('hero.cta_primary_url', '/programs/');
        $this->migrator->add('hero.cta_secondary_text', 'Speak with an Advisor');
        $this->migrator->add('hero.cta_secondary_url', '/contact');
        $this->migrator->add('hero.video_url', 'https://res.cloudinary.com/i08gwudw/video/upload/v1783604725/maverick-hero_sxhbex.webm');

        // Numbers
        $this->migrator->add('numbers.heading_line1', 'Where Education');
        $this->migrator->add('numbers.heading_line2', 'Meets Real-World');
        $this->migrator->add('numbers.heading_line3', 'Growth.');
        $this->migrator->add('numbers.context_text', 'Maverick has empowered people across different countries and industries, regardless of where they are in their career.');
        $this->migrator->add('numbers.context_link_text', 'Our Story');
        $this->migrator->add('numbers.context_link_url', '/about-us/our-story');
        $this->migrator->add('numbers.stat1_value', '9000');
        $this->migrator->add('numbers.stat1_label', 'Learners Empowered');
        $this->migrator->add('numbers.stat2_value', '90');
        $this->migrator->add('numbers.stat2_label', 'Nationalities');
        $this->migrator->add('numbers.stat3_value', '20');
        $this->migrator->add('numbers.stat3_label', 'Academic Partners');
        $this->migrator->add('numbers.stat4_value', '80');
        $this->migrator->add('numbers.stat4_label', 'Corporate Trainings');
        $this->migrator->add('numbers.stat5_value', '12');
        $this->migrator->add('numbers.stat5_label', 'Countries Represented');
        $this->migrator->add('numbers.stat6_value', '4500');
        $this->migrator->add('numbers.stat6_label', 'Graduates');

        // Who We Are
        $this->migrator->add('who_we_are.heading_line1', 'We Are');
        $this->migrator->add('who_we_are.heading_line2', 'Transformational.');
        $this->migrator->add('who_we_are.body_text', 'At Maverick Business Academy, we believe education should be practical, flexible, and globally relevant.');
        $this->migrator->add('who_we_are.stat1_value', '15+');
        $this->migrator->add('who_we_are.stat1_suffix', 'Years');
        $this->migrator->add('who_we_are.stat1_label', 'of Excellence');
        $this->migrator->add('who_we_are.stat2_value', '50+');
        $this->migrator->add('who_we_are.stat2_suffix', 'Countries');
        $this->migrator->add('who_we_are.stat2_label', 'Reached');
        $this->migrator->add('who_we_are.cta_text', 'Discover Our Story');
        $this->migrator->add('who_we_are.cta_url', '/about-us/our-story');
        $this->migrator->add('who_we_are.image_url', 'assets/images/homepage/mavrick-transform.png');

        // CEO
        $this->migrator->add('ceo.name', 'Dr. Shaik Fazil Saleem');
        $this->migrator->add('ceo.designation', 'Dean, Group CEO & Founder');
        $this->migrator->add('ceo.quote', 'We do not simply deliver qualifications. We create opportunities that transform lives, careers, and communities.');
        $this->migrator->add('ceo.body_paragraph1', 'Maverick Business Academy was founded with a vision to make internationally recognised education more accessible, practical, and transformational for learners around the world.');
        $this->migrator->add('ceo.body_paragraph2', 'Our mission goes beyond academic achievement. We focus on empowering individuals with the knowledge, confidence, and opportunities required to succeed in a rapidly changing global environment.');
        $this->migrator->add('ceo.image_url', 'https://res.cloudinary.com/i08gwudw/image/upload/v1783478429/ASD01131_v3opum.jpg');
        $this->migrator->add('ceo.badge_text', 'Dean, Group CEO & Founder');

        // What Is Maverick
        $this->migrator->add('what_is_maverick.heading', 'Education That Creates Opportunities');
        $this->migrator->add('what_is_maverick.statement1', '50+ Masterclasses & Webinars Conducted');
        $this->migrator->add('what_is_maverick.statement2', '5,000+ Certificates Awarded');
        $this->migrator->add('what_is_maverick.statement3', '80+ Corporate Trainings');
        $this->migrator->add('what_is_maverick.statement4', '100+ Industry Topics Covered');
        $this->migrator->add('what_is_maverick.statement5', '10+ Years of Excellence');
        $this->migrator->add('what_is_maverick.final_text', 'Real transformation. Real impact.');

        // Final CTA
        $this->migrator->add('final_cta.heading', 'Ready To Shape Your Future?');
        $this->migrator->add('final_cta.subtitle', 'Join a global community of learners, professionals, and leaders.');
        $this->migrator->add('final_cta.btn_primary_text', 'Apply Now');
        $this->migrator->add('final_cta.btn_primary_url', '/apply/');
        $this->migrator->add('final_cta.btn_secondary_text', 'Schedule A Consultation');
        $this->migrator->add('final_cta.btn_secondary_url', '/contact');
        $this->migrator->add('final_cta.phone_text', 'Or speak with an advisor:');
        $this->migrator->add('final_cta.phone_number', '+971 50 144 1670');
    }
}