<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // ─── Hero ───
        $this->migrator->add('csr_hero.tag', 'SOCIAL RESPONSIBILITY');
        $this->migrator->add('csr_hero.heading_line1', 'CSR &');
        $this->migrator->add('csr_hero.heading_italic', 'Community Impact');
        $this->migrator->add('csr_hero.description', 'Creating Positive Impact Through Education, Community Engagement, and Social Responsibility. Our initiatives empower communities, promote sustainability, and foster inclusive growth.');
        $this->migrator->add('csr_hero.background_image', 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&q=80&w=1920');
        $this->migrator->add('csr_hero.background_image_asset_id', null);

        // ─── Commitment ───
        $this->migrator->add('csr_commitment.label', 'Our Values');
        $this->migrator->add('csr_commitment.heading', 'Our ');
        $this->migrator->add('csr_commitment.heading_italic', 'Commitment');
        $this->migrator->add('csr_commitment.body', 'At Maverick Business Academy, we believe education extends beyond classrooms. Through our CSR initiatives, we actively contribute to community development, educational accessibility, professional growth, and social wellbeing.');
        $this->migrator->add('csr_commitment.image_url', 'https://res.cloudinary.com/i08gwudw/image/upload/v1785846422/csr-impact_bh8qyb.png');
        $this->migrator->add('csr_commitment.image_url_asset_id', null);

        // ─── Focus Areas ───
        $this->migrator->add('csr_focus.label', 'Pillars');
        $this->migrator->add('csr_focus.heading', 'CSR Focus ');
        $this->migrator->add('csr_focus.heading_italic', 'Areas');
        $this->migrator->add('csr_focus.items', [
            [
                'title' => 'Education & Skill Development',
                'icon' => 'graduation-cap',
                'activities' => [
                    'Free educational workshops',
                    'Career guidance sessions',
                    'Teacher training programs',
                    'Student mentoring initiatives',
                ],
            ],
            [
                'title' => 'Community Engagement',
                'icon' => 'globe',
                'activities' => [
                    'Community awareness campaigns',
                    'Youth development programs',
                    'Local community partnerships',
                    'Volunteering activities',
                ],
            ],
            [
                'title' => 'Sustainability & Environment',
                'icon' => 'leaf',
                'activities' => [
                    'Paperless learning initiatives',
                    'Green office practices',
                    'Environmental awareness programs',
                    'Sustainability workshops',
                ],
            ],
            [
                'title' => 'Inclusion & Accessibility',
                'icon' => 'handshake',
                'activities' => [
                    'Scholarships',
                    'Educational support programs',
                    'Equal learning opportunities',
                    'Professional development access',
                ],
            ],
        ]);

        // ─── Gallery ───
        $this->migrator->add('csr_gallery.label', 'Our Impact In Action');
        $this->migrator->add('csr_gallery.heading', 'CSR ');
        $this->migrator->add('csr_gallery.heading_italic', 'Activities');
        $this->migrator->add('csr_gallery.items', [
            [
                'title' => 'Teachers Training Workshop 2026',
                'image' => 'https://images.pexels.com/photos/10498800/pexels-photo-10498800.jpeg',
                'image_asset_id' => null,
                'description' => 'Empowering educators through innovative classroom engagement strategies.',
            ],
            [
                'title' => 'Student Career Development Sessions',
                'image' => 'https://images.unsplash.com/photo-1515187029135-18ee286d815b?auto=format&fit=crop&q=80&w=1000',
                'image_asset_id' => null,
                'description' => 'Supporting students with career planning and employability skills.',
            ],
            [
                'title' => 'Community Education Initiatives',
                'image' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&q=80&w=1000',
                'image_asset_id' => null,
                'description' => 'Providing learning opportunities to underserved communities.',
            ],
            [
                'title' => 'Sustainability Awareness Campaign',
                'image' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&q=80&w=1000',
                'image_asset_id' => null,
                'description' => 'Promoting responsible and environmentally conscious practices.',
            ],
        ]);

        // ─── Impact Numbers ───
        $this->migrator->add('csr_impact.items', [
            ['value' => 500, 'suffix' => '+', 'label' => 'Educators Trained'],
            ['value' => 1000, 'suffix' => '+', 'label' => 'Learners Supported'],
            ['value' => 50, 'suffix' => '+', 'label' => 'Community Activities'],
            ['value' => 20, 'suffix' => '+', 'label' => 'CSR Initiatives Conducted'],
        ]);

        // ─── Scholarship ───
        $this->migrator->add('csr_scholarship.label', 'Scholarship & Educational Support');
        $this->migrator->add('csr_scholarship.heading', 'Educational Access  ');
        $this->migrator->add('csr_scholarship.heading_italic', '& Scholarships');
        $this->migrator->add('csr_scholarship.body', 'Maverick supports deserving learners through scholarship opportunities, flexible learning pathways, and professional development initiatives that help individuals achieve their educational goals.');
        $this->migrator->add('csr_scholarship.items', [
            'Teachers Training Workshops',
            'Free Masterclasses',
            'Student Development Sessions',
            'Career Guidance Programs',
            'Professional Development Webinars',
            'Educational Partnerships Benefiting Communities',
            'Scholarship Programs',
            'Industry Awareness Events',
            'Women Leadership Initiatives',
            'Youth Entrepreneurship Workshops',
        ]);

        // ─── SEO ───
        $this->migrator->add('csr_seo.meta_title', 'CSR & Community Impact | Maverick Business Academy London');
        $this->migrator->add('csr_seo.meta_description', 'Completely redesigned CSR & Community Impact page of Maverick Business Academy London — Creating Positive Impact Through Education, Community Engagement, and Social Responsibility.');
        $this->migrator->add('csr_seo.meta_keywords', null);
        $this->migrator->add('csr_seo.canonical_url', null);
        $this->migrator->add('csr_seo.robots', 'index, follow');
        $this->migrator->add('csr_seo.og_title', null);
        $this->migrator->add('csr_seo.og_description', null);
        $this->migrator->add('csr_seo.og_image_url', null);
        $this->migrator->add('csr_seo.og_type', 'website');
        $this->migrator->add('csr_seo.twitter_card', 'summary_large_image');
        $this->migrator->add('csr_seo.twitter_title', null);
        $this->migrator->add('csr_seo.twitter_description', null);
        $this->migrator->add('csr_seo.twitter_image_url', null);
        $this->migrator->add('csr_seo.schema_json', null);
        $this->migrator->add('csr_seo.custom_head_scripts', null);
        $this->migrator->add('csr_seo.custom_body_scripts', null);
    }
};
