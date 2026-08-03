@extends('layouts.app')

@section('title', 'Accreditations & Recognitions - Maverick Business Academy')
@section('meta_description', 'Explore Maverick Business Academy\'s accreditations, partnerships with leading universities, and industry recognition awards.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/accreditations-new.css') }}">
@endpush

@section('content')
<div class="page-accreditations accred">

@php
    // ═══════════════════════════════════════════
    // DATA — Accreditations by Category (SOP Flow)
    // ═══════════════════════════════════════════

    $accreditationCategories = [
        [
            'id' => 'partner-universities',
            'icon' => 'graduation-cap',
            'title' => 'Partner Universities',
            'subtitle' => 'Academic partnerships with globally recognised institutions',
            'items' => [
                ['code' => 'UOL', 'name' => 'University of London', 'desc' => 'Official academic partnership for undergraduate and postgraduate business programmes.'],
                ['code' => 'UON', 'name' => 'University of Northampton', 'desc' => 'Validated degree pathways and articulation agreements for seamless progression.'],
                ['code' => 'ARU', 'name' => 'Anglia Ruskin University', 'desc' => 'Joint delivery of executive education and professional development programmes.'],
                ['code' => 'NCFE', 'name' => 'NCFE CACHE', 'desc' => 'Approved centre for NCFE qualifications in business and professional development.'],
            ],
        ],
        [
            'id' => 'institutional-memberships',
            'icon' => 'shield',
            'title' => 'Institutional Memberships',
            'subtitle' => 'Memberships with leading professional and academic bodies',
            'items' => [
                ['code' => 'BAC', 'name' => 'British Accreditation Council', 'desc' => 'Accredited institution meeting UK standards for independent higher education.'],
                ['code' => 'QAA', 'name' => 'Quality Assurance Agency', 'desc' => 'Reviewed by QAA for educational oversight and quality standards.'],
                ['code' => 'ASIC', 'name' => 'ASIC Accreditation', 'desc' => 'Accreditation Service for International Schools, Colleges and Universities.'],
                ['code' => 'UKENIC', 'name' => 'UK ENIC (NARIC)', 'desc' => 'Recognised institution with UK National Information Centre for credential evaluation.'],
            ],
        ],
        [
            'id' => 'international-recognition',
            'icon' => 'globe',
            'title' => 'International Recognition',
            'subtitle' => 'Global recognition and partnerships across continents',
            'items' => [
                ['code' => 'GAU', 'name' => 'Girne American University', 'desc' => 'International academic partnership for MBA and business programmes.'],
                ['code' => 'RBS', 'name' => 'Rushford Business School', 'desc' => 'Swiss-based business school partnership for executive education.'],
                ['code' => 'UCA', 'name' => 'University for the Creative Arts', 'desc' => 'UK specialist university partnership for creative business programmes.'],
                ['code' => 'ICEF', 'name' => 'ICEF Certified Agency', 'desc' => 'Certified for international student recruitment excellence.'],
            ],
        ],
        [
            'id' => 'regulatory-approvals',
            'icon' => 'shield-check',
            'title' => 'Regulatory Approvals',
            'subtitle' => 'Government and regulatory body approvals',
            'items' => [
                ['code' => 'OfS', 'name' => 'Office for Students', 'desc' => 'Registered with the independent regulator for higher education in England.'],
                ['code' => 'HO', 'name' => 'Home Office Sponsor', 'desc' => 'Licensed Student Sponsor for international student recruitment.'],
                ['code' => 'DfE', 'name' => 'Department for Education', 'desc' => 'Recognised provider meeting UK education standards.'],
                ['code' => 'SFA', 'name' => 'Skills Funding Agency', 'desc' => 'Approved for government-funded training programmes.'],
            ],
        ],
    ];

    // Awards & Recognition (SOP Section 2)
    $awardsCategories = [
        [
            'title' => 'Education Awards',
            'items' => [
                ['title' => 'Best Emerging Business School 2024', 'org' => 'Education Today Awards', 'image' => 'https://images.pexels.com/photos/2678468/pexels-photo-2678468.jpeg?w=600'],
                ['title' => 'Excellence in Online Learning 2023', 'org' => 'EdTech Breakthrough', 'image' => 'https://images.pexels.com/photos/7092613/pexels-photo-7092613.jpeg?w=600'],
                ['title' => 'Innovation in Executive Education 2023', 'org' => 'British Education Awards', 'image' => 'https://images.pexels.com/photos/3184465/pexels-photo-3184465.jpeg?w=600'],
            ],
        ],
        [
            'title' => 'Industry Recognition',
            'items' => [
                ['title' => 'Top 50 Global Online MBA', 'org' => 'QS World Rankings', 'image' => 'https://images.pexels.com/photos/3769021/pexels-photo-3769021.jpeg?w=600'],
                ['title' => 'Most Innovative Education Provider', 'org' => 'Forbes Education', 'image' => 'https://images.pexels.com/photos/3184339/pexels-photo-3184339.jpeg?w=600'],
                ['title' => 'Rising Stars in Business Education', 'org' => 'Bloomberg', 'image' => 'https://images.pexels.com/photos/3184291/pexels-photo-3184291.jpeg?w=600'],
            ],
        ],
        [
            'title' => 'Media Features',
            'items' => [
                ['title' => 'Top 100 European Business Schools', 'org' => 'Financial Times', 'image' => 'https://images.pexels.com/photos/590022/pexels-photo-590022.jpeg?w=600'],
                ['title' => 'Best Online MBA Programmes', 'org' => 'The Economist', 'image' => 'https://images.pexels.com/photos/3183197/pexels-photo-3183197.jpeg?w=600'],
                ['title' => 'Top UK Business Qualifications', 'org' => 'The Guardian', 'image' => 'https://images.pexels.com/photos/1181533/pexels-photo-1181533.jpeg?w=600'],
            ],
        ],
        [
            'title' => 'Conference Participation',
            'items' => [
                ['title' => 'Global Education Summit 2024', 'org' => 'Keynote Speaker', 'image' => 'https://images.pexels.com/photos/2774556/pexels-photo-2774556.jpeg?w=600'],
                ['title' => 'EdTech World Forum 2023', 'org' => 'Panel Moderator', 'image' => 'https://images.pexels.com/photos/1181406/pexels-photo-1181406.jpeg?w=600'],
                ['title' => 'International Business Education Conference', 'org' => 'Featured Presenter', 'image' => 'https://images.pexels.com/photos/2774556/pexels-photo-2774556.jpeg?w=600'],
            ],
        ],
    ];
