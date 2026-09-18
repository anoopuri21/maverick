<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Settings\MbaMastersHeroSettings;
use App\Settings\MbaMastersTrustSettings;
use App\Settings\MbaMastersOverviewSettings;
use App\Settings\MbaMastersWhySettings;
use App\Settings\MbaMastersJourneySettings;
use App\Settings\MbaMastersMbaSettings;
use App\Settings\MbaMastersMastersSettings;
use App\Settings\MbaMastersClassSettings;
use App\Settings\MbaMastersFeesSettings;
use App\Settings\MbaMastersCareerSettings;
use App\Settings\MbaMastersAlumniSettings;
use App\Settings\MbaMastersPartnersSettings;
use App\Settings\MbaMastersLearningSettings;
use App\Settings\MbaMastersTestimonialsSettings;
use App\Settings\MbaMastersCompareSettings;
use App\Settings\MbaMastersFaqSettings;
use App\Settings\MbaMastersFinalSettings;
use App\Settings\MbaMastersSeoSettings;

/**
 * Sync PDF exact content to DB via Spatie Settings
 * Source: uploads/Masters-landing-content-client-gcc-uae.pdf
 * Usage: php artisan mba:sync-pdf
 */
class SyncMbaMastersPdfContent extends Command
{
    protected $signature = 'mba:sync-pdf {--force : Force without confirmation}';
    protected $description = 'Sync Masters landing PDF exact content (18 sections) to database settings - SAME TO SAME, no word change';

