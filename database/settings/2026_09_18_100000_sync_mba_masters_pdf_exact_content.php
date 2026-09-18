<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

/**
 * PDF Exact Content Sync - /online-mba-masters-uae
 * Source: uploads/Masters-landing-content-client-gcc-uae.pdf (12 pages, 18 sections)
 * Principle: SAME TO SAME, no word change
 * Command: php artisan migrate or php artisan mba:sync-pdf
 */

return new class extends SettingsMigration
{
    public function up(): void
    {
        // 1. HERO
        $this->migrator->update('mba_masters_hero.eyebrow', fn () => 'International degrees. No visa needed. Study from the UAE.');
        $this->migrator->update('mba_masters_hero.headline', fn () => 'Online MBA & Master\'s Degrees for the UAE and GCC');
        $this->migrator->update('mba_masters_hero.subheading', fn () => 'University-awarded Master\'s degrees designed for full-time professionals. Choose an MBA or a Master\'s from Switzerland, North Cyprus, or the UK without leaving your job, without relocating, and without a student visa. The September 2026 intake is now open.');
        $this->migrator->update('mba_masters_hero.form_title', fn () => 'Get the programme guide');
        $this->migrator->update('mba_masters_hero.cta_primary_label', fn () => 'Apply Now');
        $this->migrator->update('mba_masters_hero.cta_primary_url', fn () => '#mlp-enquire');
        $this->migrator->update('mba_masters_hero.cta_secondary_label', fn () => 'Request Fee Plan');
        $this->migrator->update('mba_masters_hero.cta_secondary_url', fn () => '#mlp-fees');
        $this->migrator->update('mba_masters_hero.cta_tertiary_label', fn () => 'Speak to an Advisor');
        $this->migrator->update('mba_masters_hero.cta_tertiary_url', fn () => '#mlp-enquire');

        // 2. TRUST
        $this->migrator->update('mba_masters_trust.label', fn () => 'Rated 4.9 out of 5 by professionals who studied with us');
        $this->migrator->update('mba_masters_trust.quote', fn () => '"One of the best decisions I have made. The degree is genuinely university-awarded, and my employer in Abu Dhabi had zero questions about its validity." — Rajesh Menon, MBA graduate, Abu Dhabi');
        $this->migrator->update('mba_masters_trust.stats', fn () => [
            ['value' => '4.9/5', 'label' => 'average student rating (Trustpilot)'],
            ['value' => '9,000+', 'label' => 'Learners Empowered'],
            ['value' => '100% online', 'label' => 'No visa. No relocation.'],
            ['value' => '4,500', 'label' => 'Students Supported'],
            ['value' => '20+', 'label' => 'University Partners'],
        ]);

        // 3. OVERVIEW
        $this->migrator->update('mba_masters_overview.index', fn () => '03');
        $this->migrator->update('mba_masters_overview.label', fn () => 'Program overview');
        $this->migrator->update('mba_masters_overview.heading', fn () => 'MBA in Dubai: What This Programme Gives You');
        $this->migrator->update('mba_masters_overview.intro', fn () => 'An MBA in Dubai for working professionals should change how you work without pausing your life. From week one to graduation, every cohort gets the five things below.');
        $this->migrator->update('mba_masters_overview.items', fn () => [
            [
                'title' => 'A strong learning community with powerful networking',
                'text' => 'You study alongside founders, bankers, and government specialists from the UAE, Saudi Arabia, Oman, and Qatar. The live-class circles and WhatsApp groups that carry you through the degree become your professional network after it.',
            ],
            [
                'title' => 'ASK Quotient development',
                'text' => 'Every module builds the three things employers screen for: Attitude, Skills, and Knowledge. Your success coach tracks your growth across all three, so you graduate measurably stronger, with evidence to show for it.',
            ],
            [
                'title' => 'Skills-based MBA learning',
                'text' => 'Assignments are built as workplace deliverables: a business plan, a market entry analysis, a team leadership review. You submit work you can reuse at your job, and your manager sees the difference before you graduate.',
            ],
            [
                'title' => 'Knowledge-based MBA learning',
                'text' => 'Core theory comes from your university\'s faculty, set in current Gulf business context and distilled into focused evening modules. Short readings, clear frameworks, and session recordings keep the load manageable beside a full-time role.',
            ],
            [
                'title' => 'Real-world case studies',
                'text' => 'Cases are drawn from live Gulf businesses, and your final project solves a problem inside your own company. Several graduates have taken that project straight into their next performance review.',
            ],
        ]);
        $this->migrator->update('mba_masters_overview.cta_primary_label', fn () => 'Explore the MBA families');
        $this->migrator->update('mba_masters_overview.cta_primary_url', fn () => '#mlp-mba');
        $this->migrator->update('mba_masters_overview.cta_secondary_label', fn () => 'Ask an advisor');
        $this->migrator->update('mba_masters_overview.cta_secondary_url', fn () => '#mlp-enquire');

        // 4. WHY
        $this->migrator->update('mba_masters_why.index', fn () => '04');
        $this->migrator->update('mba_masters_why.label', fn () => 'Why choose Maverick');
        $this->migrator->update('mba_masters_why.heading', fn () => 'Why an MBA for working professionals in UAE makes sense right now');
        $this->migrator->update('mba_masters_why.intro', fn () => 'A Master\'s is not a theory course. It is a working professional\'s fastest route to a wider role, a stronger salary case, and international recognition, without pausing income.');
        $this->migrator->update('mba_masters_why.chapters', fn () => [
            [
                'title' => 'Learn without pausing your salary',
                'text' => 'Classroom-based programmes ask you to quit, relocate, or wait for a weekend slot. This degree runs in the evenings and on weekends, live from the UAE, a genuinely flexible MBA schedule that keeps your salary intact. You study after office hours, apply what you learn the very next working day, and never lose a single dirham of income to your education.',
            ],
            [
                'title' => 'Your certificate comes from the university itself',
                'text' => 'Your certificate is issued by the awarding university, with the same academic standing as on-campus study. It is recognised worldwide, including across the GCC.',
            ],
            [
                'title' => 'Built for promotion and transition',
                'text' => 'Every module maps to skills UAE employers screen for: leadership, finance, operations, and strategy. The final project lets you solve a live business problem from your own workplace. Graduates regularly point to that project in interviews, because it is concrete proof that they can turn theory into results.',
            ],
            [
                'title' => 'Pay in AED instalments',
                'text' => 'A campus MBA in UAE cities typically costs AED 80,000 to 200,000. Online, total fees land between AED 16,000 and 40,000, with no upfront full payment and a schedule that matches your monthly salary.',
            ],
            [
                'title' => 'A recognised route, explained honestly',
                'text' => 'Study is delivered fully online from the UAE. Degrees from an accredited institution can be submitted for MoHESR recognition. Recognition depends on your circumstances, so confirm your category with an advisor. If recognition matters for your goal, we help you map the right paperwork early.',
            ],
            [
                'title' => 'Sharjah office, not a call centre',
                'text' => 'Local counsellors who know the UAE market, employer expectations, and the recognition process. Visit the office, or meet an advisor on WhatsApp. The same team stays with you from your first call to graduation.',
            ],
        ]);

        // 5. JOURNEY
        $this->migrator->update('mba_masters_journey.index', fn () => '05');
        $this->migrator->update('mba_masters_journey.label', fn () => 'Admission journey');
        $this->migrator->update('mba_masters_journey.heading', fn () => 'How to Start: 5 Steps');
        $this->migrator->update('mba_masters_journey.intro', fn () => 'Most applicants complete the steps below in 2 to 4 weeks. An advisor walks every step with you. The September 2026 intake is now open.');
        $this->migrator->update('mba_masters_journey.steps', fn () => [
            [
                'title' => 'Free 15-minute eligibility check',
                'text' => 'Share your CV or LinkedIn with an advisor. No documents needed yet.',
            ],
            [
                'title' => 'Confirm admission requirements and fees',
                'text' => 'Within 24 hours you get the right programme, total fees, and intake dates.',
            ],
            [
                'title' => 'Reserve your seat for the September 2026 intake',
                'text' => 'Pay the first instalment to secure it. Seats are limited per intake.',
            ],
            [
                'title' => 'Enrolment and induction',
                'text' => 'Receive portal access, orientation, and your study schedule in week one.',
            ],
            [
                'title' => 'Start studying online',
                'text' => 'Live evening classes, recorded sessions, and an assigned success coach. You know exactly what to study each week, from week one.',
            ],
        ]);
        $this->migrator->update('mba_masters_journey.cta_label', fn () => 'Start your enquiry');
        $this->migrator->update('mba_masters_journey.cta_url', fn () => '#mlp-enquire');

        // 6. MBA CATEGORIES
        $mbaTabs = [
            [
                'key' => 'rbs-mba',
                'label' => 'MBA, Rushford Business School (Switzerland)',
                'description' => 'Twelve specializations in one online format. Works in any industry: the curriculum is built around leadership, strategy, and decision-making. Ideal for team leads, operations managers, and consultants moving into senior roles.',
                'programs' => [
                    ['title' => 'MBA in Sustainability, Energy and Environment'],
                    ['title' => 'MBA in Strategic Management'],
                    ['title' => 'MBA in Real Estate Management'],
                    ['title' => 'MBA in Human Resource Management'],
                    ['title' => 'MBA in Marketing'],
                    ['title' => 'MBA in Logistics & Supply Chain Management'],
                    ['title' => 'MBA in Healthcare Leadership'],
                    ['title' => 'MBA in Hospitality & Tourism Management'],
                    ['title' => 'MBA in Health Economics'],
                    ['title' => 'MBA in Entrepreneurship and Innovation'],
                    ['title' => 'MBA in Finance'],
                    ['title' => 'Master of Business Administration (MBA)'],
                ],
            ],
            [
                'key' => 'gau-mba',
                'label' => 'MBA, Girne American University (North Cyprus)',
                'description' => 'Six MBAs for managers who want a specialization with an international cohort.',
                'programs' => [
                    ['title' => 'MBA in Business Management'],
                    ['title' => 'MBA in Financial Management'],
                    ['title' => 'MBA in International Business Management'],
                    ['title' => 'MBA in Management Information Systems'],
                    ['title' => 'MBA in Marketing'],
                    ['title' => 'MBA Data Science/Analytics Management'],
                ],
            ],
            [
                'key' => 'gau-emba',
                'label' => 'Executive MBA, Girne American University (North Cyprus)',
                'description' => 'Sixteen Executive MBAs for senior leaders. Cohort size is limited and classmates bring seniority, so discussions draw on real leadership experience.',
                'programs' => [
                    ['title' => 'Executive MBA in Educational Leadership'],
                    ['title' => 'Executive MBA in Media & Entertainment'],
                    ['title' => 'Executive MBA in Global Banking & Finance'],
                    ['title' => 'Executive MBA in Health & Safety Leadership'],
                    ['title' => 'Executive MBA in Renewable Energy & Sustainability'],
                    ['title' => 'Executive MBA in Tourism & Hospitality Management'],
                    ['title' => 'Executive MBA in Innovation & Entrepreneurship'],
                    ['title' => 'Executive MBA in Project Management'],
                    ['title' => 'Executive MBA in Human Resources Management'],
                    ['title' => 'Executive MBA in Supply Chain Management'],
                    ['title' => 'Executive MBA in Health Care Management'],
                    ['title' => 'Executive MBA in Engineering Management'],
                    ['title' => 'Executive MBA in Public Administration'],
                    ['title' => 'Executive MBA in Public Health'],
                    ['title' => 'Executive MBA in Digital Marketing'],
                    ['title' => 'Executive MBA in Sport Management'],
                ],
            ],
            [
                'key' => 'global-mba',
                'label' => 'Global MBA, University for the Creative Arts (UK) with Rushford',
                'description' => 'One degree with an international perspective, for careers across borders. Popular in logistics, trade, and regional HQ roles across Dubai and Abu Dhabi.',
                'programs' => [
                    ['title' => 'Global MBA'],
                ],
            ],
            [
                'key' => 'uws-mba',
                'label' => 'MBA in International Business, University of the West of Scotland (UK)',
                'description' => 'A UK-awarded MBA focused on cross-border trade, global strategy, and international teams. A strong fit for professionals in logistics, import-export, and regional HQ roles.',
                'programs' => [
                    ['title' => 'MBA in International Business'],
                ],
            ],
        ];
        $this->migrator->update('mba_masters_mba.index', fn () => '06');
        $this->migrator->update('mba_masters_mba.label', fn () => 'MBA specializations');
        $this->migrator->update('mba_masters_mba.heading', fn () => 'MBA specializations in UAE: Pick Your MBA');
        $this->migrator->update('mba_masters_mba.intro', fn () => 'Four MBA routes on one page: twelve specializations at Rushford Business School, six MBAs and sixteen Executive MBAs at Girne American University, and a Global MBA awarded in the UK. Every programme is university-awarded and fully online. Compare them side by side, then pick the one that matches your next role. Not sure which route fits? Ask an advisor and get a recommendation within 24 hours. The right pick depends on your experience and your next role, and the call is free.');
        $this->migrator->update('mba_masters_mba.tabs', fn () => $mbaTabs);

        // 7. MASTERS CATEGORIES
        $mastersUniversities = [
            [
                'name' => 'Rushford Business School (Switzerland), MSc',
                'description' => 'Specialist MSc pathways that build advanced knowledge across business and management.',
                'programs' => [
                    ['title' => 'MSc in Sustainability and Environmental Management'],
                    ['title' => 'MSc in Strategic Management'],
                    ['title' => 'MSc in Operations and Supply Chain Management'],
                    ['title' => 'MSc in International Business Management'],
                    ['title' => 'MSc in Marketing'],
                    ['title' => 'MSc in Entrepreneurship & Innovation'],
                    ['title' => 'MSc in Finance and Investment'],
                    ['title' => 'MSc in Economics'],
                    ['title' => 'MSc in Business Management'],
                ],
            ],
            [
                'name' => 'Girne American University (North Cyprus), MSc with Thesis',
                'description' => 'Thesis-based MSc programmes for professionals heading toward research or doctoral study.',
                'programs' => [
                    ['title' => 'MSc in Business Management'],
                    ['title' => 'MSc in Economics'],
                    ['title' => 'MSc in Healthcare Management'],
                    ['title' => 'MSc in Counselling Psychology'],
                ],
            ],
            [
                'name' => 'University of Wolverhampton (UK), Master of Laws',
                'description' => 'A UK-awarded LLM for professionals who need formal legal grounding alongside business roles.',
                'programs' => [
                    ['title' => 'Master of Laws'],
                ],
            ],
        ];
        $trending = [
            ['label' => 'Affordable MBA in Finance', 'percent' => 82, 'note' => 'Rushford Business School. Online, instalments available.'],
            ['label' => 'Affordable MBA in Human Resource Management', 'percent' => 75, 'note' => 'Rushford Business School. Online, instalments available.'],
            ['label' => 'Affordable MBA in Healthcare Leadership', 'percent' => 70, 'note' => 'Rushford Business School. Online, instalments available.'],
        ];
        $this->migrator->update('mba_masters_masters.index', fn () => '07');
        $this->migrator->update('mba_masters_masters.label', fn () => 'Master\'s programs');
        $this->migrator->update('mba_masters_masters.heading', fn () => 'Master\'s Degrees Beyond the MBA');
        $this->migrator->update('mba_masters_masters.intro', fn () => 'The same award route, applied to high-demand fields: sustainability, supply chain, economics, healthcare, and law.');
        $this->migrator->update('mba_masters_masters.universities', fn () => $mastersUniversities);
        $this->migrator->update('mba_masters_masters.trending_title', fn () => 'Trending|Picks');
        $this->migrator->update('mba_masters_masters.trending', fn () => $trending);

        // 8 & 9. CLASS OF 2025 + COHORT (combined in mba_masters_class)
        $this->migrator->update('mba_masters_class.index', fn () => '08');
        $this->migrator->update('mba_masters_class.label', fn () => 'Class of 2025');
        $this->migrator->update('mba_masters_class.heading', fn () => 'Your Classmates: UAE and GCC Professionals');
        $this->migrator->update('mba_masters_class.intro', fn () => 'The most diverse cohort we have run in the Gulf: founders, government specialists, bankers, and senior operators from the UAE, Saudi Arabia, Oman, and Qatar. You learn from each other as much as from the faculty. Group work brings four markets into one discussion, which is exactly how regional business runs.');
        $this->migrator->update('mba_masters_class.audience', fn () => 'The average student on this page works full time in the UAE or the wider Gulf and studies in the evenings and on weekends. Most join to move into senior roles without taking a career break.');
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
        $this->migrator->update('mba_masters_class.industries', fn () => [
            ['name' => 'Energy & Oil', 'share' => '15', 'image' => null, 'image_asset_id' => null],
            ['name' => 'Logistics & Trade', 'share' => '18', 'image' => null, 'image_asset_id' => null],
            ['name' => 'Banking & Finance', 'share' => '20', 'image' => null, 'image_asset_id' => null],
            ['name' => 'Government', 'share' => '12', 'image' => null, 'image_asset_id' => null],
            ['name' => 'Healthcare', 'share' => '10', 'image' => null, 'image_asset_id' => null],
            ['name' => 'Education', 'share' => '8', 'image' => null, 'image_asset_id' => null],
            ['name' => 'Real Estate', 'share' => '9', 'image' => null, 'image_asset_id' => null],
            ['name' => 'Tech & Consulting', 'share' => '8', 'image' => null, 'image_asset_id' => null],
        ]);

        // 10. FEES
        $this->migrator->update('mba_masters_fees.index', fn () => '10');
        $this->migrator->update('mba_masters_fees.label', fn () => 'Fees');
        $this->migrator->update('mba_masters_fees.heading', fn () => 'MBA Fees in UAE: What You Actually Pay');
        $this->migrator->update('mba_masters_fees.intro', fn () => 'Total programme cost, no hidden lines. Across programmes, fees typically fall between AED 16,000 and 40,000, roughly one third of what a campus MBA costs in Dubai today. Every payment is confirmed in writing before your seat is reserved.');
        $this->migrator->update('mba_masters_fees.note', fn () => 'Fees depend on the programme and intake. Your advisor confirms the exact figure in writing before you pay anything.');
        $this->migrator->update('mba_masters_fees.rows', fn () => [
            ['programme' => 'MBA, Rushford (12 specializations)', 'mode' => 'Online · Hybrid · Part-time', 'fees' => 'confirmed per programme', 'duration' => '10 to 15 months', 'payment' => 'Current fee sheet on request'],
            ['programme' => 'MBA, Girne American University', 'mode' => 'Online · Hybrid · Part-time', 'fees' => 'confirmed per programme', 'duration' => '10 to 15 months', 'payment' => 'Current fee sheet on request'],
            ['programme' => 'Executive MBA, Girne American University', 'mode' => 'Online · Hybrid · Part-time', 'fees' => 'confirmed per programme', 'duration' => '12 to 18 months', 'payment' => 'Current fee sheet on request'],
            ['programme' => 'Global MBA, UCA with Rushford', 'mode' => 'Online · Hybrid · Part-time', 'fees' => 'confirmed per programme', 'duration' => '10 to 15 months', 'payment' => 'Current fee sheet on request'],
            ['programme' => 'MSc (Rushford, Girne) and LLM (Wolverhampton)', 'mode' => 'Online · Hybrid · Part-time', 'fees' => 'confirmed per programme', 'duration' => '8 to 18 months', 'payment' => 'Current fee sheet on request'],
            ['programme' => 'MBA in International Business, UWS', 'mode' => 'Online · Hybrid · Part-time', 'fees' => 'confirmed per programme', 'duration' => 'confirmed per programme', 'payment' => 'Current fee sheet on request'],
        ]);
        $this->migrator->update('mba_masters_fees.cta_primary_label', fn () => 'Request Fee Plan');
        $this->migrator->update('mba_masters_fees.cta_primary_url', fn () => '#mlp-enquire');
        $this->migrator->update('mba_masters_fees.cta_secondary_label', fn () => 'Ask an advisor');
        $this->migrator->update('mba_masters_fees.cta_secondary_url', fn () => '#mlp-enquire');

        // 11. CAREER STORIES
        $this->migrator->update('mba_masters_career.index', fn () => '11');
        $this->migrator->update('mba_masters_career.label', fn () => 'Career stories');
        $this->migrator->update('mba_masters_career.heading', fn () => 'What UAE Graduates Did Next');
        $this->migrator->update('mba_masters_career.intro', fn () => 'Recent outcomes from students across the Gulf. Every story below started with a full-time job and evening study.');
        $this->migrator->update('mba_masters_career.stories', fn () => [
            [
                'name' => 'Ahmed',
                'country' => 'Dubai',
                'program' => 'MBA in Logistics & Supply Chain Management',
                'previous_role' => 'Operations Lead',
                'current_role' => 'Regional operations role',
                'quote' => 'Moved into a regional operations role six months after graduating. His employer supported the fees.',
                'portrait' => null,
                'portrait_asset_id' => null,
            ],
            [
                'name' => 'Fatima',
                'country' => 'Riyadh',
                'program' => 'MBA in Human Resource Management',
                'previous_role' => 'HR Manager',
                'current_role' => 'Promoted before course ended',
                'quote' => 'Used the final project to redesign her company\'s talent framework. Promoted before the course ended.',
                'portrait' => null,
                'portrait_asset_id' => null,
            ],
            [
                'name' => 'Khalid',
                'country' => 'Doha',
                'program' => 'Master of Business Administration',
                'previous_role' => 'Branch Manager',
                'current_role' => 'Took over a second branch',
                'quote' => 'Took over a second branch within a year. The finance modules changed how he reads a P&L.',
                'portrait' => null,
                'portrait_asset_id' => null,
            ],
            [
                'name' => 'Sara',
                'country' => 'Abu Dhabi',
                'program' => 'MBA in Marketing',
                'previous_role' => 'Marketing Executive',
                'current_role' => 'Led first regional campaign',
                'quote' => 'Led her first regional campaign within a year of graduating. The specialization matched her target role exactly.',
                'portrait' => null,
                'portrait_asset_id' => null,
            ],
        ]);

        // 12. ALUMNI
        $this->migrator->update('mba_masters_alumni.index', fn () => '12');
        $this->migrator->update('mba_masters_alumni.label', fn () => 'Alumni');
        $this->migrator->update('mba_masters_alumni.heading', fn () => 'Alumni in the UAE and Across the GCC');
        $this->migrator->update('mba_masters_alumni.intro', fn () => 'Our graduates work in UAE government departments, free zones, banks, hospital groups, and multinationals across the Gulf. The network grows with every intake, and many students arrive through referrals from colleagues who already studied with us. Ask your advisor for alumni references in your industry before you commit.');
        $this->migrator->update('mba_masters_alumni.trust_line', fn () => 'Company logos shown with permission from alumni employers.');

        // 13. PARTNERS
        $this->migrator->update('mba_masters_partners.index', fn () => '13');
        $this->migrator->update('mba_masters_partners.label', fn () => 'University partners');
        $this->migrator->update('mba_masters_partners.heading', fn () => 'International MBA Degrees from Our University Partners');
        $this->migrator->update('mba_masters_partners.intro', fn () => 'Every Master\'s on this page is awarded by the university named below. You apply once, study online, and graduate from the university that issues your certificate. One application covers admission, enrolment, and your study plan.');
        $this->migrator->update('mba_masters_partners.trust_line', fn () => 'The university awards your degree, not a third party. Your certificate is the same as an on-campus graduate\'s. Accreditation status is confirmed in writing before you enrol. Recognition guidance for the UAE is available on request.');

        // 14. LEARNING
        $this->migrator->update('mba_masters_learning.index', fn () => '14');
        $this->migrator->update('mba_masters_learning.label', fn () => 'Learning');
        $this->migrator->update('mba_masters_learning.heading', fn () => 'How Online Learning Actually Works');
        $this->migrator->update('mba_masters_learning.intro', fn () => 'Not recorded videos that gather dust. Structured learning with real people around you. The platform is built for busy schedules: focused modules, clear weekly goals, and support that replies within one working day.');
        $this->migrator->update('mba_masters_learning.points', fn () => [
            ['title' => 'Live evening classes', 'text' => 'Two live sessions per week, recorded if you miss one'],
            ['title' => 'Dedicated success coach', 'text' => 'One named coach for your whole degree, from induction to graduation'],
            ['title' => 'Online exams, from home', 'text' => 'No travel for assessments. Clear rubrics, timely feedback'],
            ['title' => 'Project on your own business', 'text' => 'Apply each module to a live challenge from your workplace'],
            ['title' => 'Career-relevant assessment', 'text' => 'Projects, presentations, and portfolios you can show your employer'],
        ]);

        // 15. TESTIMONIALS
        $this->migrator->update('mba_masters_testimonials.index', fn () => '15');
        $this->migrator->update('mba_masters_testimonials.label', fn () => 'Testimonials');
        $this->migrator->update('mba_masters_testimonials.heading', fn () => 'What Students Say in Their Own Words');
        $this->migrator->update('mba_masters_testimonials.intro', fn () => '');

        // 16. COMPARE
        $this->migrator->update('mba_masters_compare.index', fn () => '16');
        $this->migrator->update('mba_masters_compare.label', fn () => 'Comparison');
        $this->migrator->update('mba_masters_compare.heading', fn () => 'Online MBA vs Classroom MBA: A Fair Comparison');
        $this->migrator->update('mba_masters_compare.intro', fn () => 'If you have ever weighed a part-time MBA on campus against an online one, this table is the honest answer. Same degree standard, different delivery.');
        $this->migrator->update('mba_masters_compare.col_online', fn () => 'This online MBA');
        $this->migrator->update('mba_masters_compare.col_traditional', fn () => 'Classroom MBA');
        $this->migrator->update('mba_masters_compare.rows', fn () => [
            ['criterion' => 'Total fees', 'online' => 'AED 16,000 to 40,000', 'traditional' => 'Typically AED 80,000 to 200,000+'],
            ['criterion' => 'Commute', 'online' => 'Zero', 'traditional' => '3 to 5 hours a week in traffic'],
            ['criterion' => 'Class timing', 'online' => 'Evenings and weekends, from home', 'traditional' => 'Fixed campus timetable'],
            ['criterion' => 'Visa needed', 'online' => 'No', 'traditional' => 'Yes for international campuses'],
            ['criterion' => 'Study while working', 'online' => 'Yes, designed for it', 'traditional' => 'Often requires a break'],
            ['criterion' => 'Award on certificate', 'online' => 'UK university degree', 'traditional' => 'UK university degree'],
            ['criterion' => 'Networking', 'online' => 'Live cohort events and an active WhatsApp community', 'traditional' => 'Campus cohorts'],
        ]);
        $this->migrator->update('mba_masters_compare.cta_label', fn () => null);
        $this->migrator->update('mba_masters_compare.cta_url', fn () => '#mlp-enquire');

        // 17. FAQ
        $this->migrator->update('mba_masters_faq.index', fn () => '17');
        $this->migrator->update('mba_masters_faq.label', fn () => 'FAQ');
        $this->migrator->update('mba_masters_faq.heading', fn () => 'Frequently Asked Questions');
        $this->migrator->update('mba_masters_faq.items', fn () => [
            [
                'question' => 'What are the admission requirements for an MBA?',
                'answer' => 'Most programmes ask for a bachelor\'s degree plus 2 to 3 years of work experience. No GMAT is required for the programmes on this page. If your background differs, share your CV: our advisors map alternative entry routes for experienced professionals and confirm your eligibility in writing.',
            ],
            [
                'question' => 'Are these degrees recognised in the UAE?',
                'answer' => 'Degrees awarded by an accredited institution can be submitted for MoHESR recognition. Recognition is 100% electronic and processed through official UAE channels. Study itself is delivered fully online from the UAE. Confirm your category with an advisor before enrolling; the guidance call is free.',
            ],
            [
                'question' => 'How much does an MBA cost in the UAE?',
                'answer' => 'Between AED 16,000 and 40,000 for most programmes on this page, paid in instalments. That is roughly one third of a campus MBA in Dubai. Your advisor confirms the exact total in writing before you pay anything.',
            ],
            [
                'question' => 'Can I study fully online from Dubai or Abu Dhabi?',
                'answer' => 'Yes. All classes run online with evening and weekend timings built for Gulf working hours. Students study from Dubai, Abu Dhabi, Sharjah, and across the GCC. There is no campus attendance and no student visa needed. Recordings mean a business trip never puts you behind.',
            ],
            [
                'question' => 'When is the next intake?',
                'answer' => 'The September 2026 intake is now open, with later intakes through the year. Seats per intake are limited, so reserve early. Reserving at least four weeks before the start date keeps your onboarding relaxed. Your advisor confirms current dates within one working day.',
            ],
            [
                'question' => 'Is there a fast-track MBA option?',
                'answer' => 'Yes. The fast-track MBA completes the full curriculum in a compressed timeline for experienced professionals. Ask your advisor whether your background qualifies and how the schedule works.',
            ],
            [
                'question' => 'Are there scholarships or discounts available?',
                'answer' => 'MBA scholarship support is available for strong candidates, and early-bird discounts apply ahead of each intake. Eligibility depends on your profile and timing. Ask your advisor what applies to you.',
            ],
            [
                'question' => 'Which MBA specializations are in demand in the UAE?',
                'answer' => 'Finance, healthcare leadership, logistics & supply chain, sustainability, HR, and analytics lead current demand across the UAE and the wider GCC. The Specialized MBA tab above lists every concentration you can pick.',
            ],
            [
                'question' => 'How many hours per week does a part-time MBA need?',
                'answer' => 'Plan for 8 to 10 hours a week: live evening classes, self-study, and assessments. Most students study after work and on weekends, with recordings covering any session they miss. A little study most days beats long weekend marathons, and the schedule is built for that rhythm.',
            ],
            [
                'question' => 'Will my employer in the UAE accept an online degree?',
                'answer' => 'UAE employers are broadly comfortable with accredited online degrees, and many sponsor staff through them. What matters most is the awarding university. We confirm accreditation in writing before you enrol, and we can prepare the documents your HR team needs for sponsorship.',
            ],
            [
                'question' => 'Can I study from Saudi Arabia, Oman, or Qatar?',
                'answer' => 'Yes. Around half the current cohort studies from the UAE, with the rest from Saudi Arabia, Oman, Qatar, and beyond. Support works on Gulf time for every country in the region. WhatsApp study groups run per intake, across time zones.',
            ],
            [
                'question' => 'Do I need a student visa?',
                'answer' => 'No. You study fully online from your current location, so no student visa is required. You keep your residence visa exactly as it is. That removes one of the biggest barriers to an international degree for GCC professionals.',
            ],
        ]);

        // 18. FINAL CTA
        $this->migrator->update('mba_masters_final.index', fn () => '18');
        $this->migrator->update('mba_masters_final.label', fn () => 'Final CTA');
        $this->migrator->update('mba_masters_final.heading', fn () => 'Your Master\'s Starts With One Conversation');
        $this->migrator->update('mba_masters_final.intro', fn () => 'Share your CV or LinkedIn profile, and an advisor in Sharjah will map your route, your total fees, and the September 2026 intake within 24 hours. No obligation, no pressure, and no student visa required. If a Master\'s is the right next step, this is the fastest way to confirm it. Prefer WhatsApp? Message us and an advisor replies the same day.');
        $this->migrator->update('mba_masters_final.cta_primary_label', fn () => 'Apply Now');
        $this->migrator->update('mba_masters_final.cta_primary_url', fn () => '#mlp-enquire');
        $this->migrator->update('mba_masters_final.cta_secondary_label', fn () => 'Message on WhatsApp');
        $this->migrator->update('mba_masters_final.cta_secondary_url', fn () => null);
        $this->migrator->update('mba_masters_final.show_form', fn () => true);
        $this->migrator->update('mba_masters_final.form_title', fn () => 'Get the programme guide');

        // SEO
        $this->migrator->update('mba_masters_seo.meta_title', fn () => 'Online MBA & Master\'s Degrees for the UAE and GCC | Maverick Business Academy');
        $this->migrator->update('mba_masters_seo.meta_description', fn () => 'University-awarded Master\'s degrees for UAE and GCC professionals. MBA & Master\'s from Switzerland, North Cyprus, UK. 100% online, no visa, AED instalments. September 2026 intake open.');
        $this->migrator->update('mba_masters_seo.meta_keywords', fn () => 'Online MBA UAE, Online MBA GCC, MBA in Dubai, MBA in UAE for working professionals, Master degree UAE online, Online MBA fees UAE');
    }
};
