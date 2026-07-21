<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class SeedMissingHomepageSettings extends SettingsMigration
{
    public function up(): void
    {
        // What We Do
        $this->migrator->add('what_we_do.heading_line1', 'Three Pillars of');
        $this->migrator->add('what_we_do.heading_line2', 'Global Education');
        $this->migrator->add('what_we_do.context_text', 'We deliver education through three connected pathways: academic qualifications, professional development, and international opportunities.');

        $this->migrator->add('what_we_do.pillar1_title', 'Academic Qualifications');
        $this->migrator->add('what_we_do.pillar1_desc', 'Internationally recognized degrees at every level.');
        $this->migrator->add('what_we_do.pillar1_item1', "Bachelor's Degrees");
        $this->migrator->add('what_we_do.pillar1_item2', "Master's Degrees");
        $this->migrator->add('what_we_do.pillar1_item3', 'Doctorate Degrees');
        $this->migrator->add('what_we_do.pillar1_cta_text', 'Explore Programs');
        $this->migrator->add('what_we_do.pillar1_cta_url', '/programs/academic/');

        $this->migrator->add('what_we_do.pillar2_title', 'Professional Development');
        $this->migrator->add('what_we_do.pillar2_desc', 'Career-focused training designed for working leaders.');
        $this->migrator->add('what_we_do.pillar2_item1', 'Executive Education');
        $this->migrator->add('what_we_do.pillar2_item2', 'Corporate Training');
        $this->migrator->add('what_we_do.pillar2_item3', 'Leadership Programs');
        $this->migrator->add('what_we_do.pillar2_cta_text', 'View Programs');
        $this->migrator->add('what_we_do.pillar2_cta_url', '/programs/professional/');

        $this->migrator->add('what_we_do.pillar3_title', 'International Opportunities');
        $this->migrator->add('what_we_do.pillar3_desc', 'Global experiences that broaden horizons.');
        $this->migrator->add('what_we_do.pillar3_item1', 'Study Abroad');
        $this->migrator->add('what_we_do.pillar3_item2', 'Student Exchange');
        $this->migrator->add('what_we_do.pillar3_item3', 'Global Bachelor Pathways');
        $this->migrator->add('what_we_do.pillar3_item4', 'Internships');
        $this->migrator->add('what_we_do.pillar3_cta_text', 'Discover Opportunities');
        $this->migrator->add('what_we_do.pillar3_cta_url', '/programs/international/');

        // How We Do It
        $this->migrator->add('how_we_do_it.heading_line1', 'A Proven');
        $this->migrator->add('how_we_do_it.heading_line2', 'Three-Step Approach');
        $this->migrator->add('how_we_do_it.subtitle', 'Every Maverick journey follows a structured path designed to maximize learning, connection, and transformation.');

        $this->migrator->add('how_we_do_it.step1_title', 'Learn');
        $this->migrator->add('how_we_do_it.step1_subtitle', 'Industry-Relevant Curriculum');
        $this->migrator->add('how_we_do_it.step1_desc', 'Programs designed with input from global industry leaders, ensuring every lesson translates to real-world application.');

        $this->migrator->add('how_we_do_it.step2_title', 'Connect');
        $this->migrator->add('how_we_do_it.step2_subtitle', 'Global Faculty & Partnerships');
        $this->migrator->add('how_we_do_it.step2_desc', 'Access to international faculty, university partnerships, and a worldwide network of fellow learners and alumni.');

        $this->migrator->add('how_we_do_it.step3_title', 'Transform');
        $this->migrator->add('how_we_do_it.step3_subtitle', 'Career Growth & Leadership');
        $this->migrator->add('how_we_do_it.step3_desc', 'Graduate with the skills, network, and credentials to advance your career and lead with confidence.');

        // Why Maverick
        $this->migrator->add('why_maverick.heading_line1', 'Why Thousands');
        $this->migrator->add('why_maverick.heading_line2', 'Choose Maverick');
        $this->migrator->add('why_maverick.subtitle', 'Six reasons our graduates lead in their industries across the globe');

        $this->migrator->add('why_maverick.tile1_title', 'International Qualifications');
        $this->migrator->add('why_maverick.tile1_desc', 'Globally recognised degrees accredited by world-leading institutions, opening doors across 30+ countries.');
        $this->migrator->add('why_maverick.tile2_title', 'Global University Network');
        $this->migrator->add('why_maverick.tile2_desc', '');
        $this->migrator->add('why_maverick.tile3_title', 'Flexible Learning');
        $this->migrator->add('why_maverick.tile3_desc', '');
        $this->migrator->add('why_maverick.tile4_title', 'Career Advancement');
        $this->migrator->add('why_maverick.tile4_desc', '');
        $this->migrator->add('why_maverick.tile5_title', 'Industry Engagement');
        $this->migrator->add('why_maverick.tile5_desc', '');
        $this->migrator->add('why_maverick.tile6_title', 'Academic Excellence');
        $this->migrator->add('why_maverick.tile6_desc', '');

        // Global Opportunities
        $this->migrator->add('global_opportunities.heading', 'Learning Without Borders');
        $this->migrator->add('global_opportunities.subtitle', 'From study-abroad semesters to dual-degree pathways — Maverick opens doors to global education.');

        $this->migrator->add('global_opportunities.left_title', 'Global Opportunities');
        $this->migrator->add('global_opportunities.opp1_title', 'Study Abroad');
        $this->migrator->add('global_opportunities.opp1_desc', 'Spend a semester at one of our 50+ partner universities across six continents.');
        $this->migrator->add('global_opportunities.opp1_url', '/opportunities/study-abroad/');
        $this->migrator->add('global_opportunities.opp2_title', 'Student Exchange');
        $this->migrator->add('global_opportunities.opp2_desc', 'Swap places with international students and live the global classroom experience.');
        $this->migrator->add('global_opportunities.opp2_url', '/opportunities/student-exchange/');
        $this->migrator->add('global_opportunities.opp3_title', 'Internships');
        $this->migrator->add('global_opportunities.opp3_desc', 'Gain real-world experience with Fortune 500 partners and high-growth startups.');
        $this->migrator->add('global_opportunities.opp3_url', '/opportunities/internships/');
        $this->migrator->add('global_opportunities.opp4_title', 'Hungary Programs');
        $this->migrator->add('global_opportunities.opp4_desc', 'Specialised European exposure through our exclusive Hungary partnership track.');
        $this->migrator->add('global_opportunities.opp4_url', '/opportunities/hungary-programs/');

        $this->migrator->add('global_opportunities.right_title', 'Global Pathways');
        $this->migrator->add('global_opportunities.path1_title', 'Global Bachelor Pathways');
        $this->migrator->add('global_opportunities.path1_desc', 'Transfer credits seamlessly between Maverick and our global partner institutions.');
        $this->migrator->add('global_opportunities.path1_url', '/pathways/credit-transfer/');
        $this->migrator->add('global_opportunities.path2_title', 'Dual Degrees');
        $this->migrator->add('global_opportunities.path2_desc', 'Graduate with two recognised degrees from two world-class institutions.');
        $this->migrator->add('global_opportunities.path2_url', '/pathways/dual-degrees/');
        $this->migrator->add('global_opportunities.path3_title', "Master's Pathways");
        $this->migrator->add('global_opportunities.path3_desc', 'Progress from undergraduate to postgraduate with guaranteed admission tracks.');
        $this->migrator->add('global_opportunities.path3_url', '/pathways/masters-pathways/');
        $this->migrator->add('global_opportunities.path4_title', 'Doctoral Pathways');
        $this->migrator->add('global_opportunities.path4_desc', 'Continue your research journey with structured PhD and DBA progression routes.');
        $this->migrator->add('global_opportunities.path4_url', '/pathways/doctoral-pathways/');
    }
}