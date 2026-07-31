<?php

namespace App\Console\Commands;

use App\Settings\OurStoryHeroSettings;
use App\Settings\OurStoryBeginningSettings;
use App\Settings\OurStoryTodaySettings;
use App\Settings\OurStoryImpactSettings;
use App\Settings\OurStoryVisionSettings;
use Illuminate\Console\Command;

class SyncOurStoryContent extends Command
{
    protected $signature = 'our-story:sync-content';
    protected $description = 'Sync all Our Story page settings text content with exact SOP wording';

    public function handle(): int
    {
        $this->info('Syncing Our Story page settings...');

        // 1. Hero Statement
        $hero = app(OurStoryHeroSettings::class);
        $hero->heading = 'Our Story';
        $hero->description = '<p>Empowering learners, professionals, and future leaders through globally recognized education and industry-relevant learning experiences. At Maverick Business Academy, we believe that education should be accessible, practical, and transformative. Our journey began with a vision to bridge the gap between academic excellence and professional success by providing internationally recognized qualifications that empower individuals to achieve their personal and career aspirations.</p>';
        $hero->save();
        $this->line('Synced Section 1: Hero Statement.');

        // 2. How It Started
        $beginning = app(OurStoryBeginningSettings::class);
        $beginning->badge = 'How It Started';
        $beginning->heading = 'Where It All Began';
        $beginning->paragraph_1 = '<p>Founded with a commitment to making quality education accessible to working professionals and ambitious learners, Maverick Business Academy was established to create flexible learning pathways that meet the evolving demands of today\'s global workforce. Recognizing the growing need for internationally accredited qualifications, we built strong academic partnerships with reputable universities and awarding bodies, enabling learners to access globally recognized programs without compromising their professional commitments.</p>';
        $beginning->paragraph_2 = null;
        $beginning->save();
        $this->line('Synced Section 2: How It Started (Where It All Began).');

        // 3. What We Do Today
        $today = app(OurStoryTodaySettings::class);
        $today->badge = 'What We Do Today';
        $today->heading = 'Building Global Learning Opportunities';
        $today->description = '<p>Today, Maverick Business Academy serves a diverse community of students, professionals, entrepreneurs, and corporate leaders from around the world. Through our portfolio of undergraduate, postgraduate, doctoral, executive education, and professional development programs, we provide opportunities that support career advancement, leadership development, and lifelong learning. Our programs are designed to combine academic rigor with practical application, ensuring graduates are prepared to thrive in dynamic and competitive industries.</p>';
        $today->save();
        $this->line('Synced Section 3: What We Do Today (Building Global Learning Opportunities).');

        // 4. Our Impact
        $impact = app(OurStoryImpactSettings::class);
        $impact->heading = 'Transforming Careers Across Borders';
        $impact->description = '<p>Over the years, Maverick has supported learners from multiple countries in achieving academic qualifications, professional certifications, career promotions, and leadership positions. Our commitment extends beyond education—we strive to create meaningful learning experiences that help individuals unlock their potential and make a positive impact within their organizations and communities.</p>';
        // Stats are preserved by not altering stat_1_value, stat_1_label, etc.
        $impact->save();
        $this->line('Synced Section 4: Our Impact (Transforming Careers Across Borders).');

        // 5. Vision for the Future
        $vision = app(OurStoryVisionSettings::class);
        $vision->heading = 'Looking Ahead';
        $vision->description = '<p>As we continue to expand our global network of academic and industry partnerships, our focus remains clear: to become a trusted international learning partner that delivers innovative, flexible, and career-focused education for future generations. We are committed to shaping leaders, fostering innovation, and creating opportunities that inspire growth, transformation, and success.</p>';
        $vision->save();
        $this->line('Synced Section 5: Vision for the Future (Looking Ahead).');

        $this->info('Our Story settings text content sync complete!');
        return self::SUCCESS;
    }
}
