<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\ProgramCategory;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Seed a Bachelors category and the Bachelor of Business Management (BSc)
     * programme, including its FAQs.
     */
    public function run(): void
    {
        $category = ProgramCategory::firstOrCreate(
            ['slug' => 'bachelors'],
            ['name' => 'Bachelors', 'icon' => 'graduation-cap', 'description' => 'Undergraduate business and management programmes.', 'is_active' => true]
        );

        $program = Program::firstOrCreate(
            ['slug' => 'bsc-business-management'],
            [
                'program_category_id' => $category->id,
                'title' => 'Bachelor of Business Management (BSc)',
                'partner_university' => 'Girne American University',
                'duration' => '20–24 Months', // VERIFY vs GAU 4 years
                'level' => 'BSc',
                'short_description' => 'Develop leadership, strategic thinking, entrepreneurial, and management skills through an internationally recognised bachelor\'s degree awarded by Girne American University (GAU).',
                'description' => 'The Bachelor of Business Management develops the knowledge, practical skills, and leadership abilities required to manage organisations in today\'s competitive global business environment. Students gain expertise in management, marketing, accounting, finance, economics, leadership, entrepreneurship, operations, and strategic decision-making while developing analytical, communication, and problem-solving skills valued by employers worldwide.',
                'image_url' => null, // falls back to default
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        $faqs = [
            ['Is the degree internationally recognised?', 'The BSc is awarded by Girne American University, an internationally recognised university. Recognition details are available from our admissions team.', 1],
            ['Can I study while working?', 'Yes. The programme supports flexible study for students and working professionals.', 2],
            ['How are students assessed?', 'Assessment is through assignments and examinations.', 3],
            ['What are the entry requirements?', 'Entry requirements are confirmed during your eligibility review by the admissions team.', 4],
            ['Are scholarships available?', 'Scholarship availability is confirmed by the admissions team.', 5],
            ['Can I continue to a master\'s degree?', 'Progression to postgraduate study is possible, subject to admission criteria.', 6],
        ];

        foreach ($faqs as [$question, $answer, $sort]) {
            $program->faqs()->updateOrCreate(
                ['question' => $question],
                ['answer' => $answer, 'sort_order' => $sort, 'is_active' => true]
            );
        }

        // Admin-driven detail content (JSON columns). Values mirror the
        // previous hardcoded blade data so nothing is lost on re-seed.
        $program->update([
            'highlights' => [
                ['label' => 'Awarded By', 'value' => 'Girne American University'],
                ['label' => 'Duration', 'value' => '20–24 Months'],
                ['label' => 'Learning', 'value' => 'Flexible'],
                ['label' => 'Curriculum', 'value' => 'Industry-Focused'],
                ['label' => 'Scholarships', 'value' => 'Available'],
            ],
            'recognition' => [
                ['name' => 'Girne American University', 'logo' => 'https://www.gau.edu.tr/template/gau/assets/img/logo2_en.png'],
                ['name' => 'IACBE', 'logo' => 'https://www.gau.edu.tr/storage//uploads/0/0/0/1786107837072-1786107836.png?vs=1', 'note' => '<p>International Accreditation Council for Business Education</p>'],
                ['name' => 'YÖK', 'logo' => 'https://www.gau.edu.tr/storage//uploads/0/0/0/1786107465646-1786107464.png?vs=1', 'note' => '<p>Higher Education Council of Turkey</p>'],
                ['name' => 'YÖDAK', 'logo' => 'https://www.gau.edu.tr/storage//uploads/0/0/0/1786107476028-1786107474.png?vs=1', 'note' => '<p>Higher Education Planning, Supervision, Accreditation and Coordination Committee (North Cyprus)</p>'],
            ],
            'snapshot' => [
                ['label' => 'Degree Award', 'value' => 'BSc'],
                ['label' => 'Awarding University', 'value' => 'Girne American University'],
                ['label' => 'Duration', 'value' => '20–24 Months'],
                ['label' => 'Study Mode', 'value' => 'Online / Hybrid'],
                ['label' => 'Intakes', 'value' => 'Multiple'],
                ['label' => 'Assessment', 'value' => 'Assignments & Examinations'],
            ],
            'benefits' => [
                ['title' => 'Develop Leadership Skills', 'desc' => '<p>Learn how to lead teams and organisations.</p>', 'icon' => 'users'],
                ['title' => 'Industry-Relevant Curriculum', 'desc' => '<p>Practical learning aligned with current business practices.</p>', 'icon' => 'book-open'],
                ['title' => 'International Recognition', 'desc' => '<p>Graduate with an internationally recognised university qualification.</p>', 'icon' => 'globe'],
                ['title' => 'Career Progression', 'desc' => '<p>Prepare for leadership roles across multiple industries.</p>', 'icon' => 'trending-up'],
                ['title' => 'Flexible Learning', 'desc' => '<p>Designed to support both students and working professionals.</p>', 'icon' => 'laptop'],
            ],
            'learning' => [
                ['item' => 'Develop strategic thinking'],
                ['item' => 'Apply business management principles'],
                ['item' => 'Analyse financial information'],
                ['item' => 'Understand marketing strategies'],
                ['item' => 'Improve organisational performance'],
                ['item' => 'Lead diverse teams'],
                ['item' => 'Make ethical business decisions'],
                ['item' => 'Manage business operations effectively'],
            ],
            'careers' => [
                ['title' => 'Business Manager'],
                ['title' => 'Operations Manager'],
                ['title' => 'Marketing Executive'],
                ['title' => 'Human Resource Executive'],
                ['title' => 'Business Analyst'],
                ['title' => 'Project Coordinator'],
                ['title' => 'Entrepreneur'],
                ['title' => 'Sales Manager'],
                ['title' => 'Business Development Executive'],
                ['title' => 'Management Consultant'],
            ],
            'structure' => [
                ['title' => 'Year 1', 'subtitle' => 'Business Foundations', 'modules' => [
                    ['title' => 'Principles of Management', 'overview' => '<p>Core concepts of managing people and organisations — from planning and organising to leading and controlling.</p>', 'list' => [['point' => 'Planning & strategy'], ['point' => 'Organisational structures'], ['point' => 'Leadership styles']]],
                    ['title' => 'Business Economics', 'overview' => '<p>Economic principles applied to business decisions, covering both micro and macroeconomic influences.</p>', 'list' => [['point' => 'Demand & supply'], ['point' => 'Market structures']]],
                    ['title' => 'Accounting Fundamentals', 'overview' => '<p>Basics of financial and management accounting — reading, interpreting and using financial information.</p>'],
                    ['title' => 'Marketing Essentials', 'overview' => '<p>Foundations of marketing and market analysis.</p>'],
                ]],
                ['title' => 'Year 2', 'subtitle' => 'Core Business Functions', 'modules' => [
                    ['title' => 'Financial Management', 'desc' => '<p>Managing financial resources and planning.</p>'],
                    ['title' => 'Organisational Behaviour', 'desc' => '<p>Understanding people and behaviour in organisations.</p>'],
                    ['title' => 'Operations Management', 'desc' => '<p>Designing and managing business operations.</p>'],
                    ['title' => 'Business Law', 'desc' => '<p>Legal frameworks relevant to business.</p>'],
                ]],
                ['title' => 'Year 3', 'subtitle' => 'Advanced Business Management', 'modules' => [
                    ['title' => 'Strategic Management', 'desc' => '<p>Developing and executing organisational strategy.</p>'],
                    ['title' => 'International Business', 'desc' => '<p>Operating across global markets and cultures.</p>'],
                    ['title' => 'Entrepreneurship', 'desc' => '<p>Creating and scaling new ventures.</p>'],
                    ['title' => 'Human Resource Management', 'desc' => '<p>Managing talent, teams and organisational culture.</p>'],
                ]],
                ['title' => 'Year 4', 'subtitle' => 'Leadership, Strategy & Internship / Capstone', 'modules' => [
                    ['title' => 'Leadership & Change', 'desc' => '<p>Leading teams and managing organisational change.</p>'],
                    ['title' => 'Business Strategy', 'desc' => '<p>Advanced strategic thinking and decision-making.</p>'],
                    ['title' => 'Internship / Capstone', 'desc' => '<p>Practical application through an internship or final project.</p>'],
                    ['title' => 'Global Business Perspectives', 'desc' => '<p>Contemporary issues shaping the global business landscape.</p>'],
                ]],
            ],
            'support' => [
                ['item' => 'Dedicated Academic Support'],
                ['item' => 'Experienced Faculty'],
                ['item' => 'Flexible Learning'],
                ['item' => 'Student Success Team'],
                ['item' => 'Assignment Support'],
                ['item' => 'Affordable Instalments'],
                ['item' => 'Career Guidance'],
                ['item' => 'Graduation Support'],
            ],
            'university' => [
                ['name' => 'Girne American University', 'description' => "<p>Girne American University (GAU), established in 1985, is one of Northern Cyprus' leading universities. It offers internationally focused education with programmes designed to prepare graduates for the global workplace.</p>", 'establishment' => 'Established 1985', 'image' => null],
            ],
            'accreditation_groups' => [
                ['group' => 'Institutional Recognition', 'items' => [
                    ['name' => 'GAU', 'logo' => 'https://www.gau.edu.tr/storage//uploads/0/0/0/logo-gau-3-1681804294.png?vs=1'],
                    ['name' => 'YÖDAK', 'logo' => 'https://www.gau.edu.tr/storage//uploads/0/0/0/1786107476028-1786107474.png?vs=1'],
                ]],
                ['group' => 'International Accreditation', 'items' => [
                    ['name' => 'IACBE', 'logo' => 'https://www.gau.edu.tr/storage//uploads/0/0/0/1786107837072-1786107836.png?vs=1'],
                ]],
                ['group' => 'Professional Recognition', 'items' => [
                    ['name' => 'YÖK', 'logo' => 'https://www.gau.edu.tr/storage//uploads/0/0/0/1786107465646-1786107464.png?vs=1'],
                ]],
            ],
            'testimonials' => [
                ['name' => 'Verified Student', 'role' => 'Business Manager', 'country' => 'UAE', 'category' => 'STUDENT'],
                ['name' => 'Verified Graduate', 'role' => 'Marketing Executive', 'country' => 'UAE', 'category' => 'GRADUATE'],
                ['name' => 'Verified Learner', 'role' => 'Entrepreneur', 'country' => 'UAE', 'category' => 'STUDENT'],
            ],
            'fees' => [
                ['title' => 'Registration Fee'],
                ['title' => 'Initial Payment'],
                ['title' => 'Monthly Instalments'],
                ['title' => 'Scholarship Availability'],
                ['title' => 'Offer Validity'],
            ],
            'reviews' => [
                ['name' => 'Rahul S.', 'rating' => 5, 'review' => '<p>Great programme structure and excellent support throughout my studies.</p>'],
                ['name' => 'Fatima A.', 'rating' => 5, 'review' => '<p>The flexible learning approach let me balance work and study. Highly recommended.</p>'],
                ['name' => 'Mohammed K.', 'rating' => 4, 'review' => '<p>A solid international pathway with very helpful advisors.</p>'],
            ],
        ]);

        $this->command?->info("Seeded programme: {$program->title}");
    }
}
