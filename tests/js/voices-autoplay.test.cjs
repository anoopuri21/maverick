// Run with: node tests/js/voices-autoplay.test.cjs
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const source = fs.readFileSync('public/assets/js/voices-testimonials.js', 'utf8');

function element() {
  const events = {};
  return {
    dataset: {}, hidden: false, events,
    classList: { toggle() {}, remove() {}, add() {} },
    setAttribute(name, value) { this[name] = value; },
    addEventListener(name, fn) { events[name] = fn; },
    removeEventListener(name) { delete events[name]; },
    querySelector() { return null; }
  };
}

function setup({ landing = true, io = true, reduced = false } = {}) {
  const slides = [element(), element(), element()];
  const toggle = element();
  const carousel = element();
  const section = element();
  const stage = { querySelectorAll: () => [] };
  carousel.querySelectorAll = selector => selector === '[data-testimonial-slide]' ? slides : [];
  carousel.querySelector = selector => selector === '[data-testimonial-toggle]' ? toggle : null;
  carousel.closest = selector => selector === '[data-closing-voices]' ? stage : landing ? section : null;
  const observers = [], timers = new Map();
  let nextTimer = 0;
  function IntersectionObserver(callback) {
    this.callback = callback;
    this.observe = target => { this.target = target; };
    this.disconnect = () => { this.disconnected = true; };
    observers.push(this);
  }
  const document = {
    hidden: false, readyState: 'complete',
    events: {},
    querySelectorAll: selector => selector === '[data-luxury-testimonials]' ? [carousel] : [],
    addEventListener(name, fn) { this.events[name] = fn; },
    removeEventListener(name) { delete this.events[name]; }
  };
  const window = {
    matchMedia: () => ({ matches: reduced }),
    requestAnimationFrame: fn => fn(),
    setInterval(fn) { const id = ++nextTimer; timers.set(id, fn); return id; },
    clearInterval(id) { timers.delete(id); },
    setTimeout() { return 1; }, clearTimeout() {}, addEventListener() {}, removeEventListener() {}
  };
  if (io) window.IntersectionObserver = IntersectionObserver;
  vm.runInNewContext(source, { window, document, IntersectionObserver, console });
  return { window, document, observers, timers, slides, toggle };
}

const landing = setup();
assert.equal(landing.timers.size, 0, 'landing carousel must not autoplay before intersection');
assert.equal(landing.observers.length, 1);
landing.observers[0].callback([{ isIntersecting: true }]);
assert.equal(landing.timers.size, 1, 'visible carousel starts timer');
landing.timers.values().next().value();
assert.equal(landing.slides[1].hidden, false, 'timer advances visible carousel');
landing.toggle.events.click();
landing.timers.values().next().value();
assert.equal(landing.slides[1].hidden, false, 'user pause remains effective');
landing.observers[0].callback([{ isIntersecting: false }]);
assert.equal(landing.timers.size, 0, 'offscreen carousel stops timer');
landing.observers[0].callback([{ isIntersecting: true }]);
assert.equal(landing.timers.size, 1);
landing.timers.values().next().value();
assert.equal(landing.slides[1].hidden, false, 'user pause persists after re-entry');
landing.document.hidden = true;
landing.document.events.visibilitychange();
assert.equal(landing.timers.size, 0, 'hidden page stops timer');
landing.document.hidden = false;
landing.document.events.visibilitychange();
assert.equal(landing.timers.size, 1, 'visible page resumes timer');
landing.window.MaverickVoicesTestimonials.destroy();
assert.equal(landing.timers.size, 0);
assert.equal(landing.observers[0].disconnected, true);
landing.observers[0].callback([{ isIntersecting: true }]);
assert.equal(landing.timers.size, 0, 'queued observer callbacks cannot restart a destroyed carousel');

const reduced = setup({ reduced: true });
reduced.observers[0].callback([{ isIntersecting: true }]);
assert.equal(reduced.timers.size, 0, 'reduced motion never starts autoplay');
const other = setup({ landing: false });
assert.equal(other.timers.size, 1, 'other pages retain their previous behavior');
const fallback = setup({ io: false });
assert.equal(fallback.timers.size, 1, 'no IntersectionObserver falls back to existing behavior');
console.log('Voices autoplay visibility tests passed');
