<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\ProgramCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProgramSeeder extends Seeder
{
    /**
     * Seed a Bachelors category and the Bachelor of Business Management (BSc)
     * programme, including its FAQs, using verified / clearly-flagged content.
     */
    public function run(): void
    {
        // 1. Category
        $category = ProgramCategory::firstOrCreate(
            ['slug' => 'bachelors'],
            [
                'name' => 'Bachelors',
                'icon' => 'graduation-cap',
                'description' => 'Undergraduate business and management programmes.',
                'is_active' => true,
            ]
        );

        // 2. Programme
        $program = Program::firstOrCreate(
            ['slug' => 'bsc-business-management'],
            [
                'program_category_id' => $category->id,
                'title' => 'Bachelor of Business Management (BSc)',
                'partner_university' => 'Girne American University',
                'duration' => '20–24 Months', // VERIFY: Maverick page states 20–24 months; GAU lists 4 years
                'level' => 'BSc',
                'short_description' => 'Develop leadership, strategic thinking, entrepreneurial, and management skills through an internationally recognised bachelor\'s degree awarded by Girne American University (GAU).',
                'description' => 'The Bachelor of Business Management develops the knowledge, practical skills, and leadership abilities required to manage organisations in today\'s competitive global business environment. Students gain expertise in management, marketing, accounting, finance, economics, leadership, entrepreneurship, operations, and strategic decision-making while developing analytical, communication, and problem-solving skills valued by employers worldwide.',
                'image_url' => null, // falls back to homepage default image
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        // 3. FAQs (morph to programme)
        $faqs = [
            ['question' => 'Is the degree internationally recognised?', 'answer' => 'The BSc is awarded by Girne American University, an internationally recognised university. Recognition details are available from our admissions team.', 'sort_order' => 1],
            ['question' => 'Can I study while working?', 'answer' => 'Yes. The programme supports flexible study for students and working professionals.', 'sort_order' => 2],
            ['question' => 'How are students assessed?', 'answer' => 'Assessment is through assignments and examinations.', 'sort_order' => 3],
            ['question' => 'What are the entry requirements?', 'answer' => 'Entry requirements are confirmed during your eligibility review by the admissions team.', 'sort_order' => 4],
            ['question' => 'Are scholarships available?', 'answer' => 'Scholarship availability is confirmed by the admissions team.', 'sort_order' => 5],
            ['question' => 'Can I continue to a master\'s degree?', 'answer' => 'Progression to postgraduate study is possible, subject to admission criteria.', 'sort_order' => 6],
        ];

        foreach ($faqs as $faq) {
            $program->faqs()->updateOrCreate(
                ['question' => $faq['question']],
                [
                    'answer' => $faq['answer'],
                    'sort_order' => $faq['sort_order'],
                    'is_active' => true,
                ]
            );
        }

        $this->command?->info("Seeded programme: {$program->title}");
    }
}
