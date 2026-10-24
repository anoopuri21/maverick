@extends('layouts.app')

@section('title', 'Registration Form - ' . ($event->title ?? 'Event') . ' - Maverick Business Academy')
@section('meta_description', 'Register for ' . ($event->title ?? 'Event') . ' hosted by Maverick Business Academy London.')

@push('head')
    @include('partials.seo-meta', ['seo' => $eventsSeo])
@endpush

@push('styles')
<style>
    .er-page-wrapper {
        background-color: #f0f4f9;
        min-height: 100vh;
        padding: 40px 16px 80px;
        font-family: var(--font-body, system-ui, -apple-system, sans-serif);
    }

    .er-container {
        max-width: 720px;
        margin: 0 auto;
    }

    .er-header-card {
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid #dadce0;
        overflow: hidden;
        margin-bottom: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }

    .er-header-banner {
        height: 10px;
        background: #e2231a; /* Brand Red */
        width: 100%;
    }

    .er-header-content {
        padding: 24px 28px;
    }

    .er-header-title {
        font-family: var(--font-heading, inherit);
        font-size: 1.75rem;
        font-weight: 700;
        color: #1f1f1f;
        margin: 0 0 12px 0;
        line-height: 1.3;
    }

    .er-header-subtitle {
        font-size: 1rem;
        color: #3c4043;
        margin-bottom: 16px;
        line-height: 1.5;
    }

    .er-event-details-box {
        background: #f8f9fa;
        border-left: 4px solid #e2231a;
        padding: 16px;
        border-radius: 4px;
        margin-bottom: 20px;
        font-size: 0.95rem;
        color: #202124;
    }

    .er-event-details-box p {
        margin: 4px 0;
    }

    .er-header-notice {
        font-size: 0.9rem;
        color: #d93025;
        font-weight: 500;
        margin-top: 12px;
    }

    .er-card {
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid #dadce0;
        padding: 24px 28px;
        margin-bottom: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .er-card:focus-within {
        border-color: #e2231a;
        box-shadow: 0 1px 6px rgba(226, 35, 26, 0.15);
    }

    .er-label {
        display: block;
        font-size: 1.05rem;
        font-weight: 600;
        color: #202124;
        margin-bottom: 12px;
        line-height: 1.4;
    }

    .er-label .req {
        color: #d93025;
        margin-left: 2px;
    }

    .er-input-text {
        width: 100%;
        border: none;
        border-bottom: 1px solid #ccc;
        padding: 8px 0;
        font-size: 1rem;
        color: #202124;
        background: transparent;
        outline: none;
        transition: border-color 0.2s;
    }

    .er-input-text:focus {
        border-bottom: 2px solid #e2231a;
    }

    .er-textarea {
        width: 100%;
        border: 1px solid #dadce0;
        border-radius: 4px;
        padding: 12px;
        font-size: 0.95rem;
        color: #202124;
        outline: none;
        transition: border-color 0.2s;
        resize: vertical;
        min-height: 90px;
    }

    .er-textarea:focus {
        border-color: #e2231a;
    }

    .er-select {
        width: 100%;
        max-width: 320px;
        padding: 10px 12px;
        border: 1px solid #dadce0;
        border-radius: 4px;
        font-size: 0.95rem;
        color: #202124;
        outline: none;
        background-color: #fff;
    }

    .er-select:focus {
        border-color: #e2231a;
    }

    .er-radio-group {
        display: flex;
        flex-direction: column;
        gap: 14px;
        margin-top: 8px;
    }

    .er-radio-label {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 0.95rem;
        color: #202124;
        cursor: pointer;
        user-select: none;
    }

    .er-radio-input {
        width: 18px;
        height: 18px;
        accent-color: #e2231a;
        cursor: pointer;
    }

    .er-actions-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 24px;
    }

    .er-submit-btn {
        background: #111827;
        color: #ffffff;
        border: none;
        padding: 12px 32px;
        border-radius: 4px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s, transform 0.1s;
    }

    .er-submit-btn:hover {
        background: #e2231a;
    }

    .er-clear-btn {
        background: transparent;
        color: #5f6368;
        border: none;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        padding: 8px 12px;
        border-radius: 4px;
        transition: color 0.2s, background-color 0.2s;
    }

    .er-clear-btn:hover {
        color: #202124;
        background-color: rgba(0,0,0,0.05);
    }

    .er-error-text {
        color: #d93025;
        font-size: 0.85rem;
        margin-top: 6px;
    }
