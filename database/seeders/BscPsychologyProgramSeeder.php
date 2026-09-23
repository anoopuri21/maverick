<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\UniversityPartner;
use Illuminate\Database\Seeder;

class BscPsychologyProgramSeeder extends Seeder
{
    /**
     * Seed the Girne American University BSc in Psychology programme
     * from the bachelor-program landing copy.
     */
    public function run(): void
    {
        $category = ProgramCategory::firstOrCreate(
            ['slug' => 'bachelors'],
            ['name' => 'Bachelors', 'icon' => 'graduation-cap', 'description' => 'Undergraduate business and management programmes.', 'is_active' => true]
        );

        $university = UniversityPartner::firstOrCreate(
            ['slug' => 'girne-american-university'],
            [
                'name' => 'Girne American University',
                'country' => 'Northern Cyprus',
                'country_code' => 'CY',
                'description' => "<p>Girne American University (GAU), established in 1985, is one of Northern Cyprus' leading universities. It offers internationally focused education with programmes designed to prepare graduates for the global workplace.</p>",
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        $program = Program::updateOrCreate(
            ['slug' => 'bsc-psychology'],
            [
                'program_category_id' => $category->id,
                'university_partner_id' => $university->id,
                'title' => 'BSc in Psychology',
                'duration' => 'Subject to the approved academic pathway and entry route',
                'level' => 'BSc',
                'short_description' => 'Why do people behave the way they do? The Girne American University BSc in Psychology studies behaviour, cognition, personality, development, and social interaction scientifically, with research and critical thinking built in from the start.',
                'description' => '<p>How does the brain influence thought and emotion? How are personality, childhood experiences, social environments, and relationships connected to human behaviour? The BSc Psychology programme explores these questions through the scientific study of behaviour and mental processes, developing knowledge across cognitive psychology, social psychology, developmental psychology, biological psychology, personality, mental health, and research methods.</p>'
                    .'<p>Alongside theoretical knowledge, the programme builds practical skills in research, critical thinking, communication, data interpretation, and evidence-based analysis. It suits people interested in understanding human behaviour as a discipline in its own right, and professionals working across human resources, education, healthcare administration, training, customer experience, leadership, and community services who want the science behind what they already do.</p>'
                    .'<p>Girne American University awards the degree. What you do with the understanding afterwards, in HR rooms, classrooms, clinics, or your own research, is where it counts.</p>',
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 2,
                'highlights' => [
                    ['label' => 'Awarded by', 'value' => 'Girne American University'],
                    ['label' => 'Study mode', 'value' => 'Online, flexible study'],
                    ['label' => 'Focus', 'value' => 'Psychology-focused programme'],
                    ['label' => 'Learning', 'value' => 'Research & applied learning'],
                    ['label' => 'Outcome', 'value' => 'Career-focused'],
                    ['label' => 'Funding', 'value' => 'Scholarship available'],
                ],
                'snapshot' => [
                    ['label' => 'Degree Award', 'value' => 'Bachelor of Science (BSc)'],
                    ['label' => 'Awarding University', 'value' => 'Girne American University, North Cyprus'],
                    ['label' => 'Specialisation', 'value' => 'Psychology'],
                    ['label' => 'Duration', 'value' => 'Subject to the approved academic pathway and entry route'],
                    ['label' => 'Assessments', 'value' => 'Assignments, case studies, research projects & applied assessments'],
                    ['label' => 'Focus', 'value' => 'Human Behaviour, Cognition, Development, Mental Health & Psychological Research'],
                    ['label' => 'Pathway note', 'value' => 'Programme duration and entry route may vary depending on previous academic qualifications, approved credit transfer, and institutional requirements. Admissions confirms the exact pathway and fee structure in writing before any payment.'],
                ],
                'benefits' => [
                    ['title' => 'Understand human behaviour', 'desc' => '<p>The psychological factors that influence how people think, communicate, learn, develop, and make decisions.</p>', 'icon' => 'brain'],
                    ['title' => 'Study psychology scientifically', 'desc' => '<p>How psychologists use research, evidence, and data to investigate human behaviour, rather than relying on intuition.</p>', 'icon' => 'microscope'],
                    ['title' => 'Multiple areas of psychology', 'desc' => '<p>Knowledge across cognitive, social, developmental, biological, and personality psychology, plus mental health.</p>', 'icon' => 'layers'],
                    ['title' => 'Strong research skills', 'desc' => '<p>How to design research, analyse information, and evaluate psychological evidence critically, which is the discipline\'s core skill.</p>', 'icon' => 'search'],
                    ['title' => 'Transferable professional skills', 'desc' => '<p>Communication, analytical thinking, research, and interpersonal skills that apply across many industries, from HR to marketing.</p>', 'icon' => 'briefcase'],
                    ['title' => 'Study around your career', 'desc' => '<p>Flexible learning allows working professionals to continue their education while managing professional and personal commitments.</p>', 'icon' => 'laptop'],
                ],
                'learning' => [
                    ['item' => 'Understand major psychological theories and concepts'],
                    ['item' => 'Analyse human behaviour from different psychological perspectives'],
                    ['item' => 'Understand cognitive processes including memory, learning, and decision-making'],
                    ['item' => 'Explore human development across different stages of life'],
                    ['item' => 'Understand the influence of social environments on behaviour'],
                    ['item' => 'Explore personality and individual differences'],
                    ['item' => 'Understand the biological foundations of behaviour'],
                    ['item' => 'Apply psychological research methods and basic statistical analysis'],
                    ['item' => 'Evaluate psychological research critically and ethically'],
                    ['item' => 'Communicate psychological ideas clearly and professionally'],
                ],
                'careers' => [
                    ['title' => 'Psychology Research Assistant'],
                    ['title' => 'Behavioural Support Assistant'],
                    ['title' => 'Human Resources Executive'],
                    ['title' => 'Recruitment Executive'],
                    ['title' => 'Learning & Development Executive'],
                    ['title' => 'Employee Engagement Executive'],
                    ['title' => 'Training Coordinator'],
                    ['title' => 'People & Culture Executive'],
                    ['title' => 'Community Support Officer'],
                    ['title' => 'Student Support Executive'],
                    ['title' => 'Educational Support Officer'],
                    ['title' => 'Social Services Assistant'],
                    ['title' => 'Customer Experience Executive'],
                    ['title' => 'Market Research Executive'],
                    ['title' => 'Research Coordinator'],
                    ['title' => 'Wellbeing Programme Coordinator'],
                    ['title' => 'Youth Support Worker'],
                    ['title' => 'Organisational Development Assistant'],
                    ['title' => 'Behavioural Research Executive'],
                    ['title' => 'Administrative & People Management Roles'],
                ],
                'structure' => [
                    [
                        'title' => 'Business and management foundations',
                        'subtitle' => 'Years 1 and 2',
                        'modules' => $this->modules([
                            'Communications in Organizations',
                            'Leadership and the Organization',
                            'Financial Awareness',
                            'Managing Change',
                            'Business Operations',
                            'Developing Teams',
                            'Responding to the Changing Business Environment',
                            'Effective Decision Making',
                            'Business Development',
                            'Business Models & Growing Organizations',
                            'Customer Management',
                            'Risk Management & Organizations',
                        ]),
                    ],
                    [
                        'title' => 'Psychology specialization',
                        'subtitle' => '',
                        'modules' => $this->modules([
                            'Cognitive & Experimental Psychology',
                            'Clinical Psychology',
                            'Personality Psychology and Assessment',
                            'Counselling and Psychotherapy',
                            'Social and Health Psychology',
                            'Developmental Psychology',
                        ]),
                    ],
                    [
                        'title' => 'Psychology focus areas',
                        'subtitle' => '',
                        'modules' => $this->modules([
                            'Cognitive psychology',
                            'Developmental psychology',
                            'Social psychology',
                            'Biological psychology',
                            'Personality psychology',
                            'Mental health',
                            'Organisational psychology',
                            'Research methods',
                            'Psychological assessment',
                            'Counselling foundations',
                            'Behavioural science',
                            'Human development',
                        ]),
                    ],
                ],
                'support' => [
                    ['item' => 'Dedicated academic support'],
                    ['item' => 'Experienced faculty'],
                    ['item' => 'Student success team'],
                    ['item' => 'Assignment guidance'],
                    ['item' => 'Career guidance'],
                    ['item' => 'Documentation assistance'],
                ],
                'gcc_heading' => 'Why GCC Students Choose This Programme',
                'gcc_reasons' => [
                    [
                        'title' => 'The mental health field is growing fast',
                        'text' => 'The UAE mental health market is valued at USD 1.5 billion in 2025 and projected to reach USD 2.7 billion by 2031, a 12% CAGR, per Ken Research, and the sector reports a shortage of qualified professionals.',
                        'icon' => 'trending-up',
                    ],
                    [
                        'title' => 'Psychology knowledge crosses industries',
                        'text' => 'HR, education, healthcare, business, marketing, customer experience, training, social services: the degree applies across all of them.',
                        'icon' => 'briefcase',
                    ],
                    [
                        'title' => 'Multicultural workplaces are the local context',
                        'text' => 'Professionals across the GCC work with teams representing different cultures, languages, and backgrounds, and psychological understanding supports stronger communication and workplace relationships.',
                        'icon' => 'globe',
                    ],
                    [
                        'title' => 'No career break required',
                        'text' => 'Flexible online study while living and working in the UAE, Saudi Arabia, Qatar, Oman, Bahrain, or Kuwait.',
                        'icon' => 'laptop',
                    ],
                    [
                        'title' => 'People-management skills, formalised',
                        'text' => 'A deeper understanding of motivation, behaviour, communication, and interpersonal relationships, which is what management roles keep screening for.',
                        'icon' => 'users',
                    ],
                    [
                        'title' => 'Postgraduate routes stay open',
                        'text' => 'After the bachelor\'s, graduates may explore MSc Psychology, organisational psychology, counselling-related programmes, human resource management, or MBA pathways, subject to university admission requirements. The GAU family here includes an MSc in Counselling Psychology.',
                        'icon' => 'route',
                    ],
                    [
                        'title' => 'Honest take',
                        'text' => 'The bachelor\'s is a foundation, not a licence. Regulated psychologist roles need postgraduate study, supervised training, and licensing that vary by country, and planning for those steps early makes the whole path smoother.',
                        'icon' => 'shield',
                    ],
                    [
                        'title' => 'Regulated psychology roles',
                        'text' => 'Some professional psychology occupations, including regulated roles such as psychologist, clinical psychologist, or counselling psychologist, normally require further postgraduate study, supervised professional training, and licensing depending on the country.',
                        'icon' => 'badge-check',
                    ],
                ],
                'fees' => [
                    ['title' => 'Programme Fees'],
                    ['title' => 'Scholarships'],
                    ['title' => 'Payment Options'],
                    ['title' => 'Written Breakdown'],
                    ['title' => 'Intake Offer'],
                ],
                'testimonials' => [],
                'reviews' => [],
            ]
        );

        $program->seo()->updateOrCreate([], [
            'meta_title' => 'BSc Psychology Online | Maverick Business Academy London',
            'meta_description' => 'Study BSc Psychology online and develop knowledge in human behaviour, cognitive psychology, social psychology, developmental psychology, mental health and psychological research.',
            'canonical_url' => null,
        ]);

        $this->command?->info("Seeded programme: {$program->title}");
    }

    /**
     * @param  list<string>  $titles
     * @return list<array{title: string}>
     */
    private function modules(array $titles): array
    {
        return array_map(fn (string $title) => ['title' => $title], $titles);
    }
}
