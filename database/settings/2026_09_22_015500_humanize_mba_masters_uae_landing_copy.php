<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->update(
            'mba_masters_hero.subheading',
            fn () => 'You keep your job, your salary, and the city you live in. The degree comes to you: a 100% online MBA or Master\'s from Switzerland, North Cyprus, or the UK, studied in the evenings from the UAE. The September 2026 intake is open, and seats are confirmed in the order we receive payment.'
        );

        $this->migrator->update(
            'mba_masters_trust.label',
            fn () => 'Rated 4.9 out of 5 on Trustpilot by people who finished the degree'
        );
        $this->migrator->update('mba_masters_trust.stats', function ($stats) {
            $stats = json_decode(json_encode($stats ?? []), true) ?: [];
            foreach ($stats as &$stat) {
                $label = (string) ($stat['label'] ?? '');
                if (str_starts_with(mb_strtolower($label), 'average student rating')) {
                    $stat['label'] = 'Average student rating';
                }
                if (str_starts_with(mb_strtolower($label), 'online.')) {
                    $stat['label'] = 'Online. No visa. No relocation.';
                }
            }
            unset($stat);

            return $stats;
        });

        $this->migrator->update('mba_masters_overview.label', fn () => 'Program Overview');
        $this->migrator->update('mba_masters_overview.heading', fn () => 'MBA in Dubai: What You Actually Get');
        $this->migrator->update(
            'mba_masters_overview.intro',
            fn () => 'Five things, in plain language. This is an MBA in Dubai for people who work, described the way our students talk about it after a term or two.'
        );
        $this->migrator->update('mba_masters_overview.items', fn () => [
            [
                'title' => 'A learning community that supports you',
                'text' => 'Your classmates are founders, bank managers, and government specialists from the UAE, Saudi Arabia, Oman, and Qatar. The WhatsApp group that helps you through assignments often becomes the network you call after graduation. It happens on every intake, and it costs nothing extra.',
            ],
            [
                'title' => 'ASK Quotient Development',
                'text' => 'Attitude, Skills, and Knowledge. Employers look for all three, and most courses teach only one. Your success coach tracks all three across the degree, so the change shows up in your work as well as on the certificate.',
            ],
            [
                'title' => 'Skills-based MBA learning',
                'text' => 'Assignments look like the work you already do: a business plan, a market-entry analysis, or a review of how your team is run. You hand in work you can use on a Monday morning, and your manager often notices before you graduate.',
            ],
            [
                'title' => 'Knowledge-based MBA learning',
                'text' => 'The theory comes from your university\'s faculty, then it is applied to the Gulf through regional cases, short readings, and frameworks you can actually use. Recordings cover the evenings when work comes first.',
            ],
            [
                'title' => 'Real-world case studies',
                'text' => 'The cases come from businesses that are operating now, and the final project takes on a problem inside your own company. Several graduates have taken that project straight into a performance review.',
            ],
        ]);
        $this->migrator->update('mba_masters_overview.cta_primary_label', fn () => 'Explore the MBA Routes');
        $this->migrator->update('mba_masters_overview.cta_secondary_label', fn () => 'Ask an Advisor');

        $this->migrator->update('mba_masters_why.label', fn () => 'Why Choose Maverick');
        $this->migrator->update(
            'mba_masters_why.heading',
            fn () => 'Why an MBA for Working Professionals in the UAE Makes Sense Right Now'
        );
        $this->migrator->update(
            'mba_masters_why.intro',
            fn () => 'A Master\'s with Maverick is not a break from your life. It is a practical way to work towards a bigger role and a clearer salary conversation, while your salary keeps coming in.'
        );
        $this->migrator->update('mba_masters_why.chapters', fn () => [
            [
                'title' => 'Learn without pausing your salary',
                'text' => 'Campus programmes ask you to quit, relocate, or wait for Saturday. This one runs live on Tuesday and Thursday evenings, from where you already are. You can use what you learned the next morning. That is what a flexible MBA is for: your salary stays in place.',
                'anchor' => null,
            ],
            [
                'title' => 'Your certificate comes from the university itself',
                'text' => 'The awarding university issues your certificate, the same certificate it gives its on-campus students. Online study is not mentioned on it. The degree travels across the GCC and beyond.',
                'anchor' => '#mlp-partners',
            ],
            [
                'title' => 'Built for promotion and transition',
                'text' => 'The modules cover what employers in the UAE look for: leadership, finance, operations, and strategy. The final project uses a live problem from your own workplace, so you can show that you turn theory into results.',
                'anchor' => '#mlp-career',
            ],
            [
                'title' => 'Pay in AED instalments',
                'text' => 'A campus MBA in cities across the UAE usually costs AED 80,000 to 200,000. Most programmes on this page total AED 16,000 to 40,000, paid monthly in dirhams and timed to your salary date. The degree fits your cash flow from the first month.',
                'anchor' => '#mlp-fees',
            ],
            [
                'title' => 'A recognised route, explained honestly',
                'text' => 'You study 100% online from the UAE. The university awards the degree, and your advisor explains in writing how employers here usually read that award, before you pay anything. If you have a question about recognition, we answer it in plain language. We would rather lose an enrolment than leave you with a surprise later.',
                'anchor' => '#mlp-faq',
            ],
            [
                'title' => 'Head Office/Sharjah and London',
                'text' => 'Counsellors at our Sharjah head office know this market, how employers here hire, and the paperwork that comes with the degree. Walk in, message us on WhatsApp, or connect with us through the <a href="/contact">contact page</a>. Our London office is part of the same team, and the people you start with stay with you until you graduate.',
                'anchor' => '#mlp-enquire',
            ],
        ]);

        $this->migrator->update('mba_masters_journey.label', fn () => 'How to Start');
        $this->migrator->update('mba_masters_journey.heading', fn () => 'How to Start: Five Steps');
        $this->migrator->update(
            'mba_masters_journey.intro',
            fn () => 'Most people are enrolled within two to four weeks. An advisor walks the steps with you, and the September 2026 intake is open now.'
        );
        $this->migrator->update('mba_masters_journey.steps', fn () => [
            [
                'title' => 'A free 15-minute eligibility check',
                'text' => 'Send your CV, or just your LinkedIn profile. No documents yet, and no commitment.',
            ],
            [
                'title' => 'Written confirmation in 24 hours',
                'text' => 'Within 24 hours we confirm, in writing, the admission requirements, the right programme, the total fee, and the intake dates.',
            ],
            [
                'title' => 'Reserve your seat',
                'text' => 'You reserve your seat with a first instalment. Places are capped for each intake, and September fills first.',
            ],
            [
                'title' => 'Enrolment and Orientation',
                'text' => 'You receive portal access, join orientation, and get your week-one study schedule.',
            ],
            [
                'title' => 'Zoom Classes Begin',
                'text' => 'Evening Zoom classes, with a recording if you miss a session, and a success coach who already knows your name.',
            ],
        ]);
        $this->migrator->update('mba_masters_journey.cta_label', fn () => 'Start Eligibility Check');

        $this->migrator->update('mba_masters_mba.label', fn () => 'MBA Specializations');
        $this->migrator->update('mba_masters_mba.heading', fn () => 'MBA Specializations in the UAE: Pick Your MBA');
        $this->migrator->update(
            'mba_masters_mba.intro',
            fn () => 'More than 30 specializations, 100% online, from universities you can put on a CV. Study with Rushford Business School in Switzerland, Girne American University in North Cyprus, the University for the Creative Arts in the UK with Rushford on the Dual MBA, the University of Wolverhampton, or the University of the West of Scotland for the MBA in International Business. Compare the routes, then choose the one that matches the role you want next.'
        );
        $this->migrator->update('mba_masters_mba.tabs', function ($tabs) {
            $tabs = json_decode(json_encode($tabs ?? []), true);
            if (! is_array($tabs)) {
                return [];
            }

            foreach ($tabs as &$tab) {
                if (($tab['key'] ?? '') !== 'rbs-mba') {
                    continue;
                }

                $programs = $tab['universities'][0]['programs'] ?? [];
                $existing = collect($programs)->pluck('title')->all();
                $missing = array_values(array_filter(
                    ['MBA in Artificial Intelligence', 'MBA in International Business'],
                    fn (string $title) => ! in_array($title, $existing, true)
                ));

                if ($missing !== []) {
                    $rows = array_map(fn (string $title) => ['title' => $title], $missing);
                    $index = null;
                    foreach ($programs as $i => $program) {
                        if (($program['title'] ?? '') === 'Master of Business Administration (MBA)') {
                            $index = $i;
                            break;
                        }
                    }
                    if ($index === null) {
                        $programs = array_merge($programs, $rows);
                    } else {
                        array_splice($programs, $index, 0, $rows);
                    }
                }

                $tab['universities'][0]['programs'] = array_values($programs);
                if (isset($tab['universities'][0]['specification']) && is_array($tab['universities'][0]['specification'])) {
                    $tab['universities'][0]['specification']['programme_count'] = (string) count($programs);
                }
            }
            unset($tab);

            return array_values($tabs);
        });

        $this->migrator->update(
            'mba_masters_masters.intro',
            fn () => 'The same partner universities, with a different focus: sustainability, supply chain, economics, healthcare, counselling psychology, and law.'
        );
        $this->migrator->update('mba_masters_masters.trending', fn () => [
            ['label' => 'Affordable MBA in Finance', 'percent' => 78],
            ['label' => 'Affordable MBA in Human Resource Management', 'percent' => 72],
            ['label' => 'Affordable MBA in Healthcare Leadership', 'percent' => 68],
            ['label' => 'Affordable MBA in Artificial Intelligence', 'percent' => 64],
            ['label' => 'Affordable MBA in International Business', 'percent' => 61],
            ['label' => 'Affordable MBA in Marketing', 'percent' => 57],
            ['label' => 'Affordable MBA in Logistics and Supply Chain', 'percent' => 54],
            ['label' => 'Affordable MBA in Strategic Management', 'percent' => 51],
        ]);

        $this->migrator->update('mba_masters_class.label', fn () => 'Class Snapshot');
        $this->migrator->update(
            'mba_masters_class.heading',
            fn () => 'Your Maverick Cohort: Professionals Across the UAE and GCC'
        );
        $this->migrator->update(
            'mba_masters_class.intro',
            fn () => 'The people in this cohort already have a career. Founders, bankers, government specialists, and senior operators join from the UAE, Saudi Arabia, Oman, and Qatar. When a group project starts, it sounds like a regional business meeting, because that is who is on the call.'
        );
        $this->migrator->update(
            'mba_masters_class.audience',
            fn () => 'The typical Maverick student works full time and studies after work. Most join so they can step up, without stepping away from the job that pays them.'
        );
        $this->migrator->update('mba_masters_class.metrics', function ($metrics) {
            $metrics = json_decode(json_encode($metrics ?? []), true) ?: [];
            $labels = [
                'Median age in the current cohort',
                'Average work experience',
                'In mid-level or senior roles',
                'Employed full time while studying',
                'Sponsored or supported by their employer',
            ];
            foreach ($metrics as $i => &$metric) {
                if (isset($labels[$i])) {
                    $metric['label'] = $labels[$i];
                }
            }
            unset($metric);

            return $metrics;
        });
        $this->migrator->update('mba_masters_class.regions', function ($regions) {
            $regions = json_decode(json_encode($regions ?? []), true) ?: [];
            $notes = [
                'uae' => 'About half the cohort, across Dubai, Abu Dhabi, and Sharjah',
                'saudi arabia' => 'About a quarter of the cohort, from Riyadh, Jeddah, and the NEOM region',
                'oman' => 'A growing share each intake, based in Muscat',
                'qatar' => 'A steady presence, based in Doha',
            ];
            foreach ($regions as &$region) {
                $key = mb_strtolower(trim((string) ($region['name'] ?? '')));
                if (isset($notes[$key])) {
                    $region['note'] = $notes[$key];
                }
            }
            unset($region);

            return $regions;
        });

        $this->migrator->update('mba_masters_fees.heading', fn () => 'MBA Fees in the UAE: What You Actually Pay');
        $this->migrator->update(
            'mba_masters_fees.intro',
            fn () => 'There are no hidden charges, and we do not print outdated numbers. Across these programmes, the total is usually between AED 16,000 and 40,000, about a third of a campus MBA in Dubai. We confirm your exact figure in writing before you pay a dirham.'
        );
        $this->migrator->update(
            'mba_masters_fees.note',
            fn () => 'Fees change with the programme and the intake. Written confirmation always comes before any payment.'
        );
        $this->migrator->update('mba_masters_fees.rows', function ($rows) {
            $rows = json_decode(json_encode($rows ?? []), true) ?: [];
            foreach ($rows as &$row) {
                $program = (string) ($row['program'] ?? '');
                if (str_starts_with($program, 'MBA, Rushford')) {
                    $row['program'] = 'MBA, Rushford (14 Specializations)';
                }
                if (str_starts_with($program, 'Global MBA')) {
                    $row['program'] = 'Dual MBA, UCA with Rushford';
                }
                if (($row['mode'] ?? '') === 'Online') {
                    $row['mode'] = '100% Online';
                }
                if (($row['duration'] ?? '') === 'Confirmed per programme') {
                    $row['duration'] = 'Confirmed for each programme';
                }
            }
            unset($row);

            return $rows;
        });
        $this->migrator->update('mba_masters_fees.blocks', fn () => [
            [
                'title' => 'A flexible payment plan, in AED, with no interest',
                'text' => 'You do not pay the full fee upfront. Monthly instalments are in dirhams and timed to your salary, so the degree sits inside your cash flow from the start. Many students also receive partial sponsorship. It is worth asking, because employers agree more often than people expect.',
            ],
            [
                'title' => 'What your fee covers',
                'text' => 'Your fee covers full tuition and the university award, all study materials and portal access, session recordings, advisor support from Sharjah on Gulf time, plus orientation, enrolment, and student services.',
            ],
            [
                'title' => 'Scholarships and early-bird discounts',
                'text' => 'Scholarship support is available for strong MBA candidates, and early applicants pay less. Eligibility depends on your profile and the timing, so ask what applies to you before you assume.',
            ],
        ]);

        $this->migrator->update('mba_masters_career.label', fn () => 'Career Stories');
        $this->migrator->update('mba_masters_career.heading', fn () => 'What UAE Graduates Did Next');
        $this->migrator->update(
            'mba_masters_career.intro',
            fn () => 'Recent outcomes, told simply. Each of these graduates kept a full-time job and studied in the evening.'
        );
        $this->migrator->update('mba_masters_career.stories', function ($stories) {
            $stories = json_decode(json_encode($stories ?? []), true) ?: [];
            $quotes = [
                'Ahmed' => 'He moved into a regional operations role six months after graduating. His employer supported the fees.',
                'Fatima' => 'She used the final project to redesign her company\'s talent framework, and she was promoted before the course ended.',
                'Khalid' => 'He took over a second branch within a year. The finance modules changed how he reads a profit and loss statement.',
                'Sara' => 'She led her first regional campaign within a year of graduating. The specialization matched the role she wanted.',
            ];
            foreach ($stories as &$story) {
                $name = (string) ($story['name'] ?? '');
                if (isset($quotes[$name])) {
                    $story['quote'] = $quotes[$name];
                }
            }
            unset($story);

            return $stories;
        });

        $this->migrator->update(
            'mba_masters_alumni.intro',
            fn () => 'Graduates work in government departments, free zones, banks, hospital groups, and multinationals across the Gulf. Most new students come to us because a colleague completed the degree and said the evenings were worth it. Ask us for alumni references in your industry before you decide. We are happy to arrange them.'
        );
        $this->migrator->update(
            'mba_masters_alumni.trust_line',
            fn () => 'Company logos are shown with permission from alumni employers.'
        );

        $this->migrator->update('mba_masters_partners.label', fn () => 'University Partners');
        $this->migrator->update(
            'mba_masters_partners.intro',
            fn () => 'Every Master\'s on this page is awarded by one of the universities below. You apply once, study 100% online, and graduate with that university\'s name on the certificate. One application covers admission, enrolment, and your study plan.'
        );
        $this->migrator->update(
            'mba_masters_partners.trust_line',
            fn () => 'Rushford Business School · Girne American University · University for the Creative Arts · University of Wolverhampton · University of the West of Scotland'
        );
        $this->migrator->update('mba_masters_partners.checklist', fn () => [
            ['text' => 'The university awards your degree, not a third party'],
            ['text' => 'Your degree will not mention that you studied online'],
            ['text' => 'Accreditation status is confirmed in writing before you enrol'],
            ['text' => 'Recognition guidance for the UAE, explained in plain language when you ask'],
        ]);

        $this->migrator->update('mba_masters_learning.label', fn () => 'Learning Experience');
        $this->migrator->update('mba_masters_learning.heading', fn () => 'How Online Learning Actually Works');
        $this->migrator->update(
            'mba_masters_learning.intro',
            fn () => 'This is not a pile of old recordings. It is a weekly rhythm, with real people in it.'
        );
        $this->migrator->update(
            'mba_masters_learning.plate_caption',
            fn () => 'Live evenings · Named coach · Workplace projects'
        );
        $this->migrator->update('mba_masters_learning.points', fn () => [
            [
                'title' => 'Live evening classes',
                'text' => 'Two live Zoom sessions a week, with a recording if you miss one.',
            ],
            [
                'title' => 'Dedicated success coach',
                'text' => 'One named coach from orientation through to graduation.',
            ],
            [
                'title' => 'Online exams, from home',
                'text' => 'No travel for assessments. The marking criteria are clear, and feedback arrives on time.',
            ],
            [
                'title' => 'Project on your own business',
                'text' => 'Each module is applied to a live challenge from your workplace.',
            ],
            [
                'title' => 'Career-relevant assessment',
                'text' => 'Projects, presentations, and portfolios you can show your employer.',
            ],
        ]);

        if ($this->migrator->exists('mba_masters_video_testimonials.intro')) {
            $this->migrator->update(
                'mba_masters_video_testimonials.intro',
                fn () => 'Evening classes, advisors in Sharjah, and workplace projects, in their own words.'
            );
        }

        $this->migrator->update(
            'mba_masters_testimonials.intro',
            fn () => 'Recent voices from Dubai, Abu Dhabi, and Sharjah.'
        );

        $this->migrator->update(
            'mba_masters_compare.intro',
            fn () => 'If you have weighed a part-time MBA on campus against a 100% online MBA, this is the plain version of that comparison. The award is the same. The life around it is not.'
        );
        $this->migrator->update('mba_masters_compare.col_online', fn () => '100% Online MBA');
        $this->migrator->update('mba_masters_compare.rows', fn () => [
            [
                'criterion' => 'Total fees',
                'online' => 'AED 16,000 to 40,000',
                'traditional' => 'Typically AED 80,000 to 200,000+',
            ],
            [
                'criterion' => 'Commute',
                'online' => 'None',
                'traditional' => '3 to 5 hours a week in traffic',
            ],
            [
                'criterion' => 'Class timing',
                'online' => 'Evenings and weekends, from home',
                'traditional' => 'A fixed campus timetable',
            ],
            [
                'criterion' => 'Visa needed',
                'online' => 'No',
                'traditional' => 'Yes, for international campuses',
            ],
            [
                'criterion' => 'Study while working',
                'online' => 'Yes. It is designed for that.',
                'traditional' => 'Often requires a break from work',
            ],
            [
                'criterion' => 'Award on the certificate',
                'online' => 'A university-awarded degree. Online study is not printed on it.',
                'traditional' => 'A university-awarded degree',
            ],
            [
                'criterion' => 'Networking',
                'online' => 'Webinars, seminars, alumni job consultations, live cohort sessions, and an active WhatsApp community',
                'traditional' => 'On-campus cohorts',
            ],
        ]);
        $this->migrator->update('mba_masters_compare.blocks', fn () => [
            [
                'title' => 'Fast-track or standard: your choice',
                'text' => 'A fast-track MBA compresses the same modules for experienced applicants. The standard pace spreads the work across busier seasons. Choose with your calendar. An advisor will help you decide.',
            ],
            [
                'title' => 'The same award, either way',
                'text' => 'The certificate does not mention online study. The difference is how you study, not the award you earn.',
            ],
        ]);
        $this->migrator->update('mba_masters_compare.cta_label', fn () => 'Ask Which Pace Fits You');

        $this->migrator->update('mba_masters_faq.items', fn () => [
            [
                'question' => 'What are the admission requirements for an MBA?',
                'answer' => 'Most programmes ask for a bachelor\'s degree and two to three years of work experience. None of them require a GMAT. If your background looks different, send your CV anyway. Our advisors map entry routes for experienced professionals every week, and they confirm eligibility in writing.',
            ],
            [
                'question' => 'Are these degrees recognised in the UAE?',
                'answer' => 'You study 100% online from the UAE, and the university awards the degree. The certificate does not mention online study. Employers across the Gulf usually look at the awarding university, which is why we confirm those details in writing before you enrol. If you want to talk through how your employer is likely to read the certificate, an advisor will do that with you at no charge.',
            ],
            [
                'question' => 'How much does an MBA cost in the UAE?',
                'answer' => 'Most programmes on this page cost between AED 16,000 and 40,000, paid in instalments. A campus MBA in Dubai typically costs about three times that. Your advisor confirms your exact total in writing before you pay.',
            ],
            [
                'question' => 'Can I study 100% online from Dubai or Abu Dhabi?',
                'answer' => 'Yes. You can also study from Sharjah, Al Ain, or anywhere else in the GCC. Classes run on evenings and weekends around Gulf working hours. Recordings cover the nights you miss. There is no campus attendance, and there is no student visa.',
            ],
            [
                'question' => 'When is the next intake?',
                'answer' => 'The September 2026 intake is open now, with later intakes through the year. Places are capped for each intake, so reserving about four weeks ahead keeps onboarding calm. We confirm the current dates within one working day.',
            ],
            [
                'question' => 'Is there a fast-track MBA option?',
                'answer' => 'Yes. The fast-track MBA compresses the full curriculum for experienced professionals. Ask an advisor whether your background qualifies. It is a five-minute conversation.',
            ],
            [
                'question' => 'Are there scholarships or discounts available?',
                'answer' => 'Yes. There is scholarship support for strong candidates, and an early-bird discount before each intake. Eligibility depends on your profile and the timing, so ask what applies to you instead of guessing.',
            ],
            [
                'question' => 'Which MBA specializations are in demand in the UAE?',
                'answer' => 'Finance, Artificial Intelligence, International Business, healthcare leadership, logistics and supply chain, and human resources are the ones our students in the UAE and the wider GCC ask for most. The catalogue above lists every specialization you can choose.',
            ],
            [
                'question' => 'How many hours per week does a part-time MBA need?',
                'answer' => 'Plan for 8 to 10 hours a week, covering evening classes, self-study, and assessments. Most students do a little on most days and a bit more at the weekend. Recordings cover any session you miss.',
            ],
            [
                'question' => 'Will my employer in the UAE accept an online degree?',
                'answer' => 'Most do, and many sponsor the fees. Employers look at the university that awards the degree, and the certificate does not mention that you studied online. We confirm the award details in writing before you enrol. If HR needs sponsorship paperwork, we prepare it with you.',
            ],
            [
                'question' => 'Can I study from Saudi Arabia, Oman, or Qatar?',
                'answer' => 'Yes. About half of the current cohort is in the UAE. The rest study from Saudi Arabia, Oman, Qatar, and further afield. Support runs on Gulf time, and each intake has its own WhatsApp group.',
            ],
            [
                'question' => 'Do I need a student visa?',
                'answer' => 'No. You study 100% online from where you live, so there is no student visa, and your residence visa stays as it is. For professionals in the GCC, that removes the paperwork a foreign campus used to require.',
            ],
        ]);

        $this->migrator->update('mba_masters_final.label', fn () => 'Next Step');
        $this->migrator->update(
            'mba_masters_final.intro',
            fn () => 'Send your CV or your LinkedIn profile. Within 24 hours, an advisor in Sharjah will map your route, your total fees, and the September 2026 intake, in writing. There is no obligation, no pressure, and no student visa. You can also message us on WhatsApp, or connect with us through the <a href="/contact">contact page</a>. A person replies the same day.'
        );
        $this->migrator->update('mba_masters_final.cta_secondary_label', fn () => 'WhatsApp Admissions');

        $this->migrator->update(
            'mba_masters_seo.meta_description',
            fn () => 'Study a 100% online MBA or Master\'s from Switzerland, North Cyprus, or the UK, in the evenings from the UAE. No visa, AED instalments, and the September 2026 intake is open.'
        );

        if (function_exists('app') && app()->bound(\Illuminate\Contracts\Console\Kernel::class)) {
            try {
                \Illuminate\Support\Facades\Artisan::call('settings:clear-cache');
            } catch (\Throwable) {
            }
        }
    }
};