</style>
@endpush

@section('content')
<div class="er-page-wrapper">
    <div class="er-container">
        {{-- Back Link --}}
        <div style="margin-bottom: 16px;">
            <a href="{{ route('events.show', $event->slug) }}" style="color: #5f6368; text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 6px;">
                <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i> Back to Event Details
            </a>
        </div>

        <form action="{{ route('events.register.submit', $event->slug) }}" method="POST" id="event-registration-form">
            @csrf

            {{-- Header Card --}}
            <div class="er-header-card">
                <div class="er-header-banner"></div>
                <div class="er-header-content">
                    <h1 class="er-header-title">Registration Form – {{ $event->title }}</h1>
                    <p class="er-header-subtitle">
                        Maverick Business Academy London invites you to an exclusive masterclass / executive event.
                    </p>

                    <div class="er-event-details-box">
                        <p><strong>Date:</strong> {{ $event->event_date ? $event->event_date->format('l, d F Y') : 'To be announced' }}</p>
                        @if(!empty($event->formatted_time))
                            <p><strong>Time:</strong> {{ $event->formatted_time }}</p>
                        @endif
                        @if(!empty($event->location))
                            <p><strong>Venue:</strong> {{ $event->location }}</p>
                        @endif
                    </div>

                    <p style="margin: 0; color: #5f6368; font-size: 0.95rem;">
                        Complete the form below to confirm your participation.
                    </p>
                    <div class="er-header-notice">* Indicates required question</div>
                </div>
            </div>

            {{-- 1. Full Name --}}
            <div class="er-card">
                <label for="input-name" class="er-label">
                    Full Name <span class="req">*</span>
                </label>
                <input type="text" id="input-name" name="name" class="er-input-text" placeholder="Your answer" value="{{ old('name') }}" required>
                @error('name')
                    <div class="er-error-text">{{ $message }}</div>
                @enderror
            </div>

            {{-- 2. Phone Number --}}
            <div class="er-card">
                <label for="input-phone" class="er-label">
                    Number / Phone <span class="req">*</span>
                </label>
                <input type="tel" id="input-phone" name="phone" class="er-input-text" placeholder="Your answer" value="{{ old('phone') }}" required>
                @error('phone')
                    <div class="er-error-text">{{ $message }}</div>
                @enderror
            </div>

            {{-- 3. Email Address --}}
            <div class="er-card">
                <label for="input-email" class="er-label">
                    Email Address <span class="req">*</span>
                </label>
                <input type="email" id="input-email" name="email" class="er-input-text" placeholder="Your answer" value="{{ old('email') }}" required>
                @error('email')
                    <div class="er-error-text">{{ $message }}</div>
                @enderror
            </div>

            {{-- 4. How many guests? --}}
            <div class="er-card">
                <label for="select-guests" class="er-label">
                    How many guests? <span class="req">*</span>
                </label>
                <select id="select-guests" name="guest_count" class="er-select" required>
                    <option value="" disabled {{ old('guest_count') ? '' : 'selected' }}>Choose option</option>
                    <option value="Single" {{ old('guest_count') === 'Single' ? 'selected' : '' }}>Single</option>
                    <option value="2-5" {{ old('guest_count') === '2-5' ? 'selected' : '' }}>2-5</option>
                    <option value="5-10" {{ old('guest_count') === '5-10' ? 'selected' : '' }}>5-10</option>
                    <option value="10-15" {{ old('guest_count') === '10-15' ? 'selected' : '' }}>10-15</option>
                </select>
                @error('guest_count')
                    <div class="er-error-text">{{ $message }}</div>
                @enderror
            </div>

            {{-- 5. Will you be joining us? --}}
            <div class="er-card">
                <label class="er-label">
                    Will you be joining us for the masterclass on {{ $event->event_date ? $event->event_date->format('l, d F Y') : 'the event date' }}? <span class="req">*</span>
                </label>
                <div class="er-radio-group">
                    <label class="er-radio-label">
                        <input type="radio" name="attending_masterclass" value="Yes, I'll be there" class="er-radio-input" {{ old('attending_masterclass', "Yes, I'll be there") === "Yes, I'll be there" ? 'checked' : '' }} required>
                        <span>Yes, I'll be there</span>
                    </label>
                    <label class="er-radio-label">
                        <input type="radio" name="attending_masterclass" value="Sorry, can't make it" class="er-radio-input" {{ old('attending_masterclass') === "Sorry, can't make it" ? 'checked' : '' }}>
                        <span>Sorry, can't make it</span>
                    </label>
                </div>
                @error('attending_masterclass')
                    <div class="er-error-text">{{ $message }}</div>
                @enderror
            </div>

            {{-- Custom Dynamic Fields from Admin --}}
            @if(!empty($event->registration_fields) && is_array($event->registration_fields))
                @foreach($event->registration_fields as $index => $field)
                    @php
                        $key = "custom_{$index}";
                        $label = $field['label'] ?? 'Question ' . ($index + 1);
                        $type = $field['type'] ?? 'text';
                        $isRequired = !empty($field['is_required']);
                        $options = $field['options'] ?? [];
                    @endphp
                    <div class="er-card">
                        <label for="field-{{ $key }}" class="er-label">
                            {{ $label }}
                            @if($isRequired) <span class="req">*</span> @endif
                        </label>

                        @if($type === 'text')
                            <input type="text" id="field-{{ $key }}" name="{{ $key }}" class="er-input-text" placeholder="Your answer" value="{{ old($key) }}" @if($isRequired) required @endif>
                        @elseif($type === 'textarea')
                            <textarea id="field-{{ $key }}" name="{{ $key }}" class="er-textarea" placeholder="Your answer" @if($isRequired) required @endif>{{ old($key) }}</textarea>
                        @elseif($type === 'select')
                            <select id="field-{{ $key }}" name="{{ $key }}" class="er-select" @if($isRequired) required @endif>
                                <option value="" disabled {{ old($key) ? '' : 'selected' }}>Choose option</option>
                                @foreach($options as $opt)
                                    <option value="{{ $opt }}" {{ old($key) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        @elseif($type === 'radio')
                            <div class="er-radio-group">
                                @foreach($options as $optIndex => $opt)
                                    <label class="er-radio-label">
                                        <input type="radio" name="{{ $key }}" value="{{ $opt }}" class="er-radio-input" {{ old($key) === $opt ? 'checked' : '' }} @if($isRequired && $optIndex === 0) required @endif>
                                        <span>{{ $opt }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @endif

                        @error($key)
                            <div class="er-error-text">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach
            @endif

            {{-- Actions --}}
            <div class="er-actions-row">
                <button type="submit" class="er-submit-btn">Submit</button>
                <button type="reset" class="er-clear-btn" id="btn-clear-form">Clear form</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const clearBtn = document.getElementById('btn-clear-form');
        const form = document.getElementById('event-registration-form');

        if (clearBtn && form) {
            clearBtn.addEventListener('click', (e) => {
                if (confirm('Are you sure you want to clear all responses?')) {
                    form.reset();
                } else {
                    e.preventDefault();
                }
            });
        }
    });
</script>
@endpush
@endsection
