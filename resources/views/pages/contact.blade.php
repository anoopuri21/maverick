@extends('layouts.app')

@section('title', ($contactSeo->meta_title ?? 'Contact Us | Maverick Business Academy London'))
@section('meta_description', ($contactSeo->meta_description ?? 'Connect with the admissions and partnerships team at Maverick Business Academy London. Reach our Sharjah / Dubai campus for inquiries.'))

@push('head')
    @include('partials.seo-meta', ['seo' => $contactSeo])
@endpush

@if(!empty($contactSeo->custom_body_scripts))
@push('scripts')
    {!! $contactSeo->custom_body_scripts !!}
@endpush
@endif

@push('styles')
    <link rel="stylesheet" href="{{ cached_asset('assets/css/contact.css') }}" />
@endpush

@section('content')
<section class="contact-section">
    <div class="container">

        {{-- A) PAGE HERO --}}
        <div class="contact-hero" data-scroll-reveal>
            <span class="contact-hero__eyebrow">{{ $contactPage->eyebrow ?: 'Contact Us' }}</span>
            <h1 class="contact-hero__title">{{ $contactPage->heading ?: "Let's Start a Conversation" }}</h1>
            <p class="contact-hero__desc">
                {!! html_filled($contactPage->description ?? null) ? rich_html($contactPage->description ?? null) : 'Whether you are exploring our executive postgraduate pathways, seeking a corporate partnership, or require technical admissions support, our advisory team is here to assist you.' !!}
            </p>
        </div>

        {{-- B) MAIN SPLIT SECTION --}}
        <div class="contact-split-grid">

            {{-- LEFT COLUMN — Contact Information --}}
            <div class="contact-info-col">

                {{-- Address Card --}}
                @if(!empty($site->address))
                    <x-contact.info-card
                        icon="address"
                        :label="$contactPage->label_address ?? 'Campus Location'"
                        :value="$site->address"
                        :link="'https://www.google.com/maps/search/?api=1&query=' . urlencode($site->address)"
                        linkText="Get Directions"
                    />
                @endif

                {{-- Email Card --}}
                @if(!empty($site->email))
                    <x-contact.info-card
                        icon="email"
                        :label="$contactPage->label_email ?? 'Email Inquiry'"
                        :value="$site->email"
                        :link="'mailto:' . $site->email"
                    />
                @endif

                {{-- Phone Card --}}
                @if(!empty($site->phone))
                    <x-contact.info-card
                        icon="phone"
                        :label="$contactPage->label_phone ?? 'Admissions & Hotlines'"
                        :value="$site->phone"
                        :secondary="$site->phone_secondary ?? null"
                        :link="'tel:' . str_replace(' ', '', $site->phone)"
                    />
                @endif

                {{-- Office Hours Card --}}
                @if(!empty($site->office_hours))
                    <x-contact.info-card
                        icon="office_hours"
                        :label="$contactPage->label_hours ?? 'Office Hours'"
                        :value="$site->office_hours"
                    />
                @endif

                {{-- Social Icons --}}
                <div class="contact-social-section" data-scroll-reveal>
                    <span class="contact-info-card__label" style="display: block; margin: 0 0 12px 12px;">{{ $contactPage->label_social ?? 'Follow Our Insights' }}</span>
                    <x-contact.social-icons :site="$site" />
                </div>

            </div>

            {{-- RIGHT COLUMN — Contact Form --}}
            <div class="contact-card contact-form-container" data-scroll-reveal>
                <h2 class="contact-form__title">{{ $contactPage->form_title ?? 'Send Us a Message' }}</h2>
                <p class="contact-form__subtitle">{{ $contactPage->form_subtitle ?? 'Fill in the fields below, and our program directors will respond to you within 24 hours.' }}</p>

                {{-- Stylish custom alert for success message --}}
                @if(session('success'))
                    <div class="contact-alert contact-alert--success" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="contact-alert__icon" aria-hidden="true">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="m9 12 2 2 4-4"/>
                        </svg>
                        <div class="contact-alert__text">
                            {{ session('success') ?: ($contactPage->success_message ?? 'Thank you for your message. Our team will respond within 24 hours.') }}
                        </div>
                    </div>
                @endif

                <form action="{{ route('contact.submit') }}" method="POST" class="contact-form">
                    @csrf

                    {{-- Honeypot visually hidden protection field --}}
                    <div class="form-honeypot" aria-hidden="true">
                        <label for="website">Please leave this field empty</label>
                        <input type="text" id="website" name="website" value="" autocomplete="off" tabIndex="-1" />
                    </div>

                    <div class="form-group-grid">
                        {{-- Name --}}
                        <div class="form-group">
                            <label for="name" class="form-label">Full Name <span class="required-star" aria-hidden="true">*</span></label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-input @error('name') form-input--error @enderror"
                                placeholder="e.g. Alexander Vance"
                                value="{{ old('name') }}"
                                required
                            />
                            @error('name')
                                <span class="form-error-msg" role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address <span class="required-star" aria-hidden="true">*</span></label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-input @error('email') form-input--error @enderror"
                                placeholder="e.g. alex@vance.com"
                                value="{{ old('email') }}"
                                required
                            />
                            @error('email')
                                <span class="form-error-msg" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group-grid">
                        {{-- Phone --}}
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input
                                type="text"
                                id="phone"
                                name="phone"
                                class="form-input @error('phone') form-input--error @enderror"
                                placeholder="e.g. +971 50 123 4567"
                                value="{{ old('phone') }}"
                            />
                            @error('phone')
                                <span class="form-error-msg" role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Subject Reason --}}
                        <div class="form-group">
                            <label for="subject" class="form-label">Reason for Inquiry</label>
                            <select
                                id="subject"
                                name="subject"
                                class="form-select @error('subject') form-select--error @enderror"
                            >
                                <option value="General Inquiry" {{ old('subject') === 'General Inquiry' ? 'selected' : '' }}>General Inquiry</option>
                                <option value="Admissions" {{ old('subject') === 'Admissions' ? 'selected' : '' }}>Admissions</option>
                                <option value="Partnership" {{ old('subject') === 'Partnership' ? 'selected' : '' }}>Partnership</option>
                                <option value="Support" {{ old('subject') === 'Support' ? 'selected' : '' }}>Support</option>
                                <option value="Other" {{ old('subject') === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('subject')
                                <span class="form-error-msg" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Message --}}
                    <div class="form-group">
                        <label for="message" class="form-label">Your Message <span class="required-star" aria-hidden="true">*</span></label>
                        <textarea
                            id="message"
                            name="message"
                            rows="5"
                            class="form-textarea @error('message') form-textarea--error @enderror"
                            placeholder="Type your message details here..."
                            required
                        >{{ old('message') }}</textarea>
                        @error('message')
                            <span class="form-error-msg" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Submit Button --}}
                    <div class="form-group" style="margin-top: 12px;">
                        <button type="submit" class="btn btn-submit-form" id="submit-btn">
                            <span>Send Message</span>
                        </button>
                    </div>

                </form>
            </div>

        </div>

        {{-- C) MAP SECTION --}}
        @if(!empty($site->address))
            <x-contact.map :address="$site->address" />
        @endif

    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Simple client-side submit loading state handling
        const form = document.querySelector('.contact-form');
        const submitBtn = document.getElementById('submit-btn');

        if (form && submitBtn) {
            form.addEventListener('submit', () => {
                const textSpan = submitBtn.querySelector('span');
                if (textSpan) {
                    textSpan.textContent = 'Sending Message...';
                }
                submitBtn.disabled = true;
            });
        }

        // GSAP Scroll Trigger Entrance Animations
        if (window.gsap && window.ScrollTrigger) {
            gsap.registerPlugin(ScrollTrigger);

            const revealElements = document.querySelectorAll('[data-scroll-reveal]');

            revealElements.forEach((el, index) => {
                gsap.fromTo(el,
                    {
                        opacity: 0,
                        y: 30
                    },
                    {
                        opacity: 1,
                        y: 0,
                        duration: 0.8,
                        ease: 'power3.out',
                        scrollTrigger: {
                            trigger: el,
                            start: 'top 85%',
                            toggleActions: 'play none none none'
                        },
                        delay: index * 0.1
                    }
                );
            });
        }
    });
</script>
@endpush
