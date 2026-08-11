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

        $this->command?->info("Seeded programme: {$program->title}");
    }
}
