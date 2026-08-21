<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('gbp_hero.tag', "GLOBAL BACHELOR'S PATHWAY");
        $this->migrator->add('gbp_hero.heading', "Global Bachelor's");
        $this->migrator->add('gbp_hero.heading_italic', 'Pathway Programme');
        $this->migrator->add('gbp_hero.sub', "<p>Your gateway to a globally recognised European Bachelor's degree — structured pathways, flexible learning, and international progression through Maverick Business Academy London.</p>");
        $this->migrator->add('gbp_hero.background_image', 'https://images.pexels.com/photos/1462630/pexels-photo-1462630.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=1600');
        $this->migrator->add('gbp_hero.background_image_asset_id', null);

        $this->migrator->add('gbp_snapshot.cards', [
            ['icon_key' => 'map', 'title' => 'Study Route', 'items' => ['UAE', 'Hybrid', 'Online', 'European University Progression']],
            ['icon_key' => 'map-pin', 'title' => 'Destinations', 'items' => ['Hungary', 'Romania', 'Moldova']],
            ['icon_key' => 'target', 'title' => 'Focus International Pathways', 'items' => ['European Degree', 'Credit Transfer']],
        ]);
        $this->migrator->add('gbp_snapshot.ctas', [
            ['label' => 'Enquire Now', 'url' => '#enquire', 'style' => 'primary'],
            ['label' => 'Speak to an Advisor', 'url' => '#advisor', 'style' => 'ghost'],
        ]);

        $this->migrator->add('gbp_intro.tag', 'YOUR PATHWAY');
        $this->migrator->add('gbp_intro.heading_line1', 'Your Structured Route to a');
        $this->migrator->add('gbp_intro.heading_line2', 'Globally Recognised European');
        $this->migrator->add('gbp_intro.heading_italic', "Bachelor's Degree");
        $this->migrator->add('gbp_intro.paragraphs', [
            "<p>Begin your Bachelor's Degree Pathway in UAE with Maverick Business Academy London and progress towards an internationally recognised European Bachelor's degree through our partner university pathways in Hungary, Romania, and Moldova.</p>",
            "<p>Designed for students and parents seeking a smarter, affordable, and globally focused study route, the Maverick Bachelor's Global Pathway helps learners begin their academic journey with structured support and progress confidently towards international university completion, leading to an Affordable Bachelor's Degree in Europe.</p>",
        ]);
        $this->migrator->add('gbp_intro.highlights', [
            ['icon_key' => 'globe', 'label' => 'International Pathways', 'value' => 'Study in UAE, progress to Europe'],
            ['icon_key' => 'award', 'label' => 'Recognised Degree', 'value' => 'Globally accepted European qualification'],
            ['icon_key' => 'credit-card', 'label' => 'Cost Effective', 'value' => 'Affordable alternative to full overseas study'],
            ['icon_key' => 'users', 'label' => 'Full Support', 'value' => 'Visa guidance, career counselling, academic mentoring'],
        ]);

        $this->migrator->add('gbp_overview.tag', 'PROGRAMME OVERVIEW');
        $this->migrator->add('gbp_overview.heading', 'What is the Maverick');
        $this->migrator->add('gbp_overview.heading_italic', "Bachelor's Pathway Programme?");
        $this->migrator->add('gbp_overview.paragraphs', [
            "<p>The Maverick Bachelor's Pathway Programme is a structured academic route that allows students to begin their bachelor's journey with Maverick Business Academy London and progress to selected international partner universities in Europe.</p>",
            "<p>Students complete the initial academic stages through Maverick and then move towards the final bachelor's degree through partner universities in Hungary, Romania, or Moldova. Ideal for students seeking flexible learning, credit transfer guidance, visa support, and career-focused academic support.</p>",
        ]);
        $this->migrator->add('gbp_overview.quote', 'This route is ideal for students who want an international degree pathway with flexible learning, credit transfer guidance, visa support, and career-focused academic support.');
        $this->migrator->add('gbp_overview.stages', [
            ['year' => '01', 'title' => 'Level 4 Diploma', 'duration' => 'Approx. 6 Months', 'description' => "Students begin with a Level 4 Diploma designed to build the academic foundation required for bachelor's progression."],
            ['year' => '02', 'title' => 'Level 5 Diploma', 'duration' => 'Approx. 6 Months', 'description' => 'Students then complete a Level 5 Diploma, strengthening their academic knowledge and preparing them for international university progression.'],
            ['year' => '03', 'title' => 'International University Progression', 'duration' => 'Partner University Stage', 'description' => 'After completing the required academic stages, students progress to an international partner university in Europe.'],
            ['year' => '04', 'title' => "International Bachelor's Degree", 'duration' => 'Final Outcome', 'description' => "Upon successful completion of the final university stage, students receive an internationally recognised bachelor's degree from the partner university."],
        ]);
        $this->migrator->add('gbp_overview.panel_label', 'Your Journey');
        $this->migrator->add('gbp_overview.panel_title', '4 Steps to Your Degree');
        $this->migrator->add('gbp_overview.panel_stats', [
            ['number' => '4', 'label' => 'Stages'],
            ['number' => '~12', 'label' => 'Months Total'],
            ['number' => '3', 'label' => 'Countries'],
        ]);

        $this->migrator->add('gbp_why.tag', 'OUR VALUE');
        $this->migrator->add('gbp_why.heading', 'Why Choose This');
        $this->migrator->add('gbp_why.heading_italic', 'Pathway Programme?');
        $this->migrator->add('gbp_why.quote', 'A smarter alternative to the traditional overseas route — start with Maverick, progress internationally at the right stage.');
        $this->migrator->add('gbp_why.paragraph', "<p>The Maverick Bachelor's Global Pathway is designed to give students a smarter alternative to the traditional 4-year overseas study route. Instead of moving abroad from year one and paying higher international tuition and living costs, students can begin their journey with Maverick through a Bachelor's Degree with Credit Transfer route and progress internationally at a later stage. This creates a more structured, affordable, and globally focused pathway towards completing a European Bachelor's degree.</p>");
        $this->migrator->add('gbp_why.items', [
            ['icon_key' => 'clock', 'title' => 'Save Time', 'description' => "The pathway can help students save up to one year compared with the traditional Bachelor's route."],
            ['icon_key' => 'award', 'title' => 'Earn 240 UK Credits', 'description' => 'Students complete structured UK credit-based qualifications before progressing to the university stage.'],
            ['icon_key' => 'shuffle', 'title' => 'Flexible Learning Route', 'description' => 'Students can begin their studies through flexible learning before moving into the final university progression stage.'],
            ['icon_key' => 'graduation-cap', 'title' => 'Direct University Progression', 'description' => 'The programme is designed to support progression to selected partner universities.'],
            ['icon_key' => 'trending-down', 'title' => 'Cost-Effective Study Route', 'description' => 'Students and parents can reduce overall study cost compared with starting the full overseas route from year one.'],
        ]);

        $this->migrator->add('gbp_explore.label', 'YOUR OPTIONS');
        $this->migrator->add('gbp_explore.heading', 'Explore Europe with');
        $this->migrator->add('gbp_explore.heading_italic', 'Your Choices');
        $this->migrator->add('gbp_explore.sub', "<p>Hungary | Romania | Moldova — With Maverick's Bachelor's Global Pathway, students can choose from multiple European progression routes based on their academic goals, budget, preferred destination, and long-term career plans.</p>");
        $this->migrator->add('gbp_explore.cards', [
            [
                'flag' => '🇭🇺',
                'country' => 'Hungary',
                'type' => 'Premium European Pathway',
                'university' => 'International Business School, Budapest',
                'highlights' => ['International study experience in Budapest', 'Dual degree opportunities', '100% placement assistance', 'Erasmus+ student exchange'],
            ],
            [
                'flag' => '🇷🇴',
                'country' => 'Romania',
                'type' => 'Affordable European Pathway',
                'university' => 'Aurel Vlaicu University',
                'highlights' => ['Affordable tuition fees', 'One-year completion route', 'Lower cost of living', 'Direct university progression'],
            ],
            [
                'flag' => '🇲🇩',
                'country' => 'Moldova',
                'type' => 'Affordable European Pathway',
                'university' => 'USPEE, Moldova',
                'highlights' => ['Lower overall study cost', 'Reduced study duration', 'Student visa guidance', 'Flexible pathway structure'],
            ],
        ]);

        $this->migrator->add('gbp_destinations.label', 'STUDY DESTINATIONS');
        $this->migrator->add('gbp_destinations.heading', 'Choose Your European');
        $this->migrator->add('gbp_destinations.heading_italic', 'Study Destination');
        $this->migrator->add('gbp_destinations.items', [
            [
                'slug' => 'hungary',
                'position' => 'right',
                'name' => 'Hungary',
                'label' => 'PREMIUM EUROPEAN PATHWAY',
                'university' => 'International Business School, Budapest',
                'description' => "<p>Hungary is a strong choice for students seeking a premium European study experience, international exposure, and a vibrant student-friendly environment. Through Maverick's pathway, students can study Bachelor's in Hungary while benefiting from structured academic guidance, career-focused support, and opportunities for global growth. Through Maverick's Hungary pathway, students can progress to International Business School, Budapest, one of the leading international business schools in Europe.</p>",
                'image' => 'https://images.pexels.com/photos/16356273/pexels-photo-16356273.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=760',
                'image_asset_id' => null,
                'points' => [
                    'International study experience in Budapest',
                    'Dual degree opportunities with University of Buckingham (UK) and Dublin Business School (Ireland)',
                    '100% placement assistance and career mentoring',
                    'Internship support connected to KPMG, Microsoft, Amazon and more',
                    '9–12 month post-study work opportunity',
                    'Access to 27+ Schengen countries',
                    'Erasmus+ student exchange opportunity',
                    'No IELTS / TOEFL required',
                ],
                'best_for' => 'Students looking for a premium European business education pathway with stronger international exposure and career development support.',
            ],
            [
                'slug' => 'romania',
                'position' => 'left',
                'name' => 'Romania',
                'label' => 'AFFORDABLE EUROPEAN PATHWAY',
                'university' => 'Aurel Vlaicu University, Romania',
                'description' => "<p>Attractive for students who want a European bachelor's degree pathway with affordable tuition, lower living costs, and direct university progression. Benefit from structured academic support, reduced study duration, and student visa guidance.</p>",
                'image' => 'https://images.pexels.com/photos/207684/pexels-photo-207684.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=760',
                'image_asset_id' => null,
                'points' => [
                    'Affordable tuition fees',
                    'One-year university completion route',
                    'Lower cost of living',
                    "Internationally recognised European degree",
                    'Direct university progression',
                    'Reduced overall study duration',
                    'Strong return on investment',
                    'Student visa guidance',
                ],
                'best_for' => "Students looking for an affordable European bachelor's route with reduced study duration and practical academic progression.",
            ],
            [
                'slug' => 'moldova',
                'position' => 'right',
                'name' => 'Moldova',
                'label' => 'AFFORDABLE EUROPEAN PATHWAY',
                'university' => 'USPEE, Moldova',
                'description' => "<p>Another affordable European pathway option for students looking for a cost-effective route to complete their bachelor's degree. Progress to USPEE, Moldova through Maverick's structured pathway.</p>",
                'image' => 'https://images.pexels.com/photos/346823/pexels-photo-346823.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=760',
                'image_asset_id' => null,
                'points' => [
                    'Affordable tuition fees',
                    'Lower overall study cost',
                    'Reduced study duration',
                    'International university progression',
                    'Student visa guidance',
                    'Career and academic support',
                    'Flexible pathway structure',
                ],
                'best_for' => "Students seeking an affordable and practical European bachelor's progression route.",
            ],
        ]);

        $this->migrator->add('gbp_cost.tag', 'COST & TIME ADVANTAGE');
        $this->migrator->add('gbp_cost.heading', 'A Smarter Alternative to the');
        $this->migrator->add('gbp_cost.heading_italic', 'Traditional 4-Year Route');
        $this->migrator->add('gbp_cost.description', "<p>A traditional bachelor's route usually requires students to study overseas from year one, which can increase total tuition fees, living costs, and overall time commitment. With Maverick's pathway, students can begin their academic journey through Maverick and progress to Europe for the final university stage.</p>");
        $this->migrator->add('gbp_cost.closing', '<p>This makes the pathway a practical option for students and parents who want a balance of affordability, international exposure, academic progression, and career value.</p>');
        $this->migrator->add('gbp_cost.comparisons', [
            ['label' => 'Traditional Route', 'value' => '4 Years', 'variant' => 'muted'],
            ['label' => 'Maverick Pathway', 'value' => '~3 years', 'variant' => 'accent'],
            ['label' => 'Time Saving — Hungary', 'value' => 'Up to 12 Months', 'variant' => 'muted'],
            ['label' => 'Time Saving — Romania & Moldova', 'value' => 'Up to 24 Months', 'variant' => 'muted'],
        ]);

        $this->migrator->add('gbp_comparison.heading', "<p>Compare the Traditional Route with Maverick's <em>Smart Bachelor's Pathway</em></p>");
        $this->migrator->add('gbp_comparison.cards', [
            [
                'is_recommended' => false,
                'title' => 'Traditional Route',
                'duration' => '4 Years',
                'tagline' => 'MORE EXPENSIVE & LONGER',
                'bullets' => [
                    'Higher overall tuition & living costs',
                    'Full overseas study from Year 1',
                    'Longer time to graduation',
                ],
                'price_mode' => 'single',
                'price_label' => 'Total Estimated Fees',
                'price_value' => 'EUR 58,000',
                'prices' => [],
            ],
            [
                'is_recommended' => true,
                'title' => 'Maverick Hybrid / Online Route',
                'duration' => 'Approx 3 Years',
                'tagline' => 'SMART & COST-EFFECTIVE',
                'bullets' => [
                    'Stage 1 & 2 at Maverick (Hybrid/Online)',
                    'Final University Progression in Europe',
                    'Flexible & cost-effective',
                ],
                'price_mode' => 'rows',
                'price_label' => 'Total Estimated Fees',
                'price_value' => null,
                'prices' => [
                    ['country' => 'Hungary', 'amount' => 'EUR 22,750'],
                    ['country' => 'Romania / Moldova', 'amount' => 'EUR 11,250'],
                ],
            ],
            [
                'is_recommended' => false,
                'title' => 'Maverick On-Campus Route',
                'duration' => 'Approx 2 Years',
                'tagline' => 'PREMIUM & STRUCTURED',
                'bullets' => [
                    'Stage 1 & 2 at Maverick (On Campus)',
                    'Final University Progression in Europe',
                    'Structured campus learning',
                ],
                'price_mode' => 'rows',
                'price_label' => 'Total Estimated Fees',
                'price_value' => null,
                'prices' => [
                    ['country' => 'Hungary', 'amount' => 'EUR 25,750'],
                    ['country' => 'Romania / Moldova', 'amount' => 'EUR 14,250'],
                ],
            ],
        ]);
        $this->migrator->add('gbp_comparison.callout_label', 'TIME SAVING');
        $this->migrator->add('gbp_comparison.callout_value', 'Up to 12 Months (Hungary)');
        $this->migrator->add('gbp_comparison.callout_description', '<p>A smarter pathway for students and parents seeking lower cost, reduced duration.</p>');

        $this->migrator->add('gbp_areas.label', 'PATHWAY AREAS');
        $this->migrator->add('gbp_areas.heading', "Choose a Bachelor's Pathway That Matches");
        $this->migrator->add('gbp_areas.heading_italic', 'Your Career Goals');
        $this->migrator->add('gbp_areas.sub', '<p>Career-focused pathway areas across business, technology, hospitality, and international management fields.</p>');
        $this->migrator->add('gbp_areas.cards', [
            [
                'icon_key' => 'briefcase',
                'title' => 'Business & Management',
                'description' => 'Careers in management, entrepreneurship, marketing, finance, operations, international business, or corporate leadership.',
                'items' => ['Business Administration', 'Business Management', 'International Business', 'Marketing', 'Human Resource Management', 'Finance & Accounting', 'Entrepreneurship', 'Business Analytics'],
            ],
            [
                'icon_key' => 'cpu',
                'title' => 'IT & Data',
                'description' => 'Enter fast-growing digital and technology-driven careers with globally in-demand skills.',
                'items' => ['Information Technology', 'Management Information Systems', 'Computer Science', 'Data Science', 'Business Analytics', 'AI & Data Analytics'],
            ],
            [
                'icon_key' => 'compass',
                'title' => 'Hospitality & Tourism',
                'description' => 'Build careers in tourism, hospitality, events, aviation services, hotel management, or international service industries.',
                'items' => ['Hospitality Management', 'Tourism Management', 'International Hospitality & Tourism', 'Service Management'],
            ],
            [
                'icon_key' => 'globe',
                'title' => 'International & European Studies',
                'description' => 'Global exposure and European academic progression with an internationally focused curriculum.',
                'items' => ['International Relations', 'International Business Management', 'European Business Studies', 'Business & Administration'],
            ],
        ]);

        $this->migrator->add('gbp_partners.label', 'PROGRESSION OPTIONS');
        $this->migrator->add('gbp_partners.heading', 'Partner University');
        $this->migrator->add('gbp_partners.heading_italic', 'Progression Options');
        $this->migrator->add('gbp_partners.sub', '<p>Three European progression routes — pick the one that fits your budget, timeline, and career direction.</p>');
        $this->migrator->add('gbp_partners.cards', [
            [
                'code' => 'HU',
                'name' => 'Hungary — Premium European Pathway',
                'description' => "Progress to International Business School, Budapest through Maverick's premium European route.",
                'best_for' => ['Business Management', 'International Business', 'Marketing', 'Finance', 'Data Analytics', 'AI & Business', 'Entrepreneurship'],
            ],
            [
                'code' => 'RO',
                'name' => 'Romania — Affordable European Pathway',
                'description' => "Progress to Aurel Vlaicu University, Romania through Maverick's affordable European pathway.",
                'best_for' => ['Business Administration', 'Management', 'Information Technology', 'Data Science', 'Hospitality & Tourism', 'International Business'],
            ],
            [
                'code' => 'MD',
                'name' => 'Moldova — Affordable European Pathway',
                'description' => "Progress to USPEE, Moldova through Maverick's affordable European pathway.",
                'best_for' => ['Business Administration', 'Management', 'Information Technology', 'Tourism & Hospitality', 'General Business Studies'],
            ],
        ]);

        $this->migrator->add('gbp_admission.label', 'ADMISSION');
        $this->migrator->add('gbp_admission.heading', 'Admission');
        $this->migrator->add('gbp_admission.heading_italic', 'Requirements');
        $this->migrator->add('gbp_admission.eligibility_title', 'Who Can Apply?');
        $this->migrator->add('gbp_admission.eligibility', [
            'High school / Grade 12 graduates',
            "Students who want to study bachelor's abroad",
            "Students looking for a European bachelor's degree",
            'Students seeking a cost-effective alternative to studying overseas from year one',
            'Students interested in credit transfer and international university progression',
            "Working professionals who want to complete their bachelor's degree pathway",
        ]);
        $this->migrator->add('gbp_admission.entry_title', 'General Entry Requirements');
        $this->migrator->add('gbp_admission.entry_requirements', [
            'High school / Grade 12 certificate or equivalent',
            'Academic transcripts / mark sheets',
            'Passport copy',
            'Passport-size photograph',
            'Updated CV, if applicable',
            'English language evidence, if required',
            'Completed application form',
            'Any additional documents required by the partner university or visa process',
        ]);
        $this->migrator->add('gbp_admission.note', '<p>No IELTS / TOEFL required, subject to admission requirements.</p>');

        $this->migrator->add('gbp_documents.label', 'CHECKLIST');
        $this->migrator->add('gbp_documents.heading', 'Documents Required');
        $this->migrator->add('gbp_documents.heading_italic', 'for Admission');
        $this->migrator->add('gbp_documents.groups', [
            [
                'icon_key' => 'user',
                'title' => 'Personal Documents',
                'items' => ['Passport copy', 'Passport-size photograph', 'Emirates ID copy (if applicable)', 'Updated CV (if applicable)'],
            ],
            [
                'icon_key' => 'book-open',
                'title' => 'Academic Documents',
                'items' => ['High school / Grade 12 certificate', 'Academic transcripts / mark sheets', 'Previous diploma or college documents (if applicable)', 'English language documents (if required)'],
            ],
            [
                'icon_key' => 'file-check',
                'title' => 'Additional Documents for Visa Stage',
                'items' => ['Bank statement or financial proof (if required)', 'Accommodation details (if required)', 'Travel insurance (if required)', 'Medical documents (if required)', 'Any additional documents requested by the embassy or university'],
            ],
        ]);

        $this->migrator->add('gbp_final_cta.eyebrow', 'Your Global Career Starts Here');
        $this->migrator->add('gbp_final_cta.heading', 'Start Your Global');
        $this->migrator->add('gbp_final_cta.heading_italic', "Bachelor's Journey");
        $this->migrator->add('gbp_final_cta.sub', "<p>Your international bachelor's degree pathway starts here.</p>");
        $this->migrator->add('gbp_final_cta.description', '<p>Begin with Maverick Business Academy London and progress towards selected partner universities in Hungary, Romania, or Moldova — with structured academic support, visa guidance, and career-focused counselling.</p>');
        $this->migrator->add('gbp_final_cta.ctas', [
            ['label' => 'Speak to an Admission Advisor', 'url' => '#advisor', 'style' => 'solid', 'anchor_id' => 'advisor'],
            ['label' => 'Download Brochure', 'url' => '#brochure', 'style' => 'outline', 'anchor_id' => 'brochure'],
            ['label' => 'Apply for the Next Intake', 'url' => '#apply', 'style' => 'outline', 'anchor_id' => 'apply'],
        ]);

        $this->migrator->add('gbp_seo.meta_title', "Global Bachelor's Pathway Programme | Study Bachelor's in Europe");
        $this->migrator->add('gbp_seo.meta_description', "Start your Bachelor's journey with Maverick Business Academy London and progress to partner universities in Hungary, Romania, or Moldova. Explore affordable European Bachelor's pathways with credit transfer, visa support, and career guidance.");
        $this->migrator->add('gbp_seo.meta_keywords', null);
        $this->migrator->add('gbp_seo.canonical_url', null);
        $this->migrator->add('gbp_seo.robots', 'index, follow');
        $this->migrator->add('gbp_seo.og_title', null);
        $this->migrator->add('gbp_seo.og_description', null);
        $this->migrator->add('gbp_seo.og_image_url', null);
        $this->migrator->add('gbp_seo.og_image_url_asset_id', null);
        $this->migrator->add('gbp_seo.og_type', 'website');
        $this->migrator->add('gbp_seo.twitter_card', 'summary_large_image');
        $this->migrator->add('gbp_seo.twitter_title', null);
        $this->migrator->add('gbp_seo.twitter_description', null);
        $this->migrator->add('gbp_seo.twitter_image_url', null);
        $this->migrator->add('gbp_seo.twitter_image_url_asset_id', null);
        $this->migrator->add('gbp_seo.schema_json', null);
        $this->migrator->add('gbp_seo.custom_head_scripts', null);
        $this->migrator->add('gbp_seo.custom_body_scripts', null);
    }
};
