<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    {{-- JS availability flag (progressive enhancement) --}}
    <script>document.documentElement.classList.add('js');</script>

    {{-- SEO Meta --}}
    <title>@yield('title', 'Maverick Business Academy | Transforming Learners into Global Leaders')</title>
    <meta name="description" content="@yield('meta_description', 'Maverick Business Academy - Transforming Learners into Global Leaders.')" />

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.cdnfonts.com/css/pp-neue-montreal" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet" />

    {{-- Stylesheets --}}
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
    @if(request()->routeIs('our-story'))
    <link rel="stylesheet" href="{{ asset('assets/css/our-story.css') }}" />
    @endif
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}" />

    @stack('styles')
</head>

<body class="is-loading" style="min-height: 100vh;">

    {{-- Preloader --}}
    <div id="preloader" data-lenis-prevent></div>

    {{-- Custom Cursor --}}
    <div id="cursor-dot" data-lenis-prevent></div>
    <div id="cursor-outline" data-lenis-prevent></div>

    {{-- Navigation --}}
    @include('partials.navbar')

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials.footer')

    {{-- Floating Buttons --}}
    <button id="scroll-to-top" class="floating-action floating-action--scroll" type="button" aria-label="Scroll to top">
        <span aria-hidden="true" data-lucide="arrow-up"></span>
    </button>

    <a id="whatsapp-float" class="floating-action floating-action--whatsapp" href="https://wa.me/{{ $site->whatsapp_number }}" target="_blank" rel="noopener noreferrer" aria-label="Contact via WhatsApp">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm0 1.8a8.2 8.2 0 1 1-4.2 15.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 0 1 12 3.8z"/></svg>
    </a>

    {{-- Scripts --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollToPlugin.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@studio-freight/lenis@1.0.42/dist/lenis.min.js"></script>
    <script src="https://unpkg.com/lucide@0.468.0/dist/umd/lucide.min.js"></script>

    <script src="{{ asset('assets/js/main.js') }}" defer></script>
    <script src="{{ asset('assets/js/navigation.js') }}" defer></script>
    <script src="{{ asset('assets/js/animations-utils.js') }}" defer></script>
    <script src="{{ asset('assets/js/animations.js') }}" defer></script>
    @if(request()->routeIs('our-story'))
    <script src="{{ asset('assets/js/core/animations-core.js') }}" type="module" defer></script>
    <script src="{{ asset('assets/js/core/reveal-observer.js') }}" type="module" defer></script>
    <script src="{{ asset('assets/js/pages/our-story.js') }}" type="module" defer></script>
    @endif
    @if(request()->routeIs('accreditations'))
    <script src="{{ asset('assets/js/core/animations-core.js') }}" type="module" defer></script>
    <script src="{{ asset('assets/js/core/reveal-observer.js') }}" type="module" defer></script>
    <script src="{{ asset('assets/js/pages/accreditations.js') }}" type="module" defer></script>
    @endif
    @if(request()->routeIs('programs.show'))
    <script src="{{ asset('assets/js/pages/program-detail.js') }}" type="module" defer></script>
    @endif
    <script src="{{ asset('assets/js/partners.js') }}" defer></script>
    <script src="{{ asset('assets/js/testimonials.js') }}" defer></script>
    <script src="{{ asset('assets/js/scroll-controls.js') }}" defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) window.lucide.createIcons();
        });
    </script>

    @stack('scripts')
</body>
</html>