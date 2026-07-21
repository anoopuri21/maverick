<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

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
    <link rel="stylesheet" href="{{ asset('assets/css/sections.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}" />

    @stack('styles')
</head>

<body class="is-loading">

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
        <span aria-hidden="true" data-lucide="message-circle-more"></span>
    </a>

    {{-- Scripts --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollToPlugin.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@studio-freight/lenis@1.0.42/dist/lenis.min.js"></script>
    <script src="https://unpkg.com/lucide@0.468.0/dist/umd/lucide.min.js"></script>

    <script src="{{ asset('assets/js/main.js') }}" defer></script>
    <script src="{{ asset('assets/js/navigation.js') }}" defer></script>
    <script src="{{ asset('assets/js/animations.js') }}" defer></script>
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