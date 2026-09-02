/**
 * build-fixture.js — renders the MBM landing page fixture HTML for a given DOM state.
 *
 * Usage: node src/build-fixture.js <state: s0|s1|s2> <cssFilesCsv> <out.html>
 *
 * states (mirror the production states the CSS must handle):
 *   s0 — no JS:  <html> without .js; hero not assembled; no JS-toggled classes
 *   s1 — JS final: <html class="js">; hero .is-hero-assembled; blueprint .is-inview;
 *                  accred slider .is-landing-slider (added on JS init)
 *   s2 — JS pre-assembly: <html class="js">; hero NOT assembled (hero-assembly baseline rules active)
 *   s3 — JS + one prose block offscreen: s1 DOM + .is-offscreen on first .mlp-prose
 *   s4 — JS + form result state: s1 DOM + success/error banners (session('success'),
 *        session('error'), validation errors) injected before each form
 *
 * cssFilesCsv — comma-separated CSS files (repo-relative, e.g.
 *   "assets/css/main.css,assets/css/responsive.css,assets/css/pages/mba-masters-landing.css,assets/css/pages/mba-masters-polish.css")
 */

import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import { tokenizeBlade, compileTokens, makeEvaluator } from './blade-lite.js';
import { data } from './data.js';
import { providers } from './providers.js';

const here = path.dirname(fileURLToPath(import.meta.url));
const repoRoot = path.resolve(here, '..', '..', '..');
const viewsRoot = path.join(repoRoot, 'resources', 'views');
const fixtureDir = path.join(repoRoot, 'scripts', 'css-equivalence', 'fixture');

const SECTIONS = [
  'pages/mba-masters-landing/hero',
  'pages/mba-masters-landing/trust',
  'pages/mba-masters-landing/overview',
  'pages/mba-masters-landing/why',
  'pages/mba-masters-landing/mba',
  'pages/mba-masters-landing/masters',
  'pages/mba-masters-landing/class-2025',
  'sections/accreditations',
  'pages/mba-masters-landing/class-snapshot',
  'pages/mba-masters-landing/fees',
  'pages/mba-masters-landing/career',
  'pages/mba-masters-landing/alumni',
  'pages/mba-masters-landing/learning',
  'pages/mba-masters-landing/partners',
  'pages/mba-masters-landing/video-proof',
  'pages/mba-masters-landing/testimonials',
  'pages/mba-masters-landing/faq',
  'pages/mba-masters-landing/final',
];

function compileSection(relPath, ctx) {
  const file = path.join(viewsRoot, `${relPath}.blade.php`);
  const src = fs.readFileSync(file, 'utf8');
  const tokens = tokenizeBlade(src);
  ctx.current = path.basename(relPath);
  return compileTokens(tokens, ctx);
}

export function buildFixture(state, cssFiles, outFile) {
  const log = [];
  const ev = makeEvaluator({ scope: [] });
  const ctx = {
    ev,
    providers,
    log,
    data,
    viewsRoot,
    readFile: (f) => fs.readFileSync(f, 'utf8'),
    current: null,
  };

  // root scope (what the controller passes)
  ctx.ev.pushLayer({
    site: data.site,
    hero: data.hero,
    trust: data.trust,
    overview: data.overview,
    why: data.why,
    mba: data.mba,
    masters: data.masters,
    class: data.class,
    fees: data.fees,
    career: data.career,
    alumni: data.alumni,
    learning: data.learning,
    partners: data.partners,
    testimonials: data.testimonials,
    faq: data.faq,
    final: data.final,
    errors: { any: () => false, all: () => [] },
  });
  const wa = String(data.site.whatsapp_number ?? '').replace(/\D+/g, '');

  let body = '';
  for (const rel of SECTIONS) {
    body += `\n<!-- ===== ${rel} ===== -->\n`;
    body += compileSection(rel, ctx);
  }

  // --- state-dependent DOM tweaks (JS-toggled classes + session states) ---
  const jsState = state === 's1' || state === 's2' || state === 's3' || state === 's4';
  if (jsState) {
    // accred slider JS adds is-landing-slider on init
    body = body.replace(
      'class="accred-slider-track"',
      'class="accred-slider-track is-landing-slider"'
    );
  }
  if (state === 's1' || state === 's3' || state === 's4') {
    // hero assembled (post-GSAP)
    body = body.replace(
      'class="mlp-hero prospectus-cover"',
      'class="mlp-hero prospectus-cover is-hero-assembled"'
    );
    // blueprint observer fired
    body = body.replace(
      'class="blueprint-overview__system" data-overview-blueprint',
      'class="blueprint-overview__system is-inview" data-overview-blueprint'
    );
  }
  if (state === 's3') {
    // one prose block offscreen (scroll animation paused)
    body = body.replace('class="mlp-prose mlp-why__text"', 'class="mlp-prose is-offscreen mlp-why__text"');
  }
  if (state === 's4') {
    // form result state: session('success') / session('error') / validation errors
    const banners =
      '<p class="mlp-form__success" role="status">Your enquiry was sent — an advisor will reply within one working day.</p>\n' +
      '<p class="mlp-form__errors" role="alert">Something went wrong, please try again.</p>\n' +
      '<ul class="mlp-form__errors" role="alert"><li>Please provide a valid email address.</li></ul>\n';
    // insert after the COMPLETE opening tag (the tag keeps its id/action attrs)
    body = body.replace(/<form class="mlp-form__fields"[^>]*>/g, (m) => banners + m);
  }

  const htmlClass = state === 's0' ? '' : ' class="js"';
  const cssLinks = cssFiles
    .map((c) => `  <link rel="stylesheet" href="../../../public/${c}">`)
    .join('\n');

  const html = `<!doctype html>
<html${htmlClass}>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>MBM fixture ${state}</title>
${cssLinks}
</head>
<body>
<main>
<div class="mlp-page mlp-page--polished" id="mlpPage">
${body}
</div>
<div class="mlp-sticky" id="mlpSticky" aria-label="Quick actions">
  <a class="mlp-sticky__btn mlp-sticky__btn--wa" href="https://wa.me/${wa}" target="_blank" rel="noopener" aria-label="WhatsApp admissions">WhatsApp</a>
  <a class="mlp-sticky__btn mlp-sticky__btn--apply" href="#mlp-enquire">Apply Now</a>
</div>
</main>
</body>
</html>
`;

  fs.mkdirSync(path.dirname(outFile), { recursive: true });
  fs.writeFileSync(outFile, html);
  return { outFile, log };
}

// CLI
const [, , state, cssCsv, outArg] = process.argv;
if (state && cssCsv) {
  const out = path.resolve(outArg ?? path.join(fixtureDir, `mbm-${state}.html`));
  const { log } = buildFixture(state, cssCsv.split(',').map((s) => s.trim()).filter(Boolean), out);
  if (log.length) {
    console.warn('fixture notes:');
    for (const l of new Set(log)) console.warn('  -', l);
  }
  console.log(`fixture written: ${out}`);
} else {
  console.error('usage: node build-fixture.js <s0|s1|s2> <css1,css2,...> [out.html]');
  process.exit(2);
}
