<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->update(
            'mba_masters_hero.eyebrow',
            fn () => 'Flexible international study for UAE and GCC professionals'
        );
        $this->migrator->update(
            'mba_masters_hero.headline',
            fn () => 'Online MBA and Master\'s Degrees for UAE and GCC Professionals'
        );
        $this->migrator->update(
            'mba_masters_hero.subheading',
            fn () => 'Compare flexible postgraduate programs designed for working professionals. Review the awarding institution, study format, duration, entry requirements, and total fees before choosing your route. Study from your current location with guidance from our Sharjah-based admissions team. Availability and delivery format vary by program.'
        );
        $this->migrator->update('mba_masters_hero.form_title', fn () => 'Get the program and fee guide');
        $this->migrator->update('mba_masters_hero.cta_primary_label', fn () => 'Check Your Eligibility');

        $this->migrator->update(
            'mba_masters_trust.label',
            fn () => 'Rated 4.9 out of 5 by people who finished the degree'
        );
        $this->migrator->update(
            'mba_masters_trust.quote',
            fn () => 'Before you enroll, we confirm the points below in writing. Maverick supports admission and study through selected education partners. The awarding institution and qualification route differ by program.'
        );
        $this->migrator->update('mba_masters_trust.quote_attribution', fn () => '');
        $this->migrator->update('mba_masters_trust.stats', fn () => [
            ['value' => '', 'label' => 'The institution issuing your final award'],
            ['value' => '', 'label' => 'The exact program title and study format'],
            ['value' => '', 'label' => 'Entry requirements and expected duration'],
            ['value' => '', 'label' => 'Tuition fees and additional charges'],
            ['value' => '', 'label' => 'The current intake and application deadline'],
            ['value' => '', 'label' => 'Available documentation for qualification recognition'],
        ]);

        $this->migrator->update('mba_masters_overview.items', function ($items) {
            $items = $this->rows($items);
            foreach ($items as &$item) {
                $text = (string) ($item['text'] ?? '');
                $text = preg_replace('/\s*Several graduates have taken that project straight into a performance review\.?/u', '', $text) ?? $text;
                $item['text'] = trim($text);
            }
            unset($item);

            return $items;
        });

        $this->migrator->update('mba_masters_why.chapters', function ($chapters) {
            $chapters = $this->rows($chapters);
            $replacements = [
                'Learn without pausing your salary' => 'Campus programs can ask you to quit, relocate, or study on a fixed weekday. Where a program includes live classes, they are usually in the evening, from where you already are. Delivery format varies by program, and your salary stays in place.',
                'Your certificate comes from the university itself' => 'The awarding university issues your certificate. Online study is not mentioned on it. The exact award wording is confirmed in writing before you enroll.',
                'Pay in AED instalments' => 'Monthly AED instalments may be available for selected programs. Your written quotation lists tuition, registration, VAT where it applies, final-project charges, attestation charges, instalment dates, and refund terms.',
                'A recognized route, explained honestly' => 'A fully online foreign degree may be considered for recognition, but approval is not automatic. It depends on the awarding institution, the program, the documents, and the use you have in mind, such as an employer, a government role, further study, or a professional license. Advisors can explain the documents you can request. The relevant authority makes the decision.',
            ];
            foreach ($chapters as &$chapter) {
                $title = (string) ($chapter['title'] ?? '');
                if (isset($replacements[$title])) {
                    $chapter['text'] = $replacements[$title];
                }
            }
            unset($chapter);

            return $chapters;
        });

        $this->migrator->update(
            'mba_masters_journey.intro',
            fn () => 'Ask about the next available intake and application deadline. Most eligibility reviews can be completed quickly, but admission and enrollment timelines vary by institution.'
        );
        $this->migrator->update('mba_masters_journey.steps', fn () => [
            [
                'title' => 'Share your CV or LinkedIn profile',
                'text' => 'Send your CV, or just your LinkedIn profile. No documents yet, and no commitment.',
            ],
            [
                'title' => 'Receive a program shortlist',
                'text' => 'The shortlist is based on your qualifications and experience.',
            ],
            [
                'title' => 'Review the written terms',
                'text' => 'Check the awarding institution, delivery format, total fees, and recognition considerations before you decide.',
            ],
            [
                'title' => 'Enrollment and Orientation',
                'text' => 'Submit the required admission documents, then accept your offer and complete enrollment after you have checked every written term.',
            ],
            [
                'title' => 'Zoom Classes Begin',
                'text' => 'Where the program includes live classes, they are usually in the evening, and a recording is available when the university provides one.',
            ],
        ]);

        $this->migrator->update(
            'mba_masters_mba.intro',
            fn () => 'More than 30 specializations, 100% online, from institutions you can put on a CV. Routes include Rushford Business School, a private Swiss business school and not a Swiss university; Girne American University in North Cyprus; the University for the Creative Arts in the UK with Rushford on the Dual MBA; the University of Wolverhampton; and the University of the West of Scotland for the MBA in International Business. The award pathway and current authorization are confirmed in writing before you enroll. The Dual MBA names UCA with Rushford as that written pathway. This page does not claim that UCA lists Rushford as a direct campus partner.'
        );

        $this->migrator->update(
            'mba_masters_fees.intro',
            fn () => 'Fees depend on the awarding institution, program, entry route, and intake. No fee range is advertised here. Your written quotation lists tuition, registration, VAT where it applies, final-project charges, attestation charges, instalment dates, and refund terms.'
        );
        $this->migrator->update(
            'mba_masters_fees.note',
            fn () => 'Monthly AED instalments may be available for selected programs. Written confirmation always comes before any payment.'
        );
        $this->migrator->update('mba_masters_fees.rows', function ($rows) {
            $rows = $this->rows($rows);
            foreach ($rows as &$row) {
                if (str_contains(mb_strtolower((string) ($row['payment'] ?? '')), 'instalment')) {
                    $row['payment'] = 'AED instalments may be available';
                }
            }
            unset($row);

            return $rows;
        });
        $this->migrator->update('mba_masters_fees.blocks', fn () => [
            [
                'title' => 'What your written quotation shows',
                'text' => 'Your quotation lists tuition, registration and assessment charges, VAT where it applies, dissertation or final-project charges, graduation, transcript or attestation charges, instalment dates, and refund terms.',
            ],
            [
                'title' => 'Monthly AED instalments',
                'text' => 'Monthly AED instalments may be available for selected programs. The dates and refund terms are part of the written quotation.',
            ],
            [
                'title' => 'Scholarships and early-bird reductions',
                'text' => 'Ask whether scholarship support or an early-bird reduction applies to your profile and intake. Eligibility is confirmed in writing.',
            ],
        ]);

        $this->migrator->update(
            'mba_masters_alumni.intro',
            fn () => 'Alumni have gone on to roles across the Gulf. Ask us for a reference in your field before you decide. We can arrange that conversation when the graduate has agreed to it.'
        );

        $this->migrator->update(
            'mba_masters_partners.intro',
            fn () => 'Programs on this page are awarded by the institutions named below. Rushford Business School is a private Swiss business school, not a Swiss university, and its status is confirmed in writing for each program. Girne American University, the University for the Creative Arts, the University of Wolverhampton, and the University of the West of Scotland remain listed here. The award pathway and current authorization are confirmed in writing before you enroll. The Dual MBA, UCA with Rushford, is that written pathway. This page does not claim that UCA lists Rushford as a direct campus partner.'
        );

        $this->migrator->update('mba_masters_learning.points', function ($points) {
            $points = $this->rows($points);
            foreach ($points as &$point) {
                if (($point['title'] ?? '') === 'Live evening classes') {
                    $point['text'] = 'Where the program includes live classes, they are usually in the evening, and a recording is available when the university provides one.';
                }
            }
            unset($point);

            return $points;
        });

        $this->migrator->update(
            'mba_masters_compare.intro',
            fn () => 'If you have weighed a part-time MBA on campus against a 100% online MBA, this comparison covers the practical differences. The certificate wording is confirmed in writing for each program.'
        );
        $this->migrator->update('mba_masters_compare.rows', function ($rows) {
            $rows = $this->rows($rows);
            foreach ($rows as &$row) {
                if (($row['criterion'] ?? '') === 'Total fees') {
                    $row['online'] = 'Confirmed in a written quotation';
                    $row['traditional'] = 'Varies by campus and program';
                }
            }
            unset($row);

            return $rows;
        });
        $this->migrator->update('mba_masters_compare.blocks', function ($blocks) {
            $blocks = $this->rows($blocks);
            foreach ($blocks as &$block) {
                if (($block['title'] ?? '') === 'The same award, either way') {
                    $block['title'] = 'The certificate wording';
                    $block['text'] = 'Online study is not mentioned on the degree. Confirm the exact award wording in writing before you enroll.';
                }
            }
            unset($block);

            return $blocks;
        });

        $this->migrator->update('mba_masters_faq.items', function ($items) {
            $items = $this->rows($items);
            $answers = [
                'Are these degrees recognized in the UAE?' => 'A fully online foreign degree may be considered for recognition, but approval is not automatic. It depends on the awarding institution, the program, the documents, and the intended use, including an employer, a government role, further study, or a professional license. Advisors can explain the documents you can request. The relevant authority makes the decision. Online study is not mentioned on the certificate, and that wording is confirmed in writing before you enroll.',
                'How much does an MBA cost in the UAE?' => 'Fees depend on the awarding institution, program, entry route, and intake. Your written quotation lists tuition, registration, VAT where it applies, final-project charges, attestation charges, instalment dates, and refund terms. Monthly AED instalments may be available for selected programs.',
                'Can I study 100% online from Dubai or Abu Dhabi?' => 'You can study from Dubai, Abu Dhabi, Sharjah, Al Ain, or elsewhere in the GCC. Delivery format, class times, and recordings vary by program. There is no student visa for these online routes.',
                'When is the next intake?' => 'Ask about the next available intake and application deadline. Admission and enrollment timelines vary by institution.',
                'How many hours per week does a part-time MBA need?' => 'Hours vary by program. Many students plan for evening classes, self-study, and assessments across the week. Your advisor confirms the expected load for the route you choose.',
                'Will my employer in the UAE accept an online degree?' => 'Employers look at the university that awards the degree, and the certificate does not mention that you studied online. We confirm the award details in writing before you enroll. Sponsorship depends on your employer. If HR needs paperwork, we prepare it with you.',
                'Can I study from Saudi Arabia, Oman, or Qatar?' => 'Yes. Students also study from Saudi Arabia, Oman, Qatar, and further afield. Support runs on Gulf time, and each intake has its own WhatsApp group.',
            ];
            foreach ($items as &$item) {
                $question = (string) ($item['question'] ?? '');
                if (isset($answers[$question])) {
                    $item['answer'] = $answers[$question];
                }
            }
            unset($item);

            return $items;
        });

        $this->migrator->update(
            'mba_masters_final.heading',
            fn () => 'Choose Your Program With the Facts in Writing'
        );
        $this->migrator->update('mba_masters_final.form_title', fn () => 'Get the program and fee guide');
        $this->migrator->update(
            'mba_masters_final.intro',
            fn () => 'Share your CV or LinkedIn profile and receive a personalized program shortlist covering the award, study format, entry requirements, duration, fees, and next available intake. No obligation. Review the complete program and fee information before making your decision. You can also connect with us through the <a href="/contact">contact page</a>.'
        );
        $this->migrator->update('mba_masters_final.cta_primary_label', fn () => 'Check My Eligibility');
        $this->migrator->update('mba_masters_final.cta_secondary_label', fn () => 'Speak to a Sharjah Advisor');
        $this->migrator->update('mba_masters_final.cta_secondary_url', fn () => '/contact');

        $this->migrator->update(
            'mba_masters_seo.meta_title',
            fn () => 'Online MBA & Master\'s Degrees UAE | Maverick'
        );
        $this->migrator->update(
            'mba_masters_seo.meta_description',
            fn () => 'Compare flexible online MBA and Master\'s programs for UAE and GCC professionals. Review awards, fees, study formats, entry requirements, and intakes.'
        );

        if (function_exists('app') && app()->bound(\Illuminate\Contracts\Console\Kernel::class)) {
            try {
                \Illuminate\Support\Facades\Artisan::call('settings:clear-cache');
            } catch (\Throwable) {
            }
        }
    }

    private function rows(mixed $value): array
    {
        $rows = json_decode(json_encode($value ?? []), true);

        return is_array($rows) ? $rows : [];
    }
};
