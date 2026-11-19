<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->update('mba_masters_hero.cta_secondary_label', fn () => 'Request Fee Plan');
        $this->migrator->update('mba_masters_hero.cta_secondary_url', fn () => '#mlp-fees');
        $this->migrator->add('mba_masters_hero.masthead_edition', 'Admissions / 2026');
        $this->migrator->add('mba_masters_hero.masthead_academy', 'Maverick Business Academy');
        $this->migrator->add('mba_masters_hero.masthead_location', 'UK · UAE · Global');
        $this->migrator->add('mba_masters_hero.folio_primary', 'Online MBA');
        $this->migrator->add('mba_masters_hero.folio_secondary', '20+ Specializations');

        $this->migrator->update('mba_masters_trust.stats', function ($stats) {
            $stats = $this->rows($stats);
            foreach ($stats as &$stat) {
                if (($stat['value'] ?? '') === '4.9/5') {
                    $stat['value'] = '4.9';
                }
            }
            unset($stat);

            return $stats;
        });

        $this->migrator->update('mba_masters_overview.intro', function ($intro) {
            return str_replace('for people who work', 'for working professionals', (string) $intro);
        });
        $this->migrator->update('mba_masters_overview.items', function ($items) {
            $items = $this->rows($items);
            foreach ($items as &$item) {
                $item['text'] = str_replace('then it is applied', 'then you apply it', (string) ($item['text'] ?? ''));
            }
            unset($item);

            return $items;
        });
        $this->migrator->add('mba_masters_overview.guide_label', 'MBA in UAE guide');
        $this->migrator->add('mba_masters_overview.guide_url', 'https://mbalondon.org.uk/mba-in-uae-complete-guide-for-working-professionals-in-2026/');

        $this->migrator->update('mba_masters_why.chapters', function ($chapters) {
            $chapters = $this->rows($chapters);
            foreach ($chapters as &$chapter) {
                $title = (string) ($chapter['title'] ?? '');
                if ($title === 'Learn without pausing your salary') {
                    $chapter['text'] = str_replace('your salary stays in place', 'your salary remains unchanged', (string) ($chapter['text'] ?? ''));
                }
                if ($title === 'Your certificate comes from the university itself') {
                    $chapter['text'] = 'The awarding university issues your certificate. It does not mention online study. We confirm the exact award wording in writing before you enroll.';
                }
                if ($title === 'Pay in AED instalments') {
                    $chapter['title'] = 'Pay in AED installments';
                    $chapter['text'] = 'Monthly AED installments may be available for selected programs. Your written quotation lists tuition, registration, VAT (where applicable), final-project charges, attestation charges, installments dates, and refund terms.';
                }
                if ($title === 'Head Office/Sharjah and London') {
                    $chapter['title'] = 'Head Office: Sharjah and London';
                    $chapter['text'] = str_replace('Counsellors', 'Counselors', (string) ($chapter['text'] ?? ''));
                }
            }
            unset($chapter);

            return $chapters;
        });

        $this->migrator->update('mba_masters_journey.steps', function ($steps) {
            $steps = $this->rows($steps);
            foreach ($steps as &$step) {
                $title = (string) ($step['title'] ?? '');
                if (str_contains($title, 'Share your CV')) {
                    $step['text'] = 'Send your CV, or just your LinkedIn profile: no documents yet, and no commitment.';
                }
                if (str_contains($title, 'Enrollment')) {
                    $step['text'] = str_replace('checked every written term', 'reviewed every written term', (string) ($step['text'] ?? ''));
                }
            }
            unset($step);

            return $steps;
        });

        $this->migrator->update(
            'mba_masters_mba.intro',
            fn () => 'More than 20+ specializations, 100% online, from institutions you can put on a CV. Maverick Business Academy London offers routes with Rushford Business School, a private Swiss business school and not a Swiss university; Girne American University in North Cyprus; the University for the Creative Arts in the UK with Rushford on the Dual MBA; the University of Wolverhampton; and the University of the West of Scotland for the MBA in International Business. We confirm the award pathway and current authorization in writing before you enroll. The Dual MBA, UCA with Rushford, is that written pathway. Maverick does not state that UCA lists Rushford as a direct campus partner.'
        );
        $this->migrator->update('mba_masters_mba.tabs', function ($tabs) {
            $tabs = $this->rows($tabs);
            foreach ($tabs as $tabIndex => $tab) {
                $universities = $tab['universities'] ?? [];
                foreach ($universities as $universityIndex => $university) {
                    $programs = $university['programs'] ?? [];
                    foreach ($programs as $programIndex => $program) {
                        if (str_contains((string) ($program['title'] ?? ''), 'Data Science')) {
                            $programs[$programIndex]['title'] = 'MBA in Data Science/Analytics Management';
                        }
                    }
                    $universities[$universityIndex]['programs'] = $programs;
                }
                $tabs[$tabIndex]['universities'] = $universities;
            }

            return $tabs;
        });

        $this->migrator->update('mba_masters_masters.intro', function ($intro) {
            return str_replace(['counselling', 'Counselling'], ['counseling', 'Counseling'], (string) $intro);
        });
        $this->migrator->update('mba_masters_masters.universities', function ($universities) {
            $universities = $this->rows($universities);
            foreach ($universities as $universityIndex => $university) {
                $programs = $university['programs'] ?? [];
                foreach ($programs as $programIndex => $program) {
                    $programs[$programIndex]['title'] = str_replace(
                        'Counselling',
                        'Counseling',
                        (string) ($program['title'] ?? '')
                    );
                }
                $universities[$universityIndex]['programs'] = $programs;
            }

            return $universities;
        });

        $this->migrator->update('mba_masters_class.intro', function ($intro) {
            return str_replace(', because that is who is on the call', ' because that is who is on the call', (string) $intro);
        });
        $this->migrator->update('mba_masters_class.regions', function ($regions) {
            $regions = $this->rows($regions);
            foreach ($regions as &$region) {
                $region['note'] = str_replace('About 50% the cohort', 'About 50% of the cohort', (string) ($region['note'] ?? ''));
            }
            unset($region);

            return $regions;
        });
        $this->migrator->add('mba_masters_class.class_year_lead', 'Class of 2025:');
        $this->migrator->add('mba_masters_class.class_year_strong', 'Built for the GCC Region');
        $this->migrator->add('mba_masters_class.class_year_body', 'A Maverick cohort of founders, bankers, government specialists, and senior operators who keep working while they study.');
        $this->migrator->add('mba_masters_class.class_year_center', 'MBA - 2025');
        $this->migrator->add('mba_masters_class.global_heading', 'Global Cohorts');
        $this->migrator->add('mba_masters_class.global_line', 'Classmates join from the Gulf and from countries well beyond it.');

        $this->migrator->update(
            'mba_masters_fees.intro',
            fn () => 'Fees depend on the awarding institution, program, entry route, and intake. Totals usually range from AED 16,000 to 40,000, and the exact figure is confirmed in writing. Your written quotation lists tuition, registration, VAT (where applicable), final-project charges, attestation charges, installment dates, and refund terms.'
        );
        $this->migrator->update(
            'mba_masters_fees.note',
            fn () => 'Monthly AED installments may be available for selected programs. We will always provide written confirmation before any payment.'
        );
        $this->migrator->update('mba_masters_fees.rows', function ($rows) {
            $rows = $this->rows($rows);
            foreach ($rows as &$row) {
                $row['payment'] = str_replace('instalment', 'installment', (string) ($row['payment'] ?? ''));
            }
            unset($row);

            return $rows;
        });
        $this->migrator->update('mba_masters_fees.blocks', function ($blocks) {
            $blocks = $this->rows($blocks);
            foreach ($blocks as &$block) {
                $block['title'] = str_replace('instalment', 'installment', (string) ($block['title'] ?? ''));
                $block['text'] = str_replace('instalment', 'installment', (string) ($block['text'] ?? ''));
                if (($block['title'] ?? '') === 'What your written quotation shows') {
                    $block['text'] = 'Your quotation lists tuition, registration, and assessment charges; VAT where it applies; dissertation or final-project charges; graduation, transcript, or attestation charges; installments dates; and refund terms.';
                }
            }
            unset($block);

            return $blocks;
        });
        $this->migrator->add('mba_masters_fees.banner_label', 'Fee structure starts from');
        $this->migrator->add('mba_masters_fees.banner_value', 'AED 16,000–40,000*');

        $this->migrator->update(
            'mba_masters_career.intro',
            fn () => 'Recent outcomes, told. Each of these graduates kept a full-time job and studied in the evening.'
        );
        $this->migrator->add('mba_masters_career.badge_kicker', 'GCC');
        $this->migrator->add('mba_masters_career.badge_title', 'Online MBA for professionals across the GCC');
        $this->migrator->add('mba_masters_career.badge_line', 'Study from home, on a schedule that fits a full-time job.');

        $this->migrator->update(
            'mba_masters_alumni.intro',
            fn () => 'Alumni have gone on to roles across the Gulf. Ask us for a reference in your field before you decide. We can arrange that conversation once the graduate has agreed.'
        );
        $this->migrator->update('mba_masters_alumni.trust_line', function ($line) {
            return str_replace('alumni employers', 'alum employers', (string) $line);
        });

        $this->migrator->update(
            'mba_masters_partners.intro',
            fn () => 'Maverick Business Academy London offers Master\'s programs awarded by the institutions named below. Rushford Business School is a private Swiss business school, not a Swiss university, and we confirm its status in writing for each program. The routes also include Girne American University, the University for the Creative Arts, the University of Wolverhampton, and the University of the West of Scotland. We confirm the award pathway and current authorization in writing before you enroll. The Dual MBA, UCA with Rushford, is that written pathway. Maverick does not list Rushford as a direct campus partner.'
        );

        $this->migrator->update('mba_masters_compare.rows', function ($rows) {
            $rows = $this->rows($rows);
            foreach ($rows as &$row) {
                if (($row['criterion'] ?? '') === 'Networking') {
                    $row['online'] = str_replace('alumni job', 'alums job', (string) ($row['online'] ?? ''));
                }
            }
            unset($row);

            return $rows;
        });

        $this->migrator->update('mba_masters_faq.items', function ($items) {
            $items = $this->rows($items);
            $answers = [
                'Are these degrees recognized in the UAE?' => 'A fully online foreign degree may be considered for recognition, but approval is not automatic. It depends on the awarding institution, the program, the documents, and the intended use, including an employer, a government role, further study, or a professional license. Advisors can explain the documents you can request. The relevant authority makes the decision. Online study is not mentioned on the certificate, and you confirm that wording in writing before you enroll.',
                'How much does an MBA cost in the UAE?' => 'Fees depend on the awarding institution, program, entry route, and intake. Your written quotation lists tuition, registration, VAT (where applicable), final-project charges, attestation charges, installments dates, and refund terms. Monthly AED installments may be available for selected programs.',
                'Can I study 100% online from Dubai or Abu Dhabi?' => 'You can study from Dubai, Abu Dhabi, Sharjah, Al Ain, or elsewhere in the GCC. Delivery format, class times, and recordings vary by program. These online routes don\'t require no student visa.',
                'Do I need a student visa?' => 'No. You study 100% online from where you live, so you don\'t need no student visa, and your residence visa stays as it is for professionals in the GCC, which removes the paperwork a foreign campus used to require.',
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

        $this->migrator->add('mba_masters_testimonials.film_label', 'Experience in motion');
        $this->migrator->add('mba_masters_testimonials.film_heading', 'See the Maverick journey in motion.');
        $this->migrator->add('mba_masters_testimonials.film_body', 'A closer look at the people, ambition and learning culture behind the next chapter.');
        $this->migrator->add('mba_masters_testimonials.film_play_label', 'Play film');

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
