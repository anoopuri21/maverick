<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('dual_mba_hero.tag', 'Dual MBA Programme');
        $this->migrator->add('dual_mba_hero.headline_line1', 'Earn Two MBA Degrees.');
        $this->migrator->add('dual_mba_hero.headline_line2', 'Expand Your Expertise.');
        $this->migrator->add('dual_mba_hero.headline_italic', 'Accelerate Your Global Career.');
        $this->migrator->add('dual_mba_hero.sub', '<p>One Programme. Two International MBA Qualifications. Unlimited Career Opportunities.</p>');
        $this->migrator->add('dual_mba_hero.background_image', 'https://images.unsplash.com/photo-1630344745884-9c93c4593f70?w=1920&q=80');
        $this->migrator->add('dual_mba_hero.background_image_asset_id', null);
        $this->migrator->add('dual_mba_hero.visual_image', 'https://images.unsplash.com/photo-1763038311036-6d18805537e5?w=600&q=80');
        $this->migrator->add('dual_mba_hero.visual_image_asset_id', null);
        $this->migrator->add('dual_mba_hero.badge_title', '2x MBA');
        $this->migrator->add('dual_mba_hero.badge_sub', 'One Programme');
        $this->migrator->add('dual_mba_hero.stats', [
            ['value' => '1 Year', 'label' => 'Duration'],
            ['value' => '100%', 'label' => 'Online'],
            ['value' => 'Weekend', 'label' => 'Classes'],
            ['value' => '2', 'label' => 'MBA Degrees'],
        ]);
        $this->migrator->add('dual_mba_hero.ctas', [
            ['label' => 'Apply Now', 'url' => '/apply/', 'style' => 'primary'],
            ['label' => 'Download Brochure', 'url' => '#dmba-overview', 'style' => 'secondary'],
        ]);

        $this->migrator->add('dual_mba_overview.label', 'Programme Overview');
        $this->migrator->add('dual_mba_overview.heading', 'One Programme.');
        $this->migrator->add('dual_mba_overview.heading_highlight', 'Two Degrees.');
        $this->migrator->add('dual_mba_overview.description', '<p>Instead of choosing between broad business knowledge and specialist expertise, you graduate with both — giving you a significant competitive advantage in today\'s global employment market.</p>');
        $this->migrator->add('dual_mba_overview.highlights_heading', 'Programme Highlights');
        $this->migrator->add('dual_mba_overview.highlights_line', 'Two MBA Degrees . One Journey . One Affordable Investment');
        $this->migrator->add('dual_mba_overview.cards', [
            ['icon_key' => 'graduation-cap', 'icon_tone' => 'blue', 'title' => 'MBA General', 'text' => 'A comprehensive foundation in strategic leadership, finance, marketing and operations.'],
            ['icon_key' => 'grid-4', 'icon_tone' => 'red', 'title' => 'MBA with 15+ Specialisations', 'text' => 'Choose from 15+ industry-focused specialisations aligned to your career goals.'],
            ['icon_key' => 'globe', 'icon_tone' => 'blue', 'title' => 'Recognised in UAE + Globally', 'text' => 'Internationally recognised qualifications trusted by employers worldwide, including the UAE.'],
            ['icon_key' => 'medal', 'icon_tone' => 'red', 'title' => 'Triple Qualification', 'text' => 'Awarded by three internationally recognised universities — GAU, RBS & UCA.'],
            ['icon_key' => 'layers', 'icon_tone' => 'blue', 'title' => 'Two MBA Degrees in One Journey', 'text' => 'Two qualifications from one integrated programme — without doubling your time.'],
            ['icon_key' => 'dollar-circle', 'icon_tone' => 'red', 'title' => 'One Affordable Investment', 'text' => 'Premium-calibre education with flexible payment options and scholarship support.'],
        ]);

        $this->migrator->add('dual_mba_twice.slides', [
            [
                'image' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=1600&q=80',
                'image_asset_id' => null,
                'label' => 'The Dual Advantage',
                'title' => 'Twice the',
                'title_italic' => 'Value',
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=1600&q=80',
                'image_asset_id' => null,
                'label' => null,
                'title' => 'Twice the',
                'title_italic' => 'Opportunity',
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1521791136064-7986c2920216?w=1600&q=80',
                'image_asset_id' => null,
                'label' => null,
                'title' => 'Twice the',
                'title_italic' => 'Recognition',
            ],
        ]);

        $this->migrator->add('dual_mba_why.label', 'Why Choose');
        $this->migrator->add('dual_mba_why.title', 'Why Choose the');
        $this->migrator->add('dual_mba_why.title_highlight', 'Dual MBA Programme?');
        $this->migrator->add('dual_mba_why.cards', [
            ['icon_key' => 'star', 'title' => 'Build Leadership That Drives Results', 'description' => 'Develop the confidence and strategic thinking required to lead teams, manage change, and influence organisational growth.'],
            ['icon_key' => 'graduation-cap', 'title' => 'Two MBA Qualifications', 'description' => 'Earn both a General MBA and a Specialised MBA through one integrated programme, enhancing your profile and credibility.'],
            ['icon_key' => 'monitor', 'title' => 'Designed for Working Professionals', 'description' => 'Study while continuing your career through flexible 100% online learning and weekend classes.'],
            ['icon_key' => 'book', 'title' => 'Industry-Relevant Curriculum', 'description' => 'Learn practical concepts that can be immediately applied within your workplace and professional environment.'],
            ['icon_key' => 'cpu', 'title' => 'Future-Focused Specialisations', 'description' => 'Develop expertise in rapidly growing fields including AI, Business Analytics, Healthcare, IT, Finance, and Human Resources.'],
            ['icon_key' => 'globe', 'title' => 'Global Career Opportunities', 'description' => 'Prepare for leadership positions across multinational corporations, government organisations, startups, and consulting firms.'],
            ['icon_key' => 'dollar', 'title' => 'Affordable Investment', 'description' => 'Complete two internationally recognised MBA qualifications with flexible payment options and scholarship opportunities.'],
        ]);

        $this->migrator->add('dual_mba_specs.label', 'Specialisations');
        $this->migrator->add('dual_mba_specs.title', 'Choose Your');
        $this->migrator->add('dual_mba_specs.title_highlight', 'Specialisation');
        $this->migrator->add('dual_mba_specs.title_break', true);
        $this->migrator->add('dual_mba_specs.intro', '<p>Gain advanced expertise in a specific business discipline aligned with your career goals.</p>');
        $this->migrator->add('dual_mba_specs.cards', [
            ['icon_key' => 'cpu', 'title' => 'Artificial Intelligence', 'tag' => 'MBA Specialisation'],
            ['icon_key' => 'bar-chart', 'title' => 'Finance', 'tag' => 'MBA Specialisation'],
            ['icon_key' => 'users', 'title' => 'Human Resource Management', 'tag' => 'MBA Specialisation'],
            ['icon_key' => 'truck', 'title' => 'Supply Chain Management', 'tag' => 'MBA Specialisation'],
            ['icon_key' => 'clipboard', 'title' => 'Project Management', 'tag' => 'MBA Specialisation'],
            ['icon_key' => 'monitor', 'title' => 'Information Technology', 'tag' => 'MBA Specialisation'],
            ['icon_key' => 'pulse', 'title' => 'Healthcare Management', 'tag' => 'MBA Specialisation'],
            ['icon_key' => 'hexagon', 'title' => 'Business Analytics', 'tag' => 'MBA Specialisation'],
        ]);

        $this->migrator->add('dual_mba_employers.collage', [
            ['image' => 'assets/images/01.png', 'image_asset_id' => null, 'alt' => 'Business professionals in a strategic leadership meeting', 'role' => 'lead'],
            ['image' => 'assets/images/02.png', 'image_asset_id' => null, 'alt' => 'Cross-functional team collaborating across departments', 'role' => 'team'],
            ['image' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=800&q=80', 'image_asset_id' => null, 'alt' => 'Professional developing her career and leadership skills', 'role' => 'growth'],
        ]);
        $this->migrator->add('dual_mba_employers.counter_label', 'Key Competencies<br>Employers Seek');
        $this->migrator->add('dual_mba_employers.label', 'Employer Value');
        $this->migrator->add('dual_mba_employers.heading', 'Why Employers Value a');
        $this->migrator->add('dual_mba_employers.heading_italic', 'Dual MBA');
        $this->migrator->add('dual_mba_employers.description', '<p>Today\'s employers increasingly seek professionals who combine strategic leadership with specialised expertise. Graduating with two MBA qualifications demonstrates:</p>');
        $this->migrator->add('dual_mba_employers.items', [
            'Broader business understanding',
            'Advanced industry knowledge',
            'Strong leadership capability',
            'Analytical thinking',
            'Strategic decision-making',
            'Cross-functional management',
            'Adaptability in changing industries',
            'Professional development commitment',
        ]);

        $this->migrator->add('dual_mba_testimonials.label', 'Success Stories');
        $this->migrator->add('dual_mba_testimonials.title', 'What Our');
        $this->migrator->add('dual_mba_testimonials.title_italic', 'Graduates Say');
        $this->migrator->add('dual_mba_testimonials.items', [
            [
                'quote' => 'The Dual MBA programme gave me both the strategic breadth and the AI specialisation I needed to transition into a tech leadership role. The flexible format was perfect for my schedule.',
                'name' => 'James M.',
                'role' => 'Tech Director, London',
                'programme' => 'Dual MBA — AI Specialisation',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop',
                'avatar_asset_id' => null,
            ],
            [
                'quote' => 'Having two MBA qualifications on my CV opened doors I never expected. I was promoted within 6 months of graduating. The programme is truly world-class.',
                'name' => 'Priya S.',
                'role' => 'VP Finance, Dubai',
                'programme' => 'Dual MBA — Finance',
                'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=100&h=100&fit=crop',
                'avatar_asset_id' => null,
            ],
            [
                'quote' => 'As an entrepreneur, the General MBA gave me strategy and the Healthcare specialisation gave me domain expertise. This combination helped me launch my healthcare startup.',
                'name' => 'Ahmed K.',
                'role' => 'Founder & CEO, Riyadh',
                'programme' => 'Dual MBA — Healthcare',
                'avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&h=100&fit=crop',
                'avatar_asset_id' => null,
            ],
            [
                'quote' => 'The weekend class format allowed me to continue my career while studying. I gained deep knowledge in HR management alongside a solid business foundation.',
                'name' => 'Sarah L.',
                'role' => 'HR Director, Singapore',
                'programme' => 'Dual MBA — HR Management',
                'avatar' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=100&h=100&fit=crop',
                'avatar_asset_id' => null,
            ],
            [
                'quote' => 'The Dual MBA was the best investment in my career. The international recognition of the qualifications allowed me to move into a senior role in a multinational firm.',
                'name' => 'David R.',
                'role' => 'Senior Manager, New York',
                'programme' => 'Dual MBA — Project Management',
                'avatar' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=100&h=100&fit=crop',
                'avatar_asset_id' => null,
            ],
        ]);

        $this->migrator->add('dual_mba_process.label', 'How It Works');
        $this->migrator->add('dual_mba_process.title', 'Your Path to a Dual MBA');
        $this->migrator->add('dual_mba_process.steps', [
            ['title' => 'Submit Application', 'description' => 'Complete the online application form with your academic and professional details.'],
            ['title' => 'Review & Admission', 'description' => 'Our admissions team reviews your profile and responds within 48 hours.'],
            ['title' => 'Enrolment & Onboarding', 'description' => 'Secure your place, access learning materials, and meet your cohort.'],
            ['title' => 'Begin Your Journey', 'description' => 'Start classes and work towards your Dual MBA qualification.'],
        ]);

        $this->migrator->add('dual_mba_faq.label', 'FAQs');
        $this->migrator->add('dual_mba_faq.title', 'Frequently Asked Questions');
        $this->migrator->add('dual_mba_faq.items', [
            ['question' => 'What is the Dual MBA Programme?', 'answer' => '<p>The Dual MBA Programme allows you to earn two internationally recognised MBA qualifications — a General MBA and a Specialised MBA — through one integrated learning pathway in just 1 year. You gain both broad business knowledge and specialist expertise.</p>'],
            ['question' => 'Who is this programme designed for?', 'answer' => '<p>The programme is designed for ambitious professionals, entrepreneurs, managers, and future executives seeking to advance their careers with internationally recognised qualifications while continuing to work.</p>'],
            ['question' => 'How long does the programme take to complete?', 'answer' => '<p>The Dual MBA Programme is designed to be completed in 1 year, with 100% online delivery and weekend classes to accommodate working professionals.</p>'],
            ['question' => 'Can I study while working full-time?', 'answer' => '<p>Absolutely. The programme is specifically designed for working professionals, with 100% online delivery and classes scheduled on weekends so you can continue your career without interruption.</p>'],
            ['question' => 'What specialisations are available?', 'answer' => '<p>Specialisations include Artificial Intelligence, Finance, Human Resource Management, Supply Chain Management, Project Management, Information Technology, Healthcare Management, and Business Analytics.</p>'],
            ['question' => 'Are the degrees internationally recognised?', 'answer' => '<p>Yes. The Dual MBA is awarded by Girne American University (GAU), Rushford Business School (RBS), and the University for the Creative Arts — all internationally recognised institutions.</p>'],
            ['question' => 'Are scholarships available?', 'answer' => '<p>Yes. Maverick Business Academy London offers flexible payment options and scholarship opportunities. Contact our admissions team to learn more about available financial support.</p>'],
        ]);

        $this->migrator->add('dual_mba_final_cta.heading', 'Your Future Starts Here.');
        $this->migrator->add('dual_mba_final_cta.heading_line2', 'Apply for the Dual MBA Programme Today.');
        $this->migrator->add('dual_mba_final_cta.sub', '<p>Two internationally recognised MBA qualifications. One integrated programme. Unlimited career potential.</p>');
        $this->migrator->add('dual_mba_final_cta.background_image', 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=1920&q=80');
        $this->migrator->add('dual_mba_final_cta.background_image_asset_id', null);
        $this->migrator->add('dual_mba_final_cta.ctas', [
            ['label' => 'Apply Now', 'url' => '/apply/', 'style' => 'primary'],
            ['label' => 'Book a Free Consultation', 'url' => '/contact', 'style' => 'secondary'],
        ]);
        $this->migrator->add('dual_mba_final_cta.brochure_label', 'Download Programme Brochure');
        $this->migrator->add('dual_mba_final_cta.brochure_url', '#');

        $this->migrator->add('dual_mba_seo.meta_title', 'Dual MBA Programme | Maverick Business Academy London');
        $this->migrator->add('dual_mba_seo.meta_description', 'Earn Two MBA Degrees in One Year. General MBA + Specialised MBA through one integrated programme. 100% Online, Weekend Classes. Apply Now.');
        $this->migrator->add('dual_mba_seo.meta_keywords', null);
        $this->migrator->add('dual_mba_seo.canonical_url', null);
        $this->migrator->add('dual_mba_seo.robots', 'index, follow');
        $this->migrator->add('dual_mba_seo.og_title', null);
        $this->migrator->add('dual_mba_seo.og_description', null);
        $this->migrator->add('dual_mba_seo.og_image_url', null);
        $this->migrator->add('dual_mba_seo.og_image_url_asset_id', null);
        $this->migrator->add('dual_mba_seo.og_type', 'website');
        $this->migrator->add('dual_mba_seo.twitter_card', 'summary_large_image');
        $this->migrator->add('dual_mba_seo.twitter_title', null);
        $this->migrator->add('dual_mba_seo.twitter_description', null);
        $this->migrator->add('dual_mba_seo.twitter_image_url', null);
        $this->migrator->add('dual_mba_seo.twitter_image_url_asset_id', null);
        $this->migrator->add('dual_mba_seo.schema_json', null);
        $this->migrator->add('dual_mba_seo.custom_head_scripts', null);
        $this->migrator->add('dual_mba_seo.custom_body_scripts', null);
    }
};
