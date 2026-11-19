<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Source: uploads/10-masters-landing-content-client-gcc-uae.pdf (16 Sep 2026)
        // Text/structure only — preserve images/asset IDs set in admin where possible.

        $this->migrator->update('mba_masters_hero.eyebrow', fn () => 'No visa. No relocation. Study from where you already live.');
        $this->migrator->update('mba_masters_hero.headline', fn () => 'Online MBA & Master\'s Degrees for the UAE and GCC');
        $this->migrator->update(
            'mba_masters_hero.subheading',
            fn () => 'You keep the job, the salary, and the city. The degree comes to you instead: an Online MBA or a Master\'s from Switzerland, North Cyprus, or the UK, studied in the evenings from the UAE. The September 2026 intake is open, and seats go in order of payment.'
        );
        $this->migrator->update('mba_masters_hero.form_title', fn () => 'Send me the programme guide');
        $this->migrator->update('mba_masters_hero.cta_primary_label', fn () => 'Apply Now');
        $this->migrator->update('mba_masters_hero.cta_primary_url', fn () => '#mlp-enquire');
        $this->migrator->update('mba_masters_hero.cta_secondary_label', fn () => 'Download Syllabus');
        $this->migrator->update('mba_masters_hero.cta_secondary_url', fn () => '#mlp-enquire');
        $this->migrator->update('mba_masters_hero.cta_tertiary_label', fn () => 'Download Syllabus');
        $this->migrator->update('mba_masters_hero.cta_tertiary_url', fn () => '#mlp-enquire');

        $this->migrator->update(
            'mba_masters_trust.label',
            fn () => 'Rated 4.9 out of 5 on Trustpilot by people who actually finished'
        );
        $this->migrator->update(
            'mba_masters_trust.quote',
            fn () => 'One of the best decisions I have made. The degree is genuinely university-awarded, and my employer in Abu Dhabi had zero questions about its validity.'
        );
        $this->migrator->update(
            'mba_masters_trust.quote_attribution',
            fn () => 'Rajesh Menon, MBA graduate, Abu Dhabi'
        );
        $this->migrator->update('mba_masters_trust.stats', fn () => [
            ['value' => '4.9/5', 'label' => 'average student rating (Trustpilot)'],
            ['value' => '3,000+', 'label' => 'UAE alumni across all programmes'],
            ['value' => '100%', 'label' => 'online. No visa. No relocation.'],
        ]);

        $this->migrator->update('mba_masters_overview.heading', fn () => 'MBA in Dubai: what you actually get');
        $this->migrator->update(
            'mba_masters_overview.intro',
            fn () => 'Five things below, no brochure language. Built as an MBA in Dubai for working professionals, this is the degree the way our students describe it after a term or two.'
        );
        $this->migrator->update('mba_masters_overview.items', fn () => [
            [
                'title' => 'A learning community that pulls its weight',
                'text' => 'Your classmates are founders, bank managers, and government specialists from the UAE, Saudi Arabia, Oman, and Qatar. The WhatsApp group that gets you through assignments becomes the network you call after graduation. It happens every intake, and it costs nothing extra.',
            ],
            [
                'title' => 'ASK Quotient development',
                'text' => 'Attitude, Skills, Knowledge. Employers screen for all three, and most courses teach one. Your success coach tracks the three of them across the degree, so the change shows up in your work as well as on the certificate.',
            ],
            [
                'title' => 'Skills-based MBA learning',
                'text' => 'Assignments are shaped like work: a business plan, a market entry analysis, a review of how your team is run. You hand in things you can reuse on a Monday morning, and your manager tends to notice before you graduate.',
            ],
            [
                'title' => 'Knowledge-based MBA learning',
                'text' => 'The theory comes from your university\'s faculty, then gets dragged into Gulf reality with regional case material, short readings, and frameworks you can hold in your head. Recordings cover the evenings when work wins.',
            ],
            [
                'title' => 'Real-world case studies',
                'text' => 'Cases come from businesses that are actually operating, and the final project attacks a problem inside your own company. Several graduates have walked that project straight into a performance review.',
            ],
        ]);
        $this->migrator->update('mba_masters_overview.cta_primary_label', fn () => 'Explore the MBA families');
        $this->migrator->update('mba_masters_overview.cta_primary_url', fn () => '#mlp-mba');
        $this->migrator->update('mba_masters_overview.cta_secondary_label', fn () => 'Ask an advisor');
        $this->migrator->update('mba_masters_overview.cta_secondary_url', fn () => '#mlp-enquire');

        $this->migrator->update(
            'mba_masters_why.heading',
            fn () => 'Why an MBA for working professionals in UAE makes sense right now'
        );
        $this->migrator->update(
            'mba_masters_why.intro',
            fn () => 'A Master\'s here is not a pause button on your life. It is the shortest route we know to a bigger role, a better salary conversation, and a credential that travels, while the salary keeps arriving.'
        );
        $this->migrator->update('mba_masters_why.chapters', fn () => [
            [
                'title' => 'Learn without pausing your salary',
                'text' => 'Campus programmes ask you to quit, move, or wait for Saturday. This one runs on Tuesday and Thursday evenings instead, live, from where you already are. You apply things on Wednesday morning, and that loop is the point. A genuinely flexible MBA schedule, with the salary intact.',
                'anchor' => null,
            ],
            [
                'title' => 'Your certificate comes from the university itself',
                'text' => 'The awarding university issues your certificate, the same one it gives its on-campus students. No asterisk and no footnote about online study. It travels across the GCC and beyond.',
                'anchor' => '#mlp-partners',
            ],
            [
                'title' => 'Built for promotion and transition',
                'text' => 'Modules map to what UAE employers screen for: leadership, finance, operations, strategy. The final project uses a live problem from your own workplace, which is concrete proof a mark sheet never is, that you can turn theory into results.',
                'anchor' => '#mlp-career',
            ],
            [
                'title' => 'Pay in AED instalments',
                'text' => 'A campus MBA in UAE cities runs AED 80,000 to 200,000. Online, most programmes on this page total AED 16,000 to 40,000, paid monthly in dirhams and timed to your salary date. The degree fits your cash flow from day one.',
                'anchor' => '#mlp-fees',
            ],
            [
                'title' => 'A recognised route, explained honestly',
                'text' => 'Study happens fully online from the UAE. Degrees from an accredited institution can go through MoHESR recognition, and the process is electronic. The route that applies to you depends on your circumstances, so confirm your category with an advisor before you pay. We\'d rather lose an enrolment than win a complaint.',
                'anchor' => '#mlp-faq',
            ],
            [
                'title' => 'Sharjah office, not a call centre',
                'text' => 'Real counsellors in Sharjah who know this market, your employer\'s habits, and the recognition paperwork. Walk in, or sort it out on WhatsApp. The same people stay with you until graduation.',
                'anchor' => '#mlp-enquire',
            ],
        ]);

        $this->migrator->update('mba_masters_journey.label', fn () => 'How to start');
        $this->migrator->update('mba_masters_journey.heading', fn () => 'How to start: five steps');
        $this->migrator->update(
            'mba_masters_journey.intro',
            fn () => 'Two to four weeks, start to finish. An advisor walks it with you, and the September 2026 intake is open while you read this.'
        );
        $this->migrator->update('mba_masters_journey.steps', fn () => [
            [
                'title' => 'A free 15-minute eligibility check',
                'text' => 'Send the CV or just your LinkedIn. No documents yet, no commitment.',
            ],
            [
                'title' => 'Written confirmation in 24 hours',
                'text' => 'We confirm admission requirements and fees in writing within 24 hours: the right programme, the total number, the intake dates.',
            ],
            [
                'title' => 'Reserve your seat',
                'text' => 'You reserve the seat with a first instalment. Seats are capped per intake, and September fills first.',
            ],
            [
                'title' => 'Enrolment and induction',
                'text' => 'Portal access, orientation, and your week-one study schedule.',
            ],
            [
                'title' => 'Classes begin',
                'text' => 'Evening sessions, recordings, and a success coach who already knows your name.',
            ],
        ]);
        $this->migrator->update('mba_masters_journey.cta_label', fn () => 'Start eligibility check');
        $this->migrator->update('mba_masters_journey.cta_url', fn () => '#mlp-enquire');

        $this->migrator->update('mba_masters_mba.label', fn () => 'MBA specializations');
        $this->migrator->update('mba_masters_mba.heading', fn () => 'MBA specializations in UAE: Pick Your MBA');
        $this->migrator->update(
            'mba_masters_mba.intro',
            fn () => 'Five routes on one page: twelve specializations at Rushford Business School, six MBAs and sixteen Executive MBAs at Girne American University, a Global MBA awarded in the UK, and an International Business MBA from the University of the West of Scotland. All university-awarded, all online. Compare them, then pick the one that matches the role you want next.'
        );
        $this->migrator->update('mba_masters_mba.tabs', function ($tabs) {
            $tabs = json_decode(json_encode($tabs ?? []), true);
            if (! is_array($tabs)) {
                $tabs = [];
            }

            foreach ($tabs as &$tab) {
                if (($tab['key'] ?? '') === 'gau-mba') {
                    $tab['label'] = 'MBA, Girne American University (North Cyprus)';
                    $programs = collect($tab['universities'][0]['programs'] ?? [])
                        ->pluck('title')
                        ->filter()
                        ->values()
                        ->all();
                    if (! in_array('MBA Data Science/Analytics Management', $programs, true)) {
                        $tab['universities'][0]['programs'][] = ['title' => 'MBA Data Science/Analytics Management'];
                    }
                    if (isset($tab['universities'][0]['specification'])) {
                        $tab['universities'][0]['specification']['programme_count'] = '6';
                    }
                }
                if (($tab['key'] ?? '') === 'rbs-mba') {
                    $tab['label'] = 'MBA, Rushford Business School (Switzerland)';
                }
                if (($tab['key'] ?? '') === 'gau-emba') {
                    $tab['label'] = 'Executive MBA, Girne American University (North Cyprus)';
                }
                if (($tab['key'] ?? '') === 'uca-global-mba') {
                    $tab['label'] = 'Global MBA, University for the Creative Arts (UK) with Rushford';
                    if (isset($tab['universities'][0]['programs'][0])) {
                        $tab['universities'][0]['programs'][0]['title'] = 'Global MBA';
                    }
                }
            }
            unset($tab);

            $hasUws = collect($tabs)->contains(fn ($t) => ($t['key'] ?? '') === 'uws-mba');
            if (! $hasUws) {
                $tabs[] = [
                    'key' => 'uws-mba',
                    'label' => 'MBA in International Business, University of the West of Scotland (UK)',
                    'universities' => [
                        [
                            'name' => 'University of the West of Scotland (UWS), UK',
                            'logo' => null,
                            'logo_asset_id' => null,
                            'image' => 'assets/images/mba-masters-landing/mba/business-management-mba.jpg',
                            'image_asset_id' => null,
                            'specification' => [
                                'category' => 'MBA in International Business',
                                'qualification' => 'MBA',
                                'listing_page' => 'GCC content PDF',
                                'programme_count' => '1',
                            ],
                            'programs' => [
                                ['title' => 'MBA in International Business'],
                            ],
                        ],
                    ],
                ];
            }

            return array_values($tabs);
        });

        $this->migrator->update('mba_masters_masters.label', fn () => 'Master\'s Degrees');
        $this->migrator->update('mba_masters_masters.heading', fn () => 'Master\'s Degrees Beyond the MBA');
        $this->migrator->update(
            'mba_masters_masters.intro',
            fn () => 'Same award route, different toolkits: sustainability, supply chain, economics, healthcare, counselling psychology, law.'
        );
        $this->migrator->update('mba_masters_masters.trending_title', fn () => 'Trending|Picks');
        $this->migrator->update('mba_masters_masters.trending', fn () => [
            ['label' => 'Affordable MBA in Finance', 'percent' => 78],
            ['label' => 'Affordable MBA in Human Resource Management', 'percent' => 72],
            ['label' => 'Affordable MBA in Healthcare Leadership', 'percent' => 68],
        ]);

        $this->migrator->update('mba_masters_class.label', fn () => 'Class snapshot');
        $this->migrator->update('mba_masters_class.heading', fn () => 'Your classmates: UAE and GCC professionals');
        $this->migrator->update(
            'mba_masters_class.intro',
            fn () => 'The most mixed cohort we have run in the Gulf: founders, government specialists, bankers, and senior operators from four markets. Group work ends up sounding like a regional business meeting, because it is one.'
        );
        $this->migrator->update(
            'mba_masters_class.audience',
            fn () => 'The average student here works full time and studies after work. Most join to step up without stepping out.'
        );
        $this->migrator->update('mba_masters_class.metrics', fn () => [
            ['value' => '35', 'label' => 'median age in the current cohort'],
            ['value' => '9 years', 'label' => 'of average work experience'],
            ['value' => '68%', 'label' => 'hold mid-level or senior roles'],
            ['value' => '100%', 'label' => 'employed full time while studying'],
            ['value' => '80%+', 'label' => 'sponsored or supported by their employer'],
        ]);
        $this->migrator->update('mba_masters_class.regions', fn () => [
            ['name' => 'UAE', 'note' => 'around half the cohort · Dubai, Abu Dhabi, and Sharjah'],
            ['name' => 'Saudi Arabia', 'note' => 'about one quarter · Riyadh, Jeddah, and the NEOM region'],
            ['name' => 'Oman', 'note' => 'growing every intake · Muscat'],
            ['name' => 'Qatar', 'note' => 'steady presence · Doha'],
        ]);
        $this->migrator->update('mba_masters_class.industries', function ($industries) {
            $names = [
                'Energy & Oil',
                'Logistics & Trade',
                'Banking & Finance',
                'Government',
                'Healthcare',
                'Education',
                'Real Estate',
                'Tech & Consulting',
            ];
            $existing = collect(json_decode(json_encode($industries ?? []), true) ?: [])
                ->keyBy(fn ($row) => mb_strtolower(trim((string) ($row['name'] ?? ''))));
            $out = [];
            foreach ($names as $i => $name) {
                $key = mb_strtolower($name);
                $prev = $existing->get($key) ?? $existing->first(
                    fn ($row) => str_contains(mb_strtolower((string) ($row['name'] ?? '')), explode(' ', $key)[0])
                );
                $out[] = [
                    'name' => $name,
                    'share' => $prev['share'] ?? (string) max(8, 22 - $i),
                    'image' => $prev['image'] ?? null,
                    'image_asset_id' => $prev['image_asset_id'] ?? null,
                ];
            }

            return $out;
        });

        $this->migrator->update('mba_masters_fees.label', fn () => 'Fees');
        $this->migrator->update('mba_masters_fees.heading', fn () => 'MBA Fees in UAE: What You Actually Pay');
        $this->migrator->update(
            'mba_masters_fees.intro',
            fn () => 'No hidden lines, and we don\'t print stale numbers here either. Across programmes, totals usually land between AED 16,000 and 40,000, about a third of a campus MBA in Dubai. Your exact figure gets confirmed in writing before you pay a dirham.'
        );
        $this->migrator->update(
            'mba_masters_fees.note',
            fn () => 'Fees move with the programme and the intake. The written confirmation comes before any payment, always.'
        );
        $this->migrator->update('mba_masters_fees.rows', fn () => [
            [
                'program' => 'MBA, Rushford (12 specializations)',
                'duration' => '10 to 15 months',
                'mode' => 'Online',
                'payment' => 'AED instalments',
            ],
            [
                'program' => 'MBA, Girne American University',
                'duration' => '10 to 15 months',
                'mode' => 'Online',
                'payment' => 'AED instalments',
            ],
            [
                'program' => 'Executive MBA, Girne American University',
                'duration' => '12 to 18 months',
                'mode' => 'Online',
                'payment' => 'AED instalments',
            ],
            [
                'program' => 'Global MBA, UCA with Rushford',
                'duration' => '10 to 15 months',
                'mode' => 'Online',
                'payment' => 'AED instalments',
            ],
            [
                'program' => 'MSc (Rushford, Girne) and LLM (Wolverhampton)',
                'duration' => '8 to 18 months',
                'mode' => 'Online',
                'payment' => 'AED instalments',
            ],
            [
                'program' => 'MBA in International Business, UWS',
                'duration' => 'Confirmed per programme',
                'mode' => 'Online',
                'payment' => 'AED instalments',
            ],
        ]);
        $this->migrator->update('mba_masters_fees.blocks', fn () => [
            [
                'title' => 'A flexible payment plan, in AED, with no interest',
                'text' => 'No full upfront payment. Monthly instalments in dirhams, timed to your salary cycle, so the degree sits inside your cash flow from day one. Plenty of students get part of it sponsored too; ask, because employers say yes more often than people expect.',
            ],
            [
                'title' => 'What your fee covers',
                'text' => 'Full tuition and the university award. All study materials and portal access. Session recordings for revision. Advisor support in Sharjah, on Gulf time. Induction, enrolment, and student services.',
            ],
            [
                'title' => 'Scholarships and early-bird discounts',
                'text' => 'MBA scholarship support exists for strong candidates, and early birds pay less. Eligibility depends on profile and timing, so ask what yours looks like before you assume the answer.',
            ],
        ]);
        $this->migrator->update('mba_masters_fees.cta_primary_label', fn () => 'Request Fee Details');
        $this->migrator->update('mba_masters_fees.cta_primary_url', fn () => '#mlp-enquire');
        $this->migrator->update('mba_masters_fees.cta_secondary_label', fn () => 'Get Scholarship Eligibility Check');
        $this->migrator->update('mba_masters_fees.cta_secondary_url', fn () => '#mlp-enquire');

        $this->migrator->update('mba_masters_career.label', fn () => 'Career stories');
        $this->migrator->update('mba_masters_career.heading', fn () => 'What UAE graduates did next');
        $this->migrator->update(
            'mba_masters_career.intro',
            fn () => 'Recent outcomes, told plainly. Every one of these started with a full-time job and evening classes.'
        );
        $this->migrator->update('mba_masters_career.stories', function ($stories) {
            $stories = json_decode(json_encode($stories ?? []), true) ?: [];
            $portraits = collect($stories)->pluck('portrait')->values()->all();
            $assetIds = collect($stories)->pluck('portrait_asset_id')->values()->all();

            $new = [
                [
                    'name' => 'Ahmed',
                    'country' => 'Dubai',
                    'program' => 'MBA in Logistics & Supply Chain Management',
                    'previous_role' => 'Operations Lead',
                    'current_role' => 'Regional operations role',
                    'quote' => 'Moved into a regional operations role six months after graduating. His employer supported the fees.',
                ],
                [
                    'name' => 'Fatima',
                    'country' => 'Riyadh',
                    'program' => 'MBA in Human Resource Management',
                    'previous_role' => 'HR Manager',
                    'current_role' => 'Promoted before the course ended',
                    'quote' => 'Used the final project to redesign her company\'s talent framework. Promoted before the course ended.',
                ],
                [
                    'name' => 'Khalid',
                    'country' => 'Doha',
                    'program' => 'Master of Business Administration',
                    'previous_role' => 'Branch Manager',
                    'current_role' => 'Took over a second branch within a year',
                    'quote' => 'Took over a second branch within a year. The finance modules changed how he reads a P&L.',
                ],
                [
                    'name' => 'Sara',
                    'country' => 'Abu Dhabi',
                    'program' => 'MBA in Marketing',
                    'previous_role' => 'Marketing Executive',
                    'current_role' => 'Led her first regional campaign',
                    'quote' => 'Led her first regional campaign within a year of graduating. The specialization matched her target role exactly.',
                ],
            ];

            foreach ($new as $i => &$story) {
                $story['portrait'] = $portraits[$i] ?? ('assets/images/alumni/alumn-'.(($i % 5) + 1).'.png');
                $story['portrait_asset_id'] = $assetIds[$i] ?? null;
            }
            unset($story);

            return $new;
        });

        $this->migrator->update('mba_masters_alumni.label', fn () => 'Alumni');
        $this->migrator->update('mba_masters_alumni.heading', fn () => 'Alumni in the UAE and across the GCC');
        $this->migrator->update(
            'mba_masters_alumni.intro',
            fn () => 'Graduates sit in government departments, free zones, banks, hospital groups, and multinationals across the Gulf. Most new students arrive because a colleague did the degree first and said it was worth the evenings. Ask for alumni references in your industry before you decide; we encourage it.'
        );
        $this->migrator->update(
            'mba_masters_alumni.trust_line',
            fn () => 'Company logos shown with permission from alumni employers.'
        );

        $this->migrator->update('mba_masters_partners.label', fn () => 'University partners');
        $this->migrator->update(
            'mba_masters_partners.heading',
            fn () => 'International MBA Degrees from Our University Partners'
        );
        $this->migrator->update(
            'mba_masters_partners.intro',
            fn () => 'Every Master\'s on this page is awarded by one of the universities below. You apply once, study online, and graduate from the name on the certificate. One application covers admission, enrolment, and the study plan.'
        );
        $this->migrator->update(
            'mba_masters_partners.trust_line',
            fn () => 'Rushford · Girne American University · UCA · Wolverhampton · University of the West of Scotland'
        );
        $this->migrator->update('mba_masters_partners.checklist', fn () => [
            ['text' => 'The university awards your degree, not a third party'],
            ['text' => 'Your certificate is the same as an on-campus graduate\'s'],
            ['text' => 'Accreditation status is confirmed in writing before you enrol'],
            ['text' => 'Recognition guidance for the UAE, on request, in plain language'],
        ]);

        $this->migrator->update('mba_masters_learning.label', fn () => 'Learning experience');
        $this->migrator->update('mba_masters_learning.heading', fn () => 'How online learning actually works');
        $this->migrator->update(
            'mba_masters_learning.intro',
            fn () => 'Not recorded videos gathering dust. A rhythm, with people in it.'
        );
        $this->migrator->update(
            'mba_masters_learning.plate_caption',
            fn () => 'Live evenings · Named coach · Workplace projects'
        );
        $this->migrator->update('mba_masters_learning.points', fn () => [
            [
                'title' => 'Live evening classes',
                'text' => 'Two live sessions a week, recorded when you miss one.',
            ],
            [
                'title' => 'Dedicated success coach',
                'text' => 'One named coach for the whole degree, induction to graduation.',
            ],
            [
                'title' => 'Online exams, from home',
                'text' => 'No travel for assessments. Clear rubrics, timely feedback.',
            ],
            [
                'title' => 'Project on your own business',
                'text' => 'Each module applied to a live challenge from your workplace.',
            ],
            [
                'title' => 'Career-relevant assessment',
                'text' => 'Projects, presentations, and portfolios you can show your employer.',
            ],
        ]);
        $this->migrator->update('mba_masters_learning.cta_primary_label', fn () => 'Check Eligibility');
        $this->migrator->update('mba_masters_learning.cta_primary_url', fn () => '#mlp-enquire');
        $this->migrator->update('mba_masters_learning.cta_secondary_label', fn () => 'Speak to an Advisor');
        $this->migrator->update('mba_masters_learning.cta_secondary_url', fn () => '#mlp-enquire');

        if ($this->migrator->exists('mba_masters_video_testimonials.heading')) {
            $this->migrator->update(
                'mba_masters_video_testimonials.heading',
                fn () => 'What students say in their own words'
            );
            $this->migrator->update(
                'mba_masters_video_testimonials.intro',
                fn () => 'Evening format, Sharjah advisors, and workplace projects — in their words.'
            );
            $this->migrator->update('mba_masters_video_testimonials.videos', function ($videos) {
                $videos = json_decode(json_encode($videos ?? []), true) ?: [];
                $videos = array_values($videos);
                $quotes = [
                    ['name' => 'MBA graduate', 'role' => 'Dubai', 'category' => 'STUDENT'],
                    ['name' => 'MBA graduate', 'role' => 'Abu Dhabi', 'category' => 'STUDENT'],
                    ['name' => 'MSc graduate', 'role' => 'Sharjah', 'category' => 'STUDENT'],
                ];
                foreach ($quotes as $i => $q) {
                    if (! isset($videos[$i])) {
                        $videos[$i] = [
                            'video_url' => null,
                            'thumbnail' => null,
                            'thumbnail_asset_id' => null,
                        ];
                    }
                    $videos[$i]['name'] = $q['name'];
                    $videos[$i]['role'] = $q['role'];
                    $videos[$i]['category'] = $q['category'];
                }

                return $videos;
            });
        }

        $this->migrator->update('mba_masters_testimonials.label', fn () => 'Testimonials');
        $this->migrator->update('mba_masters_testimonials.heading', fn () => 'What students say in their own words');
        $this->migrator->update(
            'mba_masters_testimonials.intro',
            fn () => 'Recent voices from Dubai, Abu Dhabi, and Sharjah.'
        );
        $this->migrator->update('mba_masters_testimonials.items', function ($items) {
            $items = json_decode(json_encode($items ?? []), true) ?: [];
            $items = array_values($items);
            $quotes = [
                [
                    'name' => 'MBA graduate',
                    'role' => 'Dubai',
                    'quote' => 'The evening format meant I never missed a single day of work.',
                ],
                [
                    'name' => 'MBA graduate',
                    'role' => 'Abu Dhabi',
                    'quote' => 'My advisor in Sharjah handled everything, including how my employer could support the fees.',
                ],
                [
                    'name' => 'MSc graduate',
                    'role' => 'Sharjah',
                    'quote' => 'The final project became a real proposal at my company.',
                ],
            ];
            foreach ($quotes as $i => $q) {
                $photo = $items[$i]['photo'] ?? null;
                $photoAsset = $items[$i]['photo_asset_id'] ?? null;
                $items[$i] = array_merge($q, [
                    'photo' => $photo,
                    'photo_asset_id' => $photoAsset,
                ]);
            }

            return array_slice($items, 0, max(3, count($quotes)));
        });

        $this->migrator->update('mba_masters_compare.label', fn () => 'Comparison');
        $this->migrator->update(
            'mba_masters_compare.heading',
            fn () => 'Online MBA vs Classroom MBA: A Fair Comparison'
        );
        $this->migrator->update(
            'mba_masters_compare.intro',
            fn () => 'If you have ever weighed a part-time MBA on campus against an online one, this table is the honest version of that argument. Same degree standard, very different life around it.'
        );
        $this->migrator->update('mba_masters_compare.col_online', fn () => 'This online MBA');
        $this->migrator->update('mba_masters_compare.col_traditional', fn () => 'Classroom MBA');
        $this->migrator->update('mba_masters_compare.rows', fn () => [
            [
                'criterion' => 'Total fees',
                'online' => 'AED 16,000 to 40,000',
                'traditional' => 'Typically AED 80,000 to 200,000+',
            ],
            [
                'criterion' => 'Commute',
                'online' => 'Zero',
                'traditional' => '3 to 5 hours a week in traffic',
            ],
            [
                'criterion' => 'Class timing',
                'online' => 'Evenings and weekends, from home',
                'traditional' => 'Fixed campus timetable',
            ],
            [
                'criterion' => 'Visa needed',
                'online' => 'No',
                'traditional' => 'Yes for international campuses',
            ],
            [
                'criterion' => 'Study while working',
                'online' => 'Yes, designed for it',
                'traditional' => 'Often requires a break',
            ],
            [
                'criterion' => 'Award on certificate',
                'online' => 'University-awarded degree',
                'traditional' => 'University-awarded degree',
            ],
            [
                'criterion' => 'Networking',
                'online' => 'Live cohort events and an active WhatsApp community',
                'traditional' => 'Campus cohorts',
            ],
        ]);
        $this->migrator->update('mba_masters_compare.blocks', fn () => [
            [
                'title' => 'Fast-track or standard: your choice',
                'text' => 'A fast-track MBA compresses the same modules for experienced applicants. Standard pace spreads the load across busier seasons. Pick with your calendar; the advisor helps you choose.',
            ],
            [
                'title' => 'The same award, either way',
                'text' => 'Whichever format you choose, the certificate reads identically. The difference is how you study, not what you earn.',
            ],
        ]);
        $this->migrator->update('mba_masters_compare.cta_label', fn () => 'Ask which pace fits you');
        $this->migrator->update('mba_masters_compare.cta_url', fn () => '#mlp-enquire');

        $this->migrator->update('mba_masters_faq.label', fn () => 'FAQ');
        $this->migrator->update('mba_masters_faq.heading', fn () => 'Frequently Asked Questions');
        $this->migrator->update('mba_masters_faq.items', fn () => [
            [
                'question' => 'What are the admission requirements for an MBA?',
                'answer' => 'Most programmes ask for a bachelor\'s degree and 2 to 3 years of work experience, and none of them need a GMAT. If your background looks different, send the CV anyway. Our advisors map entry routes for experienced professionals every week, and they confirm eligibility in writing.',
            ],
            [
                'question' => 'Are these degrees recognised in the UAE?',
                'answer' => 'Degrees from an accredited institution can be submitted for MoHESR recognition, and the whole process runs electronically through official UAE channels. The study itself happens online from the UAE. Recognition depends on your category, so check it with an advisor before you enrol. The call is free.',
            ],
            [
                'question' => 'How much does an MBA cost in the UAE?',
                'answer' => 'Between AED 16,000 and 40,000 for most programmes on this page, in instalments. A campus MBA in Dubai typically costs three times that. Your advisor confirms your exact total in writing before you pay.',
            ],
            [
                'question' => 'Can I study fully online from Dubai or Abu Dhabi?',
                'answer' => 'Yes, and from Sharjah, Al Ain, or anywhere else in the GCC. Classes run on evening and weekend timings built around Gulf working hours, recordings cover the nights you miss, and there is no campus attendance and no student visa.',
            ],
            [
                'question' => 'When is the next intake?',
                'answer' => 'The September 2026 intake is open now, with later intakes through the year. Seats are capped per intake, so reserving about four weeks ahead keeps onboarding calm. Current dates are confirmed within one working day.',
            ],
            [
                'question' => 'Is there a fast-track MBA option?',
                'answer' => 'There is. The fast-track MBA compresses the full curriculum for experienced professionals. Ask an advisor if your background qualifies; it\'s a five-minute conversation.',
            ],
            [
                'question' => 'Are there scholarships or discounts available?',
                'answer' => 'MBA scholarship support for strong candidates, and early-bird discounts before each intake. Eligibility depends on profile and timing, so ask what applies to you instead of guessing.',
            ],
            [
                'question' => 'Which MBA specializations are in demand in the UAE?',
                'answer' => 'Finance, healthcare leadership, logistics and supply chain, sustainability, HR, and analytics are where the hiring is, in the UAE and across the GCC. The Specialized MBA tab above lists every concentration you can pick.',
            ],
            [
                'question' => 'How many hours per week does a part-time MBA need?',
                'answer' => 'Plan on 8 to 10 hours: evening classes, self-study, assessments. Most students do a little most days and a bit on the weekend, with recordings covering missed sessions. That rhythm beats weekend marathons, and the schedule assumes it.',
            ],
            [
                'question' => 'Will my employer in the UAE accept an online degree?',
                'answer' => 'Most do, and many sponsor staff through one. What employers actually check is the awarding university, which is why we confirm accreditation in writing before you enrol. If HR needs sponsorship paperwork, we prepare it with you.',
            ],
            [
                'question' => 'Can I study from Saudi Arabia, Oman, or Qatar?',
                'answer' => 'Yes. About half the current cohort sits in the UAE, and the rest study from Saudi Arabia, Oman, Qatar, and beyond. Support runs on Gulf time, and WhatsApp groups keep each intake talking.',
            ],
            [
                'question' => 'Do I need a student visa?',
                'answer' => 'No. You study online from where you live, so there is no student visa and your residence visa stays exactly as it is. For GCC professionals, that removes the biggest paperwork headache a foreign degree used to carry.',
            ],
        ]);

        $this->migrator->update('mba_masters_final.label', fn () => 'Next step');
        $this->migrator->update('mba_masters_final.heading', fn () => 'Your Master\'s starts with one conversation');
        $this->migrator->update(
            'mba_masters_final.intro',
            fn () => 'Send the CV or the LinkedIn profile. Within 24 hours an advisor in Sharjah maps your route, your total fees, and the September 2026 intake, in writing. No obligation, no pressure, and no student visa anywhere in the picture. Prefer WhatsApp? Message us; a person replies the same day.'
        );
        $this->migrator->update('mba_masters_final.show_form', fn () => true);
        $this->migrator->update('mba_masters_final.form_title', fn () => 'Send me the programme guide');
        $this->migrator->update('mba_masters_final.cta_primary_label', fn () => 'Apply Now');
        $this->migrator->update('mba_masters_final.cta_primary_url', fn () => '#mlp-enquire');
        $this->migrator->update('mba_masters_final.cta_secondary_label', fn () => 'WhatsApp admissions');
        $this->migrator->update('mba_masters_final.cta_secondary_url', fn () => '#mlp-enquire');

        $this->migrator->update(
            'mba_masters_seo.meta_title',
            fn () => 'Online MBA & Master\'s Degrees for the UAE and GCC | Maverick'
        );
        $this->migrator->update(
            'mba_masters_seo.meta_description',
            fn () => 'Study an Online MBA or Master\'s from Switzerland, North Cyprus, or the UK — evenings from the UAE. No visa, AED instalments, September 2026 intake open.'
        );

        if (function_exists('app') && app()->bound(\Illuminate\Contracts\Console\Kernel::class)) {
            try {
                \Illuminate\Support\Facades\Artisan::call('settings:clear-cache');
            } catch (\Throwable) {
                // Cache clear is best-effort after content sync.
            }
        }
    }
};