    public function handle(): int
    {
        if (!$this->option('force') && !$this->confirm('This will REPLACE all MBA Masters landing content with PDF exact content. Continue?', false)) {
            $this->info('Aborted.');
            return self::SUCCESS;
        }

        $this->info('🔄 Syncing PDF exact content to MBA Masters landing...');

        // 1. HERO
        $this->syncSettings(MbaMastersHeroSettings::class, [
            'eyebrow' => 'International degrees. No visa needed. Study from the UAE.',
            'headline' => 'Online MBA & Master\'s Degrees for the UAE and GCC',
            'subheading' => 'University-awarded Master\'s degrees designed for full-time professionals. Choose an MBA or a Master\'s from Switzerland, North Cyprus, or the UK without leaving your job, without relocating, and without a student visa. The September 2026 intake is now open.',
            'form_title' => 'Get the programme guide',
            'cta_primary_label' => 'Apply Now',
            'cta_primary_url' => '#mlp-enquire',
            'cta_secondary_label' => 'Request Fee Plan',
            'cta_secondary_url' => '#mlp-fees',
            'cta_tertiary_label' => 'Speak to an Advisor',
            'cta_tertiary_url' => '#mlp-enquire',
        ], 'HERO');

        // 2. TRUST
        $this->syncSettings(MbaMastersTrustSettings::class, [
            'label' => 'Rated 4.9 out of 5 by professionals who studied with us',
            'quote' => '"One of the best decisions I have made. The degree is genuinely university-awarded, and my employer in Abu Dhabi had zero questions about its validity." — Rajesh Menon, MBA graduate, Abu Dhabi',
            'stats' => [
                ['value' => '4.9/5', 'label' => 'average student rating (Trustpilot)'],
                ['value' => '9,000+', 'label' => 'Learners Empowered'],
                ['value' => '100% online', 'label' => 'No visa. No relocation.'],
                ['value' => '4,500', 'label' => 'Students Supported'],
                ['value' => '20+', 'label' => 'University Partners'],
            ],
        ], 'TRUST');

        // 3. OVERVIEW
        $this->syncSettings(MbaMastersOverviewSettings::class, [
            'index' => '03',
            'label' => 'Program overview',
            'heading' => 'MBA in Dubai: What This Programme Gives You',
            'intro' => 'An MBA in Dubai for working professionals should change how you work without pausing your life. From week one to graduation, every cohort gets the five things below.',
            'items' => [
                ['title' => 'A strong learning community with powerful networking', 'text' => 'You study alongside founders, bankers, and government specialists from the UAE, Saudi Arabia, Oman, and Qatar. The live-class circles and WhatsApp groups that carry you through the degree become your professional network after it.'],
                ['title' => 'ASK Quotient development', 'text' => 'Every module builds the three things employers screen for: Attitude, Skills, and Knowledge. Your success coach tracks your growth across all three, so you graduate measurably stronger, with evidence to show for it.'],
                ['title' => 'Skills-based MBA learning', 'text' => 'Assignments are built as workplace deliverables: a business plan, a market entry analysis, a team leadership review. You submit work you can reuse at your job, and your manager sees the difference before you graduate.'],
                ['title' => 'Knowledge-based MBA learning', 'text' => 'Core theory comes from your university\'s faculty, set in current Gulf business context and distilled into focused evening modules. Short readings, clear frameworks, and session recordings keep the load manageable beside a full-time role.'],
                ['title' => 'Real-world case studies', 'text' => 'Cases are drawn from live Gulf businesses, and your final project solves a problem inside your own company. Several graduates have taken that project straight into their next performance review.'],
            ],
            'cta_primary_label' => 'Explore the MBA families',
            'cta_primary_url' => '#mlp-mba',
            'cta_secondary_label' => 'Ask an advisor',
            'cta_secondary_url' => '#mlp-enquire',
        ], 'OVERVIEW');

        // 4. WHY
        $this->syncSettings(MbaMastersWhySettings::class, [
            'index' => '04',
            'label' => 'Why choose Maverick',
            'heading' => 'Why an MBA for working professionals in UAE makes sense right now',
            'intro' => 'A Master\'s is not a theory course. It is a working professional\'s fastest route to a wider role, a stronger salary case, and international recognition, without pausing income.',
            'chapters' => [
                ['title' => 'Learn without pausing your salary', 'text' => 'Classroom-based programmes ask you to quit, relocate, or wait for a weekend slot. This degree runs in the evenings and on weekends, live from the UAE, a genuinely flexible MBA schedule that keeps your salary intact. You study after office hours, apply what you learn the very next working day, and never lose a single dirham of income to your education.'],
                ['title' => 'Your certificate comes from the university itself', 'text' => 'Your certificate is issued by the awarding university, with the same academic standing as on-campus study. It is recognised worldwide, including across the GCC.'],
                ['title' => 'Built for promotion and transition', 'text' => 'Every module maps to skills UAE employers screen for: leadership, finance, operations, and strategy. The final project lets you solve a live business problem from your own workplace. Graduates regularly point to that project in interviews, because it is concrete proof that they can turn theory into results.'],
                ['title' => 'Pay in AED instalments', 'text' => 'A campus MBA in UAE cities typically costs AED 80,000 to 200,000. Online, total fees land between AED 16,000 and 40,000, with no upfront full payment and a schedule that matches your monthly salary.'],
                ['title' => 'A recognised route, explained honestly', 'text' => 'Study is delivered fully online from the UAE. Degrees from an accredited institution can be submitted for MoHESR recognition. Recognition depends on your circumstances, so confirm your category with an advisor. If recognition matters for your goal, we help you map the right paperwork early.'],
                ['title' => 'Sharjah office, not a call centre', 'text' => 'Local counsellors who know the UAE market, employer expectations, and the recognition process. Visit the office, or meet an advisor on WhatsApp. The same team stays with you from your first call to graduation.'],
            ],
        ], 'WHY');

        // 5. JOURNEY
        $this->syncSettings(MbaMastersJourneySettings::class, [
            'index' => '05',
            'label' => 'Admission journey',
            'heading' => 'How to Start: 5 Steps',
            'intro' => 'Most applicants complete the steps below in 2 to 4 weeks. An advisor walks every step with you. The September 2026 intake is now open.',
            'steps' => [
                ['title' => 'Free 15-minute eligibility check', 'text' => 'Share your CV or LinkedIn with an advisor. No documents needed yet.'],
                ['title' => 'Confirm admission requirements and fees', 'text' => 'Within 24 hours you get the right programme, total fees, and intake dates.'],
                ['title' => 'Reserve your seat for the September 2026 intake', 'text' => 'Pay the first instalment to secure it. Seats are limited per intake.'],
                ['title' => 'Enrolment and induction', 'text' => 'Receive portal access, orientation, and your study schedule in week one.'],
                ['title' => 'Start studying online', 'text' => 'Live evening classes, recorded sessions, and an assigned success coach. You know exactly what to study each week, from week one.'],
            ],
            'cta_label' => 'Start your enquiry',
            'cta_url' => '#mlp-enquire',
        ], 'JOURNEY');

        // 6. MBA
        $this->syncSettings(MbaMastersMbaSettings::class, [
            'index' => '06',
            'label' => 'MBA specializations',
            'heading' => 'MBA specializations in UAE: Pick Your MBA',
            'intro' => 'Four MBA routes on one page: twelve specializations at Rushford Business School, six MBAs and sixteen Executive MBAs at Girne American University, and a Global MBA awarded in the UK. Every programme is university-awarded and fully online. Compare them side by side, then pick the one that matches your next role. Not sure which route fits? Ask an advisor and get a recommendation within 24 hours. The right pick depends on your experience and your next role, and the call is free.',
            'tabs' => [
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
                    'programs' => [['title' => 'Global MBA']],
                ],
                [
                    'key' => 'uws-mba',
                    'label' => 'MBA in International Business, University of the West of Scotland (UK)',
                    'description' => 'A UK-awarded MBA focused on cross-border trade, global strategy, and international teams. A strong fit for professionals in logistics, import-export, and regional HQ roles.',
                    'programs' => [['title' => 'MBA in International Business']],
                ],
            ],
        ], 'MBA CATEGORIES');

