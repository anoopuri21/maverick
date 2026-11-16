// Run with: node tests/js/mlp-hero-lcp.test.cjs
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');

const script = fs.readFileSync('public/assets/js/pages/mba-masters-landing.js', 'utf8');
const css = fs.readFileSync('public/assets/css/pages/mba-masters-landing.css', 'utf8');
const blade = fs.readFileSync('resources/views/pages/mba-masters-landing/hero.blade.php', 'utf8');

assert.match(blade, /<h1 class="prospectus-cover__title">/);
assert.match(css, /\[data-hero-statement\] > :not\(\.prospectus-cover__title\),[\s\S]*?opacity:\s*0;/);

function element(name, isTitle = false) {
  const classes = new Set(isTitle ? ['prospectus-cover__title'] : []);
  return {
    name,
    children: [],
    classList: { contains: value => classes.has(value), add: value => classes.add(value) },
    removeAttribute() {}
  };
}

function run({ reduced = false, gsapAvailable = true } = {}) {
  const headline = element('headline', true);
  const lines = [element('line1'), element('line2')];
  headline.children = lines;
  const eyebrow = element('eyebrow');
  const intro = element('intro');
  const statement = element('statement');
  statement.children = [eyebrow, headline, intro];
  const hero = element('hero');
  const nodes = {
    '[data-hero-image]': element('image'),
    '[data-hero-wash]': element('wash'),
    '[data-hero-registration]': element('registration'),
    '[data-hero-masthead]': element('masthead'),
    '[data-hero-statement]': statement,
    '[data-hero-enquiry]': element('enquiry'),
    '[data-hero-folio]': element('folio')
  };
  hero.querySelector = selector => nodes[selector] || null;
  hero.querySelectorAll = selector => selector === '[data-hero-title-line]' ? lines : [];
  const calls = [];
  const timeline = { to(target, vars) { calls.push({ target, vars }); return this; } };
  const gsap = { set(target, vars) { calls.push({ target, vars }); }, timeline() { return timeline; } };
  const document = {
    readyState: 'complete',
    querySelector: selector => selector === '[data-hero-assembly]' ? hero : null,
    addEventListener() {}
  };
  const window = {
    matchMedia: () => ({ matches: reduced }),
    addEventListener() {},
    ...(gsapAvailable ? { gsap } : {})
  };
  vm.runInNewContext(script, { window, document, setTimeout() {}, requestAnimationFrame() {} });
  return { calls, hero, headline, lines, eyebrow, intro };
}

const normal = run();
const targets = normal.calls.flatMap(call => Array.isArray(call.target) ? call.target : [call.target]);
assert(!targets.includes(normal.headline), 'GSAP must not hide or animate the LCP headline');
normal.lines.forEach(line => assert(!targets.includes(line), 'GSAP must not hide or animate LCP lines'));
assert(targets.includes(normal.eyebrow), 'non-LCP statement details retain their entrance');
assert(targets.includes(normal.intro), 'non-LCP introduction retains its entrance');
for (const options of [{ reduced: true }, { gsapAvailable: false }]) {
  const result = run(options);
  assert(result.hero.classList.contains('is-hero-assembled'), 'fallback must show final state');
  assert.equal(result.calls.length, 0);
}
console.log('MLP hero LCP visibility tests passed');
