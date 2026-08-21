<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('mp_hero.tag', "MASTER'S PATHWAY");
        $this->migrator->add('mp_hero.heading', "International Master's");
        $this->migrator->add('mp_hero.heading_highlight', 'Pathway Program');
        $this->migrator->add('mp_hero.sub', "<p>A smarter route to a globally recognised Master's degree in Europe</p>");
        $this->migrator->add('mp_hero.paragraphs', [
            "<p>The Maverick International Master's Pathway Program is designed for graduates and working professionals who want a structured, flexible and cost-effective route towards an international Master's degree.</p>",
            "<p>Through this pathway, students complete the first academic phase with Maverick Business Academy London through a Level 7 Diploma carrying 120 UK credits, followed by progression to a partner university for the final stage of study in Hungary, Moldova or Romania, subject to university approval and academic mapping.</p>",
        ]);
        $this->migrator->add('mp_hero.background_image', 'https://images.pexels.com/photos/256541/pexels-photo-256541.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=1600');
        $this->migrator->add('mp_hero.background_image_asset_id', null);
        $this->migrator->add('mp_hero.ctas', [
            ['label' => 'Check Eligibility', 'url' => '#enquire', 'style' => 'primary'],
            ['label' => 'Speak to an Advisor', 'url' => '/contact', 'style' => 'secondary'],
        ]);
        $this->migrator->add('mp_hero.route_steps', [
            ['label' => 'Level 7 Diploma'],
            ['label' => 'Partner University'],
            ['label' => "Master's Completion"],
        ]);

        $this->migrator->add('mp_overview.label', 'YOUR PATHWAY');
        $this->migrator->add('mp_overview.heading', "What Is the Maverick Master's");
        $this->migrator->add('mp_overview.heading_highlight', 'Pathway Program?');
        $this->migrator->add('mp_overview.paragraphs', [
            "<p>The Maverick Master's Pathway Program is a two-phase international study route created for learners who want to begin their postgraduate journey with flexibility and progress towards a European university qualification.</p>",
            '<p>In Phase 1, students complete a Level 7 Diploma with 120 UK credits through Maverick. In Phase 2, eligible students may apply for university progression, where the completed Level 7 qualification is reviewed for credit mapping and advanced-entry consideration by the destination university.</p>',
            '<p>This pathway is ideal for students who want to reduce the time and cost of full-time overseas study while still gaining international academic exposure during the university completion stage.</p>',
        ]);
        $this->migrator->add('mp_overview.phases', [
            ['label' => 'PHASE 1', 'title' => 'Level 7 Diploma', 'meta' => '120 UK credits', 'desc' => 'Complete your first phase online with Maverick.'],
            ['label' => 'PHASE 2', 'title' => 'Partner University', 'meta' => 'Final-stage study', 'desc' => 'Hungary / Moldova / Romania'],
        ]);

        $this->migrator->add('mp_how.label', 'THE JOURNEY');
        $this->migrator->add('mp_how.heading', "How the Master's Pathway");
        $this->migrator->add('mp_how.heading_highlight', 'Works');
        $this->migrator->add('mp_how.phases', [
            [
                'num' => 'PHASE 01',
                'title' => 'Level 7 Diploma',
                'sub' => 'Subject-Aligned Level 7 Diploma',
                'facts' => [
                    ['label' => 'Duration', 'value' => 'Approx. 6 months'],
                    ['label' => 'Mode', 'value' => 'Online / With Maverick'],
                    ['label' => 'Academic Evaluation', 'value' => 'Credit mapping and university review'],
                ],
            ],
            [
                'num' => 'PHASE 02',
                'title' => 'Final Stage at Partner University',
                'sub' => 'Final semester / final year',
                'facts' => [
                    ['label' => 'Duration', 'value' => 'Approx. 1 semester to 1 year'],
                    ['label' => 'Mode', 'value' => 'On Campus / Abroad'],
                    ['label' => 'Completion', 'value' => 'Dissertation, project or final university assessment'],
                    ['label' => 'Partner University', 'value' => 'University-defined Partner University'],
                ],
            ],
        ]);
        $this->migrator->add('mp_how.notice', '<p>The final award is issued by the destination university after the student completes all required academic, assessment, residency, attendance and graduation requirements.</p>');

        $this->migrator->add('mp_destinations.label', 'STUDY DESTINATIONS');
        $this->migrator->add('mp_destinations.heading', 'Study');
        $this->migrator->add('mp_destinations.heading_highlight', 'Destinations');
        $this->migrator->add('mp_destinations.sub', "<p>Explore the three European destinations where you can complete the final stage of your Master's pathway.</p>");
        $this->migrator->add('mp_destinations.items', [
            [
                'slug' => 'hungary',
                'name' => 'Hungary',
                'label' => 'CENTRAL EUROPE',
                'university' => 'IBS International Business School',
                'image' => 'https://images.pexels.com/photos/16356273/pexels-photo-16356273.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=950&w=760',
                'image_asset_id' => null,
                'position' => 'left',
                'description' => "<p>Students may choose Hungary as a premium Central European study destination with international classroom exposure and strong academic progression options. Maverick's Hungary route is connected with IBS International Business School, with final-stage study expected to take approximately one academic year, subject to IBS academic mapping and admission approval. The available specialisation areas may include management, finance, marketing, AI, cybersecurity and business analytics.</p>",
                'points' => ['Management', 'Finance', 'Marketing', 'AI', 'Cybersecurity', 'Business Analytics'],
                'best_for' => "Students looking for a premium European Master's experience with specialised business and technology-related study options.",
                'qualification' => 'Subject to IBS academic mapping and admission approval.',
            ],
            [
                'slug' => 'moldova',
                'name' => 'Moldova',
                'label' => 'EASTERN EUROPE',
                'university' => 'USPEE "Constantin Stere University"',
                'image' => 'https://images.pexels.com/photos/346823/pexels-photo-346823.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=950&w=760',
                'image_asset_id' => null,
                'position' => 'right',
                'description' => "<p>Moldova is positioned as a flexible and cost-efficient European degree completion destination. Students complete a relevant Level 7 Diploma carrying 120 UK credits, aligned with their intended Master's specialisation. The Moldova route is connected with USPEE \"Constantin Stere University\", where students may progress to the university-defined final stage, subject to admission, residency requirements, academic compatibility and credit mapping.</p>",
                'points' => ['Level 7 Diploma carrying 120 UK credits', 'Subject-aligned pathway', 'Flexible and cost-efficient completion'],
                'best_for' => "Students looking for a cost-effective European Master's completion route with flexible academic options.",
                'qualification' => 'Subject to admission, residency requirements, academic compatibility and credit mapping.',
            ],
            [
                'slug' => 'romania',
                'name' => 'Romania',
                'label' => 'SOUTHEAST EUROPE',
                'university' => 'Aurel Vlaicu University of Arad',
                'image' => 'https://images.pexels.com/photos/207684/pexels-photo-207684.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=950&w=760',
                'image_asset_id' => null,
                'position' => 'left',
                'description' => '<p>Romania provides a practical European study option for students who want to complete the final university stage on campus. The Romania route is connected with Aurel Vlaicu University of Arad, where eligible students may progress after completing the 120 UK-credit Level 7 stage. Final progression is subject to admission, credit recognition, curriculum alignment and selected programme requirements.</p>',
                'points' => ["On-campus final-stage Master's experience", 'Direct European progression', '120 UK-credit Level 7 stage'],
                'best_for' => "Students looking for direct European progression and an on-campus final-stage Master's experience.",
                'qualification' => 'Final progression is subject to admission, credit recognition, curriculum alignment and selected programme requirements.',
            ],
        ]);

        $this->migrator->add('mp_why.label', 'WHY MAVERICK');
        $this->migrator->add('mp_why.heading', "Why Choose Maverick's");
        $this->migrator->add('mp_why.heading_highlight', "Master's Pathway?");
        $this->migrator->add('mp_why.statement', 'A pathway built around your life.');
        $this->migrator->add('mp_why.items', [
            ['title' => 'Flexible First Phase', 'desc' => 'Start your postgraduate journey online or through Maverick while continuing your professional and personal commitments.'],
            ['title' => 'Cost-Efficient Route', 'desc' => 'Reduce the period of full-time overseas study and lower your overall exposure to international tuition and living costs.'],
            ['title' => 'European University Progression', 'desc' => "Progress towards final-stage Master's completion in Hungary, Moldova or Romania, subject to university approval."],
            ['title' => 'Academic Mapping Support', 'desc' => 'Maverick supports students with documentation, credit mapping coordination, university communication and progression guidance.'],
            ['title' => 'Career-Focused Learning', 'desc' => 'Develop advanced academic, analytical, leadership, research and management capabilities for professional growth.'],
        ]);

        $this->migrator->add('mp_audience.label', 'WHO IS IT FOR');
        $this->migrator->add('mp_audience.heading', 'Who Is This Program');
        $this->migrator->add('mp_audience.heading_highlight', 'For?');
        $this->migrator->add('mp_audience.statement', "This Master's Pathway is suitable for:");
        $this->migrator->add('mp_audience.items', [
            "Graduates who want to progress towards an international Master's degree",
            'Working professionals seeking postgraduate advancement',
            'Students looking for a cost-effective European study route',
            'Learners who want to begin online before travelling abroad',
            'Applicants interested in management, IT, finance, marketing, AI, cybersecurity, data analytics and related fields',
            'Students who need structured support for academic progression and documentation',
        ]);

        $this->migrator->add('mp_requirements.label', 'ENTRY REQUIREMENTS');
        $this->migrator->add('mp_requirements.heading', 'Entry');
        $this->migrator->add('mp_requirements.heading_highlight', 'Requirements');
        $this->migrator->add('mp_requirements.intro', '<p>Applicants are generally expected to provide:</p>');
        $this->migrator->add('mp_requirements.items', [
            "Recognised Bachelor's degree or equivalent qualification",
            'Academic transcripts and certificates',
            'Valid passport or national ID',
            'Updated CV',
            'Statement of purpose or motivation letter',
            'English-language evidence, where required',
            'Successful academic and admissions review',
            'Additional documents, interviews or legalisation, where required by the destination university',
        ]);

        $this->migrator->add('mp_process.label', 'APPLICATION PROCESS');
        $this->migrator->add('mp_process.heading', 'Application');
        $this->migrator->add('mp_process.heading_highlight', 'Process');
        $this->migrator->add('mp_process.steps', [
            ['icon_key' => 'chat', 'num' => '01', 'title' => 'Academic Consultation', 'desc' => "Choose your preferred destination and intended Master's specialisation."],
            ['icon_key' => 'shield', 'num' => '02', 'title' => 'Eligibility Review', 'desc' => 'Submit your academic documents, CV, passport and required records.'],
            ['icon_key' => 'graduation-cap', 'num' => '03', 'title' => 'Phase 1 Enrolment', 'desc' => 'Begin the Level 7 Diploma / subject-aligned Level 7 stage with Maverick.'],
            ['icon_key' => 'map-pin', 'num' => '04', 'title' => 'University Mapping', 'desc' => 'Maverick coordinates academic records for credit evaluation and progression review.'],
            ['icon_key' => 'file-check', 'num' => '05', 'title' => 'Conditional or Final Offer', 'desc' => 'The destination university confirms eligibility, remaining modules, fees and conditions.'],
            ['icon_key' => 'plane', 'num' => '06', 'title' => 'Visa & Travel Preparation', 'desc' => 'Complete tuition payment, documentation, accommodation and visa formalities.'],
            ['icon_key' => 'award', 'num' => '07', 'title' => 'University Completion', 'desc' => 'Complete the final semester/year, dissertation, project or final assessment and graduate.'],
        ]);

        $this->migrator->add('mp_notice.label', 'ACADEMIC NOTICE');
        $this->migrator->add('mp_notice.body', '<p>Progression is <strong>not automatic</strong>. Admission, credit recognition, advanced standing, specialisation availability, final duration and award requirements are determined by the destination university after formal academic evaluation.</p><p>Students must meet all university admission, language, attendance, residency, assessment, dissertation, financial and immigration requirements. Programme structures, fees, visa rules and timelines may change as per university and government regulations.</p>');

        $this->migrator->add('mp_final_cta.eyebrow', 'Take the Next Step');
        $this->migrator->add('mp_final_cta.heading', "Start Your Master's Journey with");
        $this->migrator->add('mp_final_cta.heading_highlight', 'Maverick');
        $this->migrator->add('mp_final_cta.sub', '<p>A structured, flexible and cost-effective route towards a globally recognised degree.</p>');
        $this->migrator->add('mp_final_cta.description', "<p>Take the first step towards an international Master's degree through a structured, flexible and cost-effective European progression route. Speak to our admissions team today to check your eligibility, destination options and academic pathway.</p>");
        $this->migrator->add('mp_final_cta.ctas', [
            ['label' => 'Request Eligibility Assessment', 'url' => '/contact', 'style' => 'solid'],
            ['label' => 'Speak to an Advisor', 'url' => 'mailto:admissions@mbalondon.org.uk', 'style' => 'outline'],
        ]);
        $this->migrator->add('mp_final_cta.contacts', [
            ['label' => 'admissions@mbalondon.org.uk', 'url' => 'mailto:admissions@mbalondon.org.uk'],
            ['label' => 'www.mbalondon.org.uk', 'url' => 'https://www.mbalondon.org.uk'],
        ]);

        $this->migrator->add('mp_seo.meta_title', "International Master's Pathway Program | Maverick Business Academy London");
        $this->migrator->add('mp_seo.meta_description', "Start your international Master's journey with Maverick Business Academy London. Complete a Level 7 Diploma and progress to partner universities in Hungary, Moldova or Romania for final-stage Master's completion.");
        $this->migrator->add('mp_seo.meta_keywords', null);
        $this->migrator->add('mp_seo.canonical_url', null);
        $this->migrator->add('mp_seo.robots', 'index, follow');
        $this->migrator->add('mp_seo.og_title', null);
        $this->migrator->add('mp_seo.og_description', null);
        $this->migrator->add('mp_seo.og_image_url', null);
        $this->migrator->add('mp_seo.og_image_url_asset_id', null);
        $this->migrator->add('mp_seo.og_type', 'website');
        $this->migrator->add('mp_seo.twitter_card', 'summary_large_image');
        $this->migrator->add('mp_seo.twitter_title', null);
        $this->migrator->add('mp_seo.twitter_description', null);
        $this->migrator->add('mp_seo.twitter_image_url', null);
        $this->migrator->add('mp_seo.twitter_image_url_asset_id', null);
        $this->migrator->add('mp_seo.schema_json', null);
        $this->migrator->add('mp_seo.custom_head_scripts', null);
        $this->migrator->add('mp_seo.custom_body_scripts', null);
    }
};
