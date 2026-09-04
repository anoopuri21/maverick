# Fix: Mid-page refresh leaves upper MLP sections invisible

## Verified root cause
In [`public/assets/js/pages/mba-masters-landing.js`](public/assets/js/pages/mba-masters-landing.js), `MLP.reveal` (and several section timelines) always do `gsap.set(..., { opacity: 0 })`, then wait for ScrollTrigger `onEnter` with `once: true` and `start: "top 80%"`.

On soft/hard refresh mid-page (browser scroll restoration):
1. Init often runs while scroll is still ~0 (or ST is created before restore settles).
2. Elements above / already past the start line never get a fresh `onEnter`.
3. They stay at `opacity: 0`. Scrolling up/down does not recover them until a top-of-page hard refresh.

Same pattern exists in Class metrics/industries, Career story timelines, Journey steps, Trust fan paths.

## Fix (committed)
Centralize “past or enter” logic in motion primitives; apply to all MLP scroll reveals.

1. **`MLP.reveal`**: Before hiding, if trigger already meets start, set visible immediately. Otherwise hide + ScrollTrigger with `onEnter` / `onEnterBack`, and register a rescue callback.
2. **`MLP.whenInView`**: Same already-past check for Class / Career / Journey / Trust custom timelines.
3. **`MLP.rescuePastReveals`**: After `initPage` (double rAF) and on `load` / `pageshow` (+50ms), force-show any armed reveal whose trigger is already past start but never received `onEnter` (covers scroll-restoration race).
4. **Reduced motion**: unchanged — no hide.

## Out of scope
- Phase 12 Learning
- Redesigning sections
- Changing animation aesthetics beyond making past content visible

## Done when
Refresh while scrolled to Alumni/Career/Class: current + all **upper** sections are visible; scroll up/down works without needing a top hard-refresh.