@endphp

{{-- ═══════════════════════════════════════════
     HERO SECTION
═══════════════════════════════════════════ --}}
<section class="accred-hero" style="background-image: url('https://images.pexels.com/photos/267885/pexels-photo-267885.jpeg?auto=compress&cs=tinysrgb&w=1920');">
    <div class="accred-hero__overlay"></div>
    <div class="container accred-hero__content">
        <span class="accred-hero__tag">ACCREDITATIONS & RECOGNITIONS</span>
        <h1 class="accred-hero__heading">
            Globally Recognised,
            <em>Locally Trusted</em>
        </h1>
        <p class="accred-hero__description">
            Our commitment to excellence is validated by the world's most respected accreditation bodies, 
            regulatory authorities, and industry partners.
        </p>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     SECTION 1: ACCREDITATIONS (Draggable Slider)
═══════════════════════════════════════════ --}}
<section class="accreditations section-wrapper" aria-label="Accreditations">
    <div class="container">
        
        <div class="section-heading-block">
            <span class="section-label">OUR CREDENTIALS</span>
            <h2 class="section-heading">
                Accreditations <em>& Partnerships</em>
            </h2>
            <p class="section-subheading">
                One comprehensive flow of our university partnerships, institutional memberships, 
                international recognition, and regulatory approvals.
            </p>
        </div>

        {{-- Category Tabs --}}
        <div class="accreditations__tabs">
            @foreach($accreditationCategories as $index => $category)
            <button class="accreditations__tab {{ $index === 0 ? 'accreditations__tab--active' : '' }}" 
                    data-tab="{{ $category['id'] }}">
                <span class="accreditations__tab-icon" data-lucide="{{ $category['icon'] }}"></span>
                {{ $category['title'] }}
            </button>
            @endforeach
        </div>

        {{-- Draggable Slider for Each Category --}}
        @foreach($accreditationCategories as $category)
        <div class="accreditations__slider-section" id="{{ $category['id'] }}" 
             style="{{ !$loop->first ? 'display: none;' : '' }}">
            
            <div class="accreditations__slider-header">
                <h3 class="accreditations__slider-title">{{ $category['title'] }}</h3>
                <p class="accreditations__slider-subtitle">{{ $category['subtitle'] }}</p>
            </div>

            <div class="accreditations__carousel" data-carousel>
                <div class="accreditations__carousel-track" data-carousel-track>
                    {{-- Duplicate for infinite loop --}}
                    @for($r = 0; $r < 2; $r++)
                        @foreach($category['items'] as $item)
                        <div class="accreditations__card" data-card>
                            <div class="accreditations__card-logo">
                                <span>{{ $item['code'] }}</span>
                            </div>
                            <h4 class="accreditations__card-name">{{ $item['name'] }}</h4>
                            <p class="accreditations__card-desc">{{ $item['desc'] }}</p>
                            <div class="accreditations__card-badge">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                                    <polyline points="22 4 12 14.01 9 11.01"/>
                                </svg>
                                <span>VERIFIED</span>
                            </div>
                        </div>
                        @endforeach
                    @endfor
                </div>
            </div>
        </div>
        @endforeach

    </div>
