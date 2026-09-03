// selector-probe.mjs — extract every selector from the CSS files and check
// that jsdom (nwsapi) can match them. Reports any selector that throws or
// needs special handling (interactive pseudo-classes are listed, not errors).
import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import { JSDOM } from 'jsdom';
import postcss from 'postcss';

const here = path.dirname(fileURLToPath(import.meta.url));
const repoRoot = path.resolve(here, '..', '..', '..');
const cssFiles = [
  'public/assets/css/pages/mba-masters-landing.css',
  'public/assets/css/pages/mba-masters-polish.css',
];
const html = fs.readFileSync(path.join(here, '..', 'fixture', 'mbm-s1.html'), 'utf8');
const dom = new JSDOM(html, { url: 'https://mbm.test/' });
const { document } = dom.window;

const INTERACTIVE = new Set(['hover', 'focus', 'focus-visible', 'focus-within', 'active', 'visited']);

const allSelectors = new Set();
for (const f of cssFiles) {
  const css = fs.readFileSync(`${repoRoot}/${f}`, 'utf8');
  const rootCss = postcss.parse(css, { from: f });
  rootCss.walkRules((rule) => {
    // split the selector list, keeping :not(:hover) groups intact
    for (const sel of splitSelectors(rule.selector)) allSelectors.add(sel.trim());
  });
}

function splitSelectors(list) {
  const out = [];
  let depth = 0;
  let cur = '';
  for (const ch of list) {
    if (ch === '(') depth++;
    if (ch === ')') depth--;
    if (ch === ',' && depth === 0) { out.push(cur); cur = ''; } else cur += ch;
  }
  if (cur.trim()) out.push(cur);
  return out;
}

function interactivePseudos(sel) {
  // find :pseudo occurrences not inside parentheses belonging to :not/:is/:where args? — we WANT args too
  const found = new Set();
  for (const m of sel.matchAll(/:([a-z-]+)/g)) {
    if (INTERACTIVE.has(m[1])) found.add(m[1]);
  }
  return [...found];
}

let ok = 0, skipped = 0, failed = 0;
const failures = [];
for (const sel of [...allSelectors].sort()) {
  const inter = interactivePseudos(sel);
  if (inter.length) { skipped++; continue; }
  try {
    document.querySelectorAll(sel);
    ok++;
  } catch (e) {
    failed++;
    failures.push({ sel, err: e.message });
  }
}
console.log(`total=${allSelectors.size} matched-ok=${ok} interactive-skipped=${skipped} failed=${failed}`);
for (const f of failures.slice(0, 20)) console.log('FAIL:', f.sel, '->', f.err);
