<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Research basis: official Maverick course and homepage copy, plus the
        // public academy listing reviewed on 2026-08-25. Keep route-specific
        // claims qualified because programme and awarding-body details vary.
        $this->migrator->update(
            'mba_masters_hero.subheading',
            fn () => 'Online, hybrid and part-time MBA and Master\'s pathways for professionals in the UAE and beyond. Compare specializations, study modes, entry routes and university partners with admissions.'
        );

        $this->migrator->update(
            'mba_masters_overview.intro',
            fn () => 'Choose a business pathway that fits your experience, goals and working life — then confirm the right university route with admissions.'
        );
        $this->migrator->update('mba_masters_overview.items', fn () => [
            [
                'title' => 'Flexible delivery options',
                'text' => 'Course pages list online, hybrid and part-time formats across different MBA and Master\'s routes.',
            ],
            [
                'title' => 'Practical assessment',
                'text' => 'Assignments, projects and case studies help connect academic ideas with workplace questions.',
            ],
            [
                'title' => 'Academic guidance',
                'text' => 'Assessment feedback, module support and structured guidance are part of the published learning experience.',
            ],
            [
                'title' => 'Business specializations',
                'text' => 'Explore themes including leadership, marketing, finance, human resources, operations and entrepreneurship.',
            ],
            [
                'title' => 'Online study access',
                'text' => 'Access course materials and learning support through the online platform that matches your selected route.',
            ],
        ]);

        $this->migrator->update(
            'mba_masters_why.intro',
            fn () => 'A practical route for professionals comparing flexible delivery, applied assessment, academic support and international study options.'
        );
        $this->migrator->update('mba_masters_why.chapters', fn () => [
            [
                'title' => 'Flexible delivery',
                'text' => 'Online, hybrid and part-time formats are listed across Maverick\'s current course pages. Confirm the delivery mode for your chosen route.',
                'anchor' => null,
            ],
            [
                'title' => 'Applied assessment',
                'text' => 'Assignments, projects and case studies give learners a structured way to apply concepts to business situations.',
                'anchor' => null,
            ],
            [
                'title' => 'Academic guidance',
                'text' => 'Published course information describes assessor support, feedback and access to learning materials throughout the programme.',
                'anchor' => '#mlp-journey',
            ],
            [
                'title' => 'Specialization choice',
                'text' => 'Depending on the route, study themes include finance, marketing, leadership, human resources, operations, logistics and entrepreneurship.',
                'anchor' => '#mlp-mba',
            ],
            [
                'title' => 'International routes',
                'text' => 'Partner and awarding-body details differ by programme. Admissions can explain the university, qualification and progression attached to your shortlist.',
                'anchor' => '#mlp-partners',
            ],
            [
                'title' => 'Professional community',
                'text' => 'Masterclasses, peer learning and alumni activity add a professional layer beyond the core assessment journey.',
                'anchor' => null,
            ],
        ]);

        $this->migrator->update(
            'mba_masters_journey.intro',
            fn () => 'A clear route from your first enquiry to enrolment, orientation and the start of your chosen programme.'
        );
        $this->migrator->update('mba_masters_journey.steps', fn () => [
            [
                'title' => 'Enquiry',
                'text' => 'Share your goals, academic background, experience and preferred start window with admissions.',
            ],
            [
                'title' => 'Profile review',
                'text' => 'The team reviews your background and explains the entry route, documents and next decision points.',
            ],
            [
                'title' => 'Choose your route',
                'text' => 'Compare programme focus, university partner, delivery mode, duration and final qualification.',
            ],
            [
                'title' => 'Registration',
                'text' => 'Complete the required documents and enrolment steps for the route you select.',
            ],
            [
                'title' => 'Orientation',
                'text' => 'Understand the learning platform, assessment expectations and available support before study begins.',
            ],
            [
                'title' => 'Start learning',
                'text' => 'Begin your modules, assignments, projects and case-study work with the support of your learning route.',
            ],
        ]);

        $this->migrator->update(
            'mba_masters_class.heading',
            fn () => 'The cohort behind the classroom'
        );
        $this->migrator->update(
            'mba_masters_class.intro',
            fn () => 'The Executive MBA Class Profile 2025 describes a cohort of 281 participants with an average in-class work experience of 13 years 2 months.'
        );
        $this->migrator->update(
            'mba_masters_class.audience',
            fn () => 'Professionals bringing experience from different sectors into a flexible learning room.'
        );

        $this->migrator->update(
            'mba_masters_career.heading',
            fn () => 'Career directions after an MBA or Master\'s'
        );
        $this->migrator->update(
            'mba_masters_career.intro',
            fn () => 'Maverick\'s published pathways connect learning in finance, marketing, operations, project management and leadership to practical career directions. Outcomes depend on your experience, chosen route and employer.'
        );
        $this->migrator->update('mba_masters_career.stories', fn () => [
            [
                'name' => 'Finance and commercial management',
                'country' => 'Career direction',
                'program' => 'MBA / MSc Accounting & Finance',
                'previous_role' => 'Accounting, finance or analytical foundations',
                'current_role' => 'Finance Manager · Commercial Manager',
                'quote' => 'A route for building financial planning, reporting and strategic decision-making capability. Role examples are illustrative, not guaranteed outcomes.',
                'portrait' => 'assets/images/homepage/business.jpg',
                'portrait_asset_id' => null,
            ],
            [
                'name' => 'Marketing and growth',
                'country' => 'Career direction',
                'program' => 'MBA / Master\'s Marketing pathway',
                'previous_role' => 'Marketing, sales or customer-facing experience',
                'current_role' => 'Marketing Manager · Growth Lead',
                'quote' => 'A route for developing market, brand, customer and commercial thinking across changing business environments.',
                'portrait' => 'assets/images/edutainment/learning-beyond.png',
                'portrait_asset_id' => null,
            ],
            [
                'name' => 'Operations and supply chain',
                'country' => 'Career direction',
                'program' => 'MBA Logistics & Supply Chain pathway',
                'previous_role' => 'Operations, logistics or project experience',
                'current_role' => 'Project Manager · Supply Chain Manager',
                'quote' => 'A route for connecting systems, operations and logistics knowledge to the decisions that keep organisations moving.',
                'portrait' => 'assets/images/programs/enquire-seminar.jpg',
                'portrait_asset_id' => null,
            ],
        ]);

        $this->migrator->update(
            'mba_masters_alumni.heading',
            fn () => 'An alumni network that travels with you'
        );
        $this->migrator->update(
            'mba_masters_alumni.intro',
            fn () => 'Connect with a professional community shaped by business, leadership, education, IT and other career paths across the UAE and beyond.'
        );
        $this->migrator->update(
            'mba_masters_alumni.trust_line',
            fn () => 'A professional network built through study, masterclasses and shared milestones.'
        );

        $this->migrator->update(
            'mba_masters_partners.intro',
            fn () => 'Explore current partner and awarding routes. The university, qualification title and delivery format depend on the programme you select.'
        );
        $this->migrator->update(
            'mba_masters_partners.trust_line',
            fn () => 'Partner and awarding-body details are confirmed for your selected route before enrolment.'
        );

        $this->migrator->update(
            'mba_masters_testimonials.heading',
            fn () => 'Hear from Maverick alumni'
        );
        $this->migrator->update(
            'mba_masters_testimonials.intro',
            fn () => 'Published alumni perspectives from Maverick\'s programme and learning community.'
        );
        $this->migrator->update('mba_masters_testimonials.items', fn () => [
            [
                'name' => 'Mohammad Taha',
                'role' => 'MBA · University of Gloucestershire, UK',
                'quote' => 'I have so far had a pleasant experience at Maverick Business Academy. Mr. Fazil was kind enough to guide me through my MBA degree and welcome me into the academy. The classes that are running on Saturdays by Prof Khan are informative, beneficial and actually fun! I recommend this place to whoever\'s looking for an MBA degree.',
                'photo' => null,
                'photo_asset_id' => null,
            ],
            [
                'name' => 'Sanika',
                'role' => 'MBA Business Administration',
                'quote' => 'I had a great learning experience with Maverick Business Academy. The one-month course was well-structured, and the instructors were knowledgeable and supportive. I gained valuable insights and practical skills that will benefit my career. Highly recommend. Looking forward to my MBA.',
                'photo' => null,
                'photo_asset_id' => null,
            ],
            [
                'name' => 'Jack Boath',
                'role' => 'MSc Sustainability and Environmental Management',
                'quote' => 'Maverick Business Academy London is an outstanding institution that truly delivers on its promise of quality education and professional development. The study programs are well-structured, industry-relevant and designed to bridge the gap between academia and real-world applications.',
                'photo' => null,
                'photo_asset_id' => null,
            ],
        ]);

        $this->migrator->update(
            'mba_masters_compare.intro',
            fn () => 'Compare the practical trade-offs between flexible online study and a traditional campus route before you choose.'
        );
        $this->migrator->update('mba_masters_compare.rows', fn () => [
            [
                'criterion' => 'Schedule',
                'online' => 'Online, hybrid or part-time format depending on the selected route',
                'traditional' => 'Campus timetable set by the institution',
            ],
            [
                'criterion' => 'Location',
                'online' => 'Study from the UAE or another location where the route permits',
                'traditional' => 'Attend the institution\'s physical campus',
            ],
            [
                'criterion' => 'Duration',
                'online' => 'Published MBA routes commonly list 10–15 months; confirm your programme',
                'traditional' => 'Duration varies by institution and study pattern',
            ],
            [
                'criterion' => 'Cost of living',
                'online' => 'May reduce relocation and daily campus costs',
                'traditional' => 'Budget for travel, housing and campus attendance where relevant',
            ],
            [
                'criterion' => 'Career continuity',
                'online' => 'Designed to make study alongside work more practical',
                'traditional' => 'Attendance requirements may be less flexible for full-time workers',
            ],
            [
                'criterion' => 'Awarding body',
                'online' => 'Confirmed by the university and programme route you select',
                'traditional' => 'Confirmed by the campus institution and selected programme',
            ],
        ]);

        $this->migrator->update('mba_masters_faq.items', fn () => [
            [
                'question' => 'Who awards the qualification?',
                'answer' => 'The awarding university or body depends on the programme route. Admissions will confirm the university, qualification title, delivery format and progression details for your selected course before enrolment.',
            ],
            [
                'question' => 'Can I study while working full-time in the UAE?',
                'answer' => 'Many Maverick course pages list online, hybrid or part-time delivery. Confirm the timetable, attendance pattern and support available for your chosen route with admissions.',
            ],
            [
                'question' => 'How long do the MBA and Master\'s pathways take?',
                'answer' => 'Published course pages list 10–12 months for some accelerated MBA routes, 12–15 months for several MBA routes and 15–18 months for some MSc pathways. Your admissions team will confirm the exact duration.',
            ],
            [
                'question' => 'What are the entry requirements?',
                'answer' => 'Requirements vary by route. Official course pages reference recognised bachelor\'s degrees, postgraduate or Level 7 qualifications and relevant experience in different combinations. Share your profile for a route-specific eligibility check.',
            ],
            [
                'question' => 'What are the current fees?',
                'answer' => 'A public listing currently shows an AED 16,000–35,000 range for Maverick Business Academy. Official course pages do not publish one universal fee for every route, so request the current programme fee sheet and confirm VAT, instalments, exemptions and any scholarship terms before payment.',
            ],
            [
                'question' => 'Is the learning fully online?',
                'answer' => 'Some routes are online while others list hybrid or part-time delivery. The exact mode, timetable and any approved centre requirement depend on the programme you choose and should be confirmed before registration.',
            ],
        ]);

        $this->migrator->update(
            'mba_masters_fees.heading',
            fn () => 'Online MBA & Master\'s fees in the UAE'
        );
        $this->migrator->update(
            'mba_masters_fees.intro',
            fn () => 'Use the published range as a starting point, then confirm the exact university route, entry point, exemptions and payment schedule with admissions.'
        );
        $this->migrator->update(
            'mba_masters_fees.note',
            fn () => 'Public listing reference: AED 16,000–35,000 for Maverick Business Academy. Official course pages describe route-specific duration and delivery but do not publish one universal tuition fee; confirm the current fee sheet, VAT, instalments and approved exemptions before payment.'
        );
        $this->migrator->update('mba_masters_fees.rows', fn () => [
            [
                'program' => 'Online MBA pathways',
                'duration' => '10–15 months, depending on route',
                'mode' => 'Online · Hybrid · Part-time',
                'fee' => 'AED 16,000–35,000*',
                'payment' => 'Registration + agreed instalments',
            ],
            [
                'program' => 'Executive MBA routes',
                'duration' => '12–18 months, depending on route',
                'mode' => 'Online · Hybrid · Part-time',
                'fee' => 'Route-specific*',
                'payment' => 'Current fee sheet on request',
            ],
            [
                'program' => 'Master\'s and MSc pathways',
                'duration' => '8–18 months, depending on entry point',
                'mode' => 'Online · Hybrid · Part-time',
                'fee' => 'Route-specific*',
                'payment' => 'University and pathway confirmation',
            ],
            [
                'program' => 'LLM and university-specific routes',
                'duration' => 'As listed by the awarding university',
                'mode' => 'Confirm by programme',
                'fee' => 'Route-specific*',
                'payment' => 'Current fee sheet on request',
            ],
        ]);
    }
};