        // 7. MASTERS
        $this->syncSettings(MbaMastersMastersSettings::class, [
            'index' => '07',
            'label' => 'Master\'s programs',
            'heading' => 'Master\'s Degrees Beyond the MBA',
            'intro' => 'The same award route, applied to high-demand fields: sustainability, supply chain, economics, healthcare, and law.',
            'universities' => [
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
                    'programs' => [['title' => 'Master of Laws']],
                ],
            ],
            'trending_title' => 'Trending|Picks',
            'trending' => [
                ['label' => 'Affordable MBA in Finance', 'percent' => 82, 'note' => 'Rushford Business School. Online, instalments available.'],
                ['label' => 'Affordable MBA in Human Resource Management', 'percent' => 75, 'note' => 'Rushford Business School. Online, instalments available.'],
                ['label' => 'Affordable MBA in Healthcare Leadership', 'percent' => 70, 'note' => 'Rushford Business School. Online, instalments available.'],
            ],
        ], 'MASTERS CATEGORIES');

        // 8 & 9. CLASS + COHORT
        $this->syncSettings(MbaMastersClassSettings::class, [
            'index' => '08',
            'label' => 'Class of 2025',
            'heading' => 'Your Classmates: UAE and GCC Professionals',
            'intro' => 'The most diverse cohort we have run in the Gulf: founders, government specialists, bankers, and senior operators from the UAE, Saudi Arabia, Oman, and Qatar. You learn from each other as much as from the faculty. Group work brings four markets into one discussion, which is exactly how regional business runs.',
            'audience' => 'The average student on this page works full time in the UAE or the wider Gulf and studies in the evenings and on weekends. Most join to move into senior roles without taking a career break.',
            'metrics' => [
                ['value' => '35', 'label' => 'median age in the current cohort'],
                ['value' => '9 years', 'label' => 'of average work experience'],
                ['value' => '68%', 'label' => 'hold mid-level or senior roles'],
                ['value' => '100%', 'label' => 'employed full time while studying'],
                ['value' => '80%+', 'label' => 'sponsored or supported by their employer'],
            ],
            'regions' => [
                ['name' => 'UAE', 'note' => 'around half the cohort · Dubai, Abu Dhabi, and Sharjah'],
                ['name' => 'Saudi Arabia', 'note' => 'about one quarter · Riyadh, Jeddah, and the NEOM region'],
                ['name' => 'Oman', 'note' => 'growing every intake · Muscat'],
                ['name' => 'Qatar', 'note' => 'steady presence · Doha'],
            ],
            'industries' => [
                ['name' => 'Energy & Oil', 'share' => '15', 'image' => null, 'image_asset_id' => null],
                ['name' => 'Logistics & Trade', 'share' => '18', 'image' => null, 'image_asset_id' => null],
                ['name' => 'Banking & Finance', 'share' => '20', 'image' => null, 'image_asset_id' => null],
                ['name' => 'Government', 'share' => '12', 'image' => null, 'image_asset_id' => null],
                ['name' => 'Healthcare', 'share' => '10', 'image' => null, 'image_asset_id' => null],
                ['name' => 'Education', 'share' => '8', 'image' => null, 'image_asset_id' => null],
                ['name' => 'Real Estate', 'share' => '9', 'image' => null, 'image_asset_id' => null],
                ['name' => 'Tech & Consulting', 'share' => '8', 'image' => null, 'image_asset_id' => null],
            ],
        ], 'CLASS + COHORT');

        // 10. FEES
        $this->syncSettings(MbaMastersFeesSettings::class, [
            'index' => '10',
            'label' => 'Fees',
            'heading' => 'MBA Fees in UAE: What You Actually Pay',
            'intro' => 'Total programme cost, no hidden lines. Across programmes, fees typically fall between AED 16,000 and 40,000, roughly one third of what a campus MBA costs in Dubai today. Every payment is confirmed in writing before your seat is reserved.',
            'note' => 'Fees depend on the programme and intake. Your advisor confirms the exact figure in writing before you pay anything.',
            'rows' => [
                ['programme' => 'MBA, Rushford (12 specializations)', 'mode' => 'Online · Hybrid · Part-time', 'fees' => 'confirmed per programme', 'duration' => '10 to 15 months', 'payment' => 'Current fee sheet on request'],
                ['programme' => 'MBA, Girne American University', 'mode' => 'Online · Hybrid · Part-time', 'fees' => 'confirmed per programme', 'duration' => '10 to 15 months', 'payment' => 'Current fee sheet on request'],
                ['programme' => 'Executive MBA, Girne American University', 'mode' => 'Online · Hybrid · Part-time', 'fees' => 'confirmed per programme', 'duration' => '12 to 18 months', 'payment' => 'Current fee sheet on request'],
                ['programme' => 'Global MBA, UCA with Rushford', 'mode' => 'Online · Hybrid · Part-time', 'fees' => 'confirmed per programme', 'duration' => '10 to 15 months', 'payment' => 'Current fee sheet on request'],
                ['programme' => 'MSc (Rushford, Girne) and LLM (Wolverhampton)', 'mode' => 'Online · Hybrid · Part-time', 'fees' => 'confirmed per programme', 'duration' => '8 to 18 months', 'payment' => 'Current fee sheet on request'],
                ['programme' => 'MBA in International Business, UWS', 'mode' => 'Online · Hybrid · Part-time', 'fees' => 'confirmed per programme', 'duration' => 'confirmed per programme', 'payment' => 'Current fee sheet on request'],
            ],
        ], 'FEES');

        // 11. CAREER
        $this->syncSettings(MbaMastersCareerSettings::class, [
            'index' => '11',
            'label' => 'Career stories',
            'heading' => 'What UAE Graduates Did Next',
            'intro' => 'Recent outcomes from students across the Gulf. Every story below started with a full-time job and evening study.',
            'stories' => [
                ['name' => 'Ahmed', 'country' => 'Dubai', 'program' => 'MBA in Logistics & Supply Chain Management', 'previous_role' => 'Operations Lead', 'current_role' => 'Regional operations role', 'quote' => 'Moved into a regional operations role six months after graduating. His employer supported the fees.', 'portrait' => null],
                ['name' => 'Fatima', 'country' => 'Riyadh', 'program' => 'MBA in Human Resource Management', 'previous_role' => 'HR Manager', 'current_role' => 'Promoted before course ended', 'quote' => 'Used the final project to redesign her company\'s talent framework. Promoted before the course ended.', 'portrait' => null],
                ['name' => 'Khalid', 'country' => 'Doha', 'program' => 'Master of Business Administration', 'previous_role' => 'Branch Manager', 'current_role' => 'Took over a second branch', 'quote' => 'Took over a second branch within a year. The finance modules changed how he reads a P&L.', 'portrait' => null],
                ['name' => 'Sara', 'country' => 'Abu Dhabi', 'program' => 'MBA in Marketing', 'previous_role' => 'Marketing Executive', 'current_role' => 'Led first regional campaign', 'quote' => 'Led her first regional campaign within a year of graduating. The specialization matched her target role exactly.', 'portrait' => null],
            ],
        ], 'CAREER STORIES');

        // 12. ALUMNI
        $this->syncSettings(MbaMastersAlumniSettings::class, [
            'index' => '12',
            'label' => 'Alumni',
            'heading' => 'Alumni in the UAE and Across the GCC',
            'intro' => 'Our graduates work in UAE government departments, free zones, banks, hospital groups, and multinationals across the Gulf. The network grows with every intake, and many students arrive through referrals from colleagues who already studied with us. Ask your advisor for alumni references in your industry before you commit.',
            'trust_line' => 'Company logos shown with permission from alumni employers.',
        ], 'ALUMNI');

        // 13. PARTNERS
        $this->syncSettings(MbaMastersPartnersSettings::class, [
            'index' => '13',
            'label' => 'University partners',
            'heading' => 'International MBA Degrees from Our University Partners',
            'intro' => 'Every Master\'s on this page is awarded by the university named below. You apply once, study online, and graduate from the university that issues your certificate. One application covers admission, enrolment, and your study plan.',
            'trust_line' => 'The university awards your degree, not a third party. Your certificate is the same as an on-campus graduate\'s. Accreditation status is confirmed in writing before you enrol. Recognition guidance for the UAE is available on request.',
        ], 'PARTNERS');

        // 14. LEARNING
        $this->syncSettings(MbaMastersLearningSettings::class, [
            'index' => '14',
            'label' => 'Learning',
            'heading' => 'How Online Learning Actually Works',
            'intro' => 'Not recorded videos that gather dust. Structured learning with real people around you. The platform is built for busy schedules: focused modules, clear weekly goals, and support that replies within one working day.',
            'points' => [
                ['title' => 'Live evening classes', 'text' => 'Two live sessions per week, recorded if you miss one'],
                ['title' => 'Dedicated success coach', 'text' => 'One named coach for your whole degree, from induction to graduation'],
                ['title' => 'Online exams, from home', 'text' => 'No travel for assessments. Clear rubrics, timely feedback'],
                ['title' => 'Project on your own business', 'text' => 'Apply each module to a live challenge from your workplace'],
                ['title' => 'Career-relevant assessment', 'text' => 'Projects, presentations, and portfolios you can show your employer'],
            ],
        ], 'LEARNING');

        // 15. TESTIMONIALS
        $this->syncSettings(MbaMastersTestimonialsSettings::class, [
            'index' => '15',
            'label' => 'Testimonials',
            'heading' => 'What Students Say in Their Own Words',
            'intro' => '',
        ], 'TESTIMONIALS');

        // 16. COMPARE
        $this->syncSettings(MbaMastersCompareSettings::class, [
            'index' => '16',
            'label' => 'Comparison',
            'heading' => 'Online MBA vs Classroom MBA: A Fair Comparison',
            'intro' => 'If you have ever weighed a part-time MBA on campus against an online one, this table is the honest answer. Same degree standard, different delivery.',
            'col_online' => 'This online MBA',
            'col_traditional' => 'Classroom MBA',
            'rows' => [
                ['criterion' => 'Total fees', 'online' => 'AED 16,000 to 40,000', 'traditional' => 'Typically AED 80,000 to 200,000+'],
                ['criterion' => 'Commute', 'online' => 'Zero', 'traditional' => '3 to 5 hours a week in traffic'],
                ['criterion' => 'Class timing', 'online' => 'Evenings and weekends, from home', 'traditional' => 'Fixed campus timetable'],
                ['criterion' => 'Visa needed', 'online' => 'No', 'traditional' => 'Yes for international campuses'],
                ['criterion' => 'Study while working', 'online' => 'Yes, designed for it', 'traditional' => 'Often requires a break'],
                ['criterion' => 'Award on certificate', 'online' => 'UK university degree', 'traditional' => 'UK university degree'],
                ['criterion' => 'Networking', 'online' => 'Live cohort events and an active WhatsApp community', 'traditional' => 'Campus cohorts'],
            ],
        ], 'COMPARE');

        // 17. FAQ
        $this->syncSettings(MbaMastersFaqSettings::class, [
            'index' => '17',
            'label' => 'FAQ',
            'heading' => 'Frequently Asked Questions',
            'items' => [
                ['question' => 'What are the admission requirements for an MBA?', 'answer' => 'Most programmes ask for a bachelor\'s degree plus 2 to 3 years of work experience. No GMAT is required for the programmes on this page. If your background differs, share your CV: our advisors map alternative entry routes for experienced professionals and confirm your eligibility in writing.'],
                ['question' => 'Are these degrees recognised in the UAE?', 'answer' => 'Degrees awarded by an accredited institution can be submitted for MoHESR recognition. Recognition is 100% electronic and processed through official UAE channels. Study itself is delivered fully online from the UAE. Confirm your category with an advisor before enrolling; the guidance call is free.'],
                ['question' => 'How much does an MBA cost in the UAE?', 'answer' => 'Between AED 16,000 and 40,000 for most programmes on this page, paid in instalments. That is roughly one third of a campus MBA in Dubai. Your advisor confirms the exact total in writing before you pay anything.'],
                ['question' => 'Can I study fully online from Dubai or Abu Dhabi?', 'answer' => 'Yes. All classes run online with evening and weekend timings built for Gulf working hours. Students study from Dubai, Abu Dhabi, Sharjah, and across the GCC. There is no campus attendance and no student visa needed. Recordings mean a business trip never puts you behind.'],
                ['question' => 'When is the next intake?', 'answer' => 'The September 2026 intake is now open, with later intakes through the year. Seats per intake are limited, so reserve early. Reserving at least four weeks before the start date keeps your onboarding relaxed. Your advisor confirms current dates within one working day.'],
                ['question' => 'Is there a fast-track MBA option?', 'answer' => 'Yes. The fast-track MBA completes the full curriculum in a compressed timeline for experienced professionals. Ask your advisor whether your background qualifies and how the schedule works.'],
                ['question' => 'Are there scholarships or discounts available?', 'answer' => 'MBA scholarship support is available for strong candidates, and early-bird discounts apply ahead of each intake. Eligibility depends on your profile and timing. Ask your advisor what applies to you.'],
                ['question' => 'Which MBA specializations are in demand in the UAE?', 'answer' => 'Finance, healthcare leadership, logistics & supply chain, sustainability, HR, and analytics lead current demand across the UAE and the wider GCC. The Specialized MBA tab above lists every concentration you can pick.'],
                ['question' => 'How many hours per week does a part-time MBA need?', 'answer' => 'Plan for 8 to 10 hours a week: live evening classes, self-study, and assessments. Most students study after work and on weekends, with recordings covering any session they miss. A little study most days beats long weekend marathons, and the schedule is built for that rhythm.'],
                ['question' => 'Will my employer in the UAE accept an online degree?', 'answer' => 'UAE employers are broadly comfortable with accredited online degrees, and many sponsor staff through them. What matters most is the awarding university. We confirm accreditation in writing before you enrol, and we can prepare the documents your HR team needs for sponsorship.'],
                ['question' => 'Can I study from Saudi Arabia, Oman, or Qatar?', 'answer' => 'Yes. Around half the current cohort studies from the UAE, with the rest from Saudi Arabia, Oman, Qatar, and beyond. Support works on Gulf time for every country in the region. WhatsApp study groups run per intake, across time zones.'],
                ['question' => 'Do I need a student visa?', 'answer' => 'No. You study fully online from your current location, so no student visa is required. You keep your residence visa exactly as it is. That removes one of the biggest barriers to an international degree for GCC professionals.'],
            ],
        ], 'FAQ');

        // 18. FINAL CTA
        $this->syncSettings(MbaMastersFinalSettings::class, [
            'index' => '18',
            'label' => 'Final CTA',
            'heading' => 'Your Master\'s Starts With One Conversation',
            'intro' => 'Share your CV or LinkedIn profile, and an advisor in Sharjah will map your route, your total fees, and the September 2026 intake within 24 hours. No obligation, no pressure, and no student visa required. If a Master\'s is the right next step, this is the fastest way to confirm it. Prefer WhatsApp? Message us and an advisor replies the same day.',
            'cta_primary_label' => 'Apply Now',
            'cta_primary_url' => '#mlp-enquire',
            'cta_secondary_label' => 'Message on WhatsApp',
            'cta_secondary_url' => null,
            'show_form' => true,
            'form_title' => 'Get the programme guide',
        ], 'FINAL CTA');

        // SEO
        $this->syncSettings(MbaMastersSeoSettings::class, [
            'meta_title' => 'Online MBA & Master\'s Degrees for the UAE and GCC | Maverick Business Academy',
            'meta_description' => 'University-awarded Master\'s degrees for UAE and GCC professionals. MBA & Master\'s from Switzerland, North Cyprus, UK. 100% online, no visa, AED instalments. September 2026 intake open.',
            'meta_keywords' => 'Online MBA UAE, Online MBA GCC, MBA in Dubai, MBA in UAE for working professionals, Master degree UAE online, Online MBA fees UAE',
        ], 'SEO');

        $this->info('✅ All 18 sections synced with PDF exact content!');
        $this->info('💡 Now run: php artisan optimize:clear and check /online-mba-masters-uae');
        return self::SUCCESS;
    }

    private function syncSettings(string $class, array $data, string $label): void
    {
        try {
            $settings = app($class);
            foreach ($data as $key => $value) {
                if (property_exists($settings, $key)) {
                    $settings->$key = $value;
                }
            }
            $settings->save();
            $this->line("  ✓ {$label} ({$class})");
        } catch (\Throwable $e) {
            $this->error("  ✗ {$label} failed: {$e->getMessage()}");
            report($e);
        }
    }
}