</section>


{{-- ═══════════════════════════════════════════
     SECTION 2: AWARDS & RECOGNITION
═══════════════════════════════════════════ --}}
<section class="awards section-wrapper section--light" aria-label="Awards & Recognition">
    <div class="container">
        
        <div class="section-heading-block">
            <span class="section-label">ACHIEVEMENTS</span>
            <h2 class="section-heading">
                Awards <em>& Recognition</em>
            </h2>
            <p class="section-subheading">
                Our commitment to excellence has been recognised by leading education bodies worldwide.
            </p>
        </div>

        {{-- Awards Categories --}}
        <div class="awards__categories">
            @foreach($awardsCategories as $category)
            <div class="awards__category">
                <h3 class="awards__category-title">{{ $category['title'] }}</h3>
                
                <div class="awards__grid">
                    @foreach($category['items'] as $award)
                    <article class="awards__card">
                        <div class="awards__card-image">
                            <img src="{{ $award['image'] }}" 
                                 alt="{{ $award['title'] }}" 
                                 loading="lazy" 
                                 decoding="async" />
                        </div>
                        <div class="awards__card-content">
                            <h4 class="awards__card-title">{{ $award['title'] }}</h4>
                            <p class="awards__card-org">{{ $award['org'] }}</p>
                        </div>
                    </article>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

</div>

@include('sections.final-cta')

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching
    const tabs = document.querySelectorAll('.accreditations__tab');
    const sections = document.querySelectorAll('.accreditations__slider-section');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetId = this.dataset.tab;
            
            // Update active tab
            tabs.forEach(t => t.classList.remove('accreditations__tab--active'));
            this.classList.add('accreditations__tab--active');
            
            // Show target section
            sections.forEach(s => {
                s.style.display = s.id === targetId ? 'block' : 'none';
            });
        });
    });

    // Draggable carousel
    document.querySelectorAll('[data-carousel]').forEach(carousel => {
        const track = carousel.querySelector('[data-carousel-track]');
        if (!track) return;

        let isDragging = false;
        let startX = 0;
        let scrollLeft = 0;
        let currentTranslate = 0;
        let prevTranslate = 0;
        let isAutoSliding = true;
        let animationId = null;

        const getTrackWidth = () => track.scrollWidth / 2;

        // Auto-slide
        function autoSlide() {
            if (!isAutoSliding || isDragging) return;
            currentTranslate -= 0.5;
            if (Math.abs(currentTranslate) >= getTrackWidth()) {
                currentTranslate = 0;
            }
            track.style.transform = `translateX(${currentTranslate}px)`;
            animationId = requestAnimationFrame(autoSlide);
        }

        function startAutoSlide() {
            isAutoSliding = true;
            autoSlide();
        }

        function stopAutoSlide() {
            isAutoSliding = false;
            if (animationId) cancelAnimationFrame(animationId);
        }

        // Mouse events
        carousel.addEventListener('mousedown', (e) => {
            isDragging = true;
            startX = e.pageX;
            prevTranslate = currentTranslate;
            stopAutoSlide();
        });

        carousel.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            e.preventDefault();
            currentTranslate = prevTranslate + (e.pageX - startX) * 1.5;
            track.style.transform = `translateX(${currentTranslate}px)`;
        });

        carousel.addEventListener('mouseup', () => {
            isDragging = false;
            startAutoSlide();
        });

        carousel.addEventListener('mouseleave', () => {
            if (isDragging) {
                isDragging = false;
                startAutoSlide();
            }
        });

        // Touch events
        carousel.addEventListener('touchstart', (e) => {
            isDragging = true;
            startX = e.touches[0].pageX;
            prevTranslate = currentTranslate;
            stopAutoSlide();
        }, { passive: true });

        carousel.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            currentTranslate = prevTranslate + (e.touches[0].pageX - startX) * 1.5;
            track.style.transform = `translateX(${currentTranslate}px)`;
        }, { passive: true });

        carousel.addEventListener('touchend', () => {
            isDragging = false;
            startAutoSlide();
        });

        // Hover pause
        carousel.addEventListener('mouseenter', stopAutoSlide);
        carousel.addEventListener('mouseleave', () => {
            if (!isDragging) startAutoSlide();
        });

        // Start auto-slide
        autoSlide();
    });
});
</script>
@endpush
