# Cinematic Accreditations Section - Implementation Plan

## Overview
Add a cinematic full-screen pinned-image section to the "Accreditations" page, placed between the "Accreditations & Partnerships" section and the "Awards & Recognition" section.

## Design Specs
- **Initial State**: A centered image (60vw x 60vh) with rounded corners (20px).
- **On Scroll**:
    - Image scales up to fill 100vw x 100vh.
    - Border radius goes to 0.
    - Section pins to the viewport.
    - A dark gradient overlay fades in.
    - Centered text fades in and moves up.
- **Completion**: Section unpins and user continues to the Awards section.

## Technical Implementation
- **Blade**: `resources/views/pages/accreditations.blade.php`
- **CSS**: `public/css/pages/accreditations.css` (appended to existing styles)
- **JS Component**: `public/assets/js/components/accreditations-cinematic.js` (GSAP ScrollTrigger)
- **JS Orchestrator**: `public/assets/js/pages/accreditations.js` (Lazy-loaded)
- **Settings**: `App\Settings\AccreditationCinematicSettings`
- **Admin**: `App\Filament\Pages\ManageAccreditationCinematic` (MediaPicker integrated)

## Phase 2 (Completed)
- Made content dynamic via Spatie Settings + Filament.
- Integrated MediaPicker for the background image.
- Updated controller to inject settings into the view.
