// gate.mjs — build candidate winner tables and compare against the frozen
// baseline. This is the zero-visual-change gate.
//
// The global CSS (main, our-story, responsive) is fixed and never part of the
// diff; only the target files (the two landing CSS being merged/optimized)
// change. The gate always feeds: [main, our-story, responsive, ...targets].
//
// Usage:
//   node gate.mjs --capture <targetsCsv>          # build + freeze baseline
//   node gate.mjs <targetsCsv>                     # build candidate + compare
//
// Exit 0 = PASS (identical), 1 = FAIL (differences), 2 = error.

import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import postcss from 'postcss';
import { buildTables, writeTables, readTables } from './engine.mjs';

// keyframe bodies are not part of the winner tables (they only affect
// animated states), so the gate additionally hashes every @keyframes block
// in the target files and requires them to be identical to the baseline.
function keyframesSig(repoRoot, targets) {
  const sig = {};
  for (const t of targets) {
    const css = fs.readFileSync(path.join(repoRoot, 'public', t), 'utf8');
    const root = postcss.parse(css, { from: t });
    root.walkAtRules('keyframes', (at) => {
      sig[at.params] = (at.nodes ?? [])
        .map((n) => `${n.selector}{${(n.nodes ?? []).filter((d) => d.type === 'decl').map((d) => `${d.prop}:${d.value}`).join(';')}}`)
        .join('');
    });
  }
  return sig;
}

const here = path.dirname(fileURLToPath(import.meta.url));
const repoRoot = path.resolve(here, '..', '..', '..');
const harnessRoot = path.resolve(here, '..');
const baselineDir = path.join(harnessRoot, 'baseline');
const fixtureDir = path.join(harnessRoot, 'fixture');

const GLOBALS = [
  'assets/css/main.css',
  'assets/css/our-story.css',
  'assets/css/responsive.css',
];

const args = process.argv.slice(2);
const capture = args.includes('--capture');
const csv = args.find((a) => !a.startsWith('--'));
if (!csv) {
  console.error('usage: node gate.mjs [--capture] <target1.css,target2.css,...>');
  console.error('  (paths relative to repo public/, e.g. assets/css/pages/mba-masters-landing.css)');
  process.exit(2);
}
const targets = csv.split(',').map((s) => s.trim()).filter(Boolean);
const cssFiles = [...GLOBALS, ...targets];
for (const t of targets) {
  if (!fs.existsSync(path.join(repoRoot, 'public', t))) {
    console.error(`target not found: public/${t}`);
    process.exit(2);
  }
}

const STATES = ['s0', 's1', 's2', 's3', 's4'];
const outDir = path.join(harnessRoot, 'cand-tables');
if (fs.existsSync(outDir)) fs.rmSync(outDir, { recursive: true, force: true });

let total = 0;
for (const st of STATES) {
  const fixture = path.join(fixtureDir, `mbm-${st}.html`);
  if (!fs.existsSync(fixture)) {
    console.error(`missing fixture ${fixture} — run: node src/build-fixture.js ${st} <css> fixture/mbm-${st}.html`);
    process.exit(2);
  }
  const html = fs.readFileSync(fixture, 'utf8');
  const { results, matchWarn, n } = buildTables({ repoRoot, cssFiles, fixtureHtml: html, label: st });
  writeTables(outDir, results);
  total += n;
  process.stderr.write(`built ${st}: n=${n} warnings=${matchWarn.length}\n`);
}

if (capture) {
  if (fs.existsSync(baselineDir)) fs.rmSync(baselineDir, { recursive: true, force: true });
  fs.cpSync(outDir, baselineDir, { recursive: true });
  const count = fs.readdirSync(baselineDir).length;
  fs.writeFileSync(path.join(baselineDir, 'meta.json'), JSON.stringify({ keyframes: keyframesSig(repoRoot, targets) }, null, 2));
  console.log(`baseline captured: ${count} tables + keyframe signatures → ${baselineDir}`);
  console.log('GATE: CAPTURED (run without --capture to compare against it)');
  process.exit(0);
}

// compare
const base = readTables(baselineDir);
if (Object.keys(base).length === 0) {
  console.error('no baseline found — run: node gate.mjs --capture <originalCss> first');
  process.exit(2);
}
const cand = readTables(outDir);

const baseNames = new Set(Object.keys(base));
const candNames = new Set(Object.keys(cand));
const missing = [...baseNames].filter((n) => !candNames.has(n));
if (missing.length) {
  console.error('candidate missing tables:', missing.slice(0, 8));
  process.exit(2);
}

let totalDiffs = 0;
const firstDiffs = [];
for (const name of [...baseNames].sort()) {
  const B = base[name];
  const C = cand[name];
  if (B.n !== C.n) { totalDiffs++; firstDiffs.push(`${name}: element count ${B.n} → ${C.n}`); continue; }
  for (let i = 0; i < B.elements.length; i++) {
    const [pb, Ab] = B.elements[i];
    const [pc, Ac] = C.elements[i];
    if (pb !== pc) { totalDiffs++; firstDiffs.push(`${name} elem${i} path changed`); continue; }
    for (const p of new Set([...Object.keys(Ab), ...Object.keys(Ac)])) {
      // custom properties are compared indirectly: any var() consumer that
      // lost its definition resolves differently and shows up on the
      // CONSUMING property (color/gap/…), which IS compared.
      if (p.startsWith('--')) continue;
      if (Ab[p] !== Ac[p]) {
        totalDiffs++;
        if (firstDiffs.length < 30) firstDiffs.push(`${name} [${i}] ${trunc(pb)}\n   ${p}: ${str(Ab[p])} → ${str(Ac[p])}`);
      }
    }
    for (const ctx of ['hover', 'focus', 'focus-visible', 'focus-within']) {
      const cb = B.ctx?.[ctx] ?? {};
      const cc = C.ctx?.[ctx] ?? {};
      for (const k of new Set([...Object.keys(cb), ...Object.keys(cc)])) {
        const eb = cb[k] ?? {}, ec = cc[k] ?? {};
          for (const p of new Set([...Object.keys(eb), ...Object.keys(ec)])) {
            if (p.startsWith('--')) continue; // see base-context note
            if (eb[p] !== ec[p]) {
              totalDiffs++;
              if (firstDiffs.length < 30) firstDiffs.push(`${name} [${k}] ${trunc(pc)}\n   ${ctx} ${p}: ${str(eb[p])} → ${str(ec[p])}`);
            }
          }
      }
    }
  }
}

// keyframe body check
let kfDiffs = 0;
const metaPath = path.join(baselineDir, 'meta.json');
if (fs.existsSync(metaPath)) {
  const baseKf = JSON.parse(fs.readFileSync(metaPath, 'utf8')).keyframes ?? {};
  const candKf = keyframesSig(repoRoot, targets);
  for (const [name, body] of Object.entries({ ...baseKf, ...candKf })) {
    if (baseKf[name] !== candKf[name]) kfDiffs++;
  }
}

console.log('──────────────────────────────────────────────');
if (totalDiffs === 0 && kfDiffs === 0) {
  console.log('GATE: PASS — zero cascade differences across all states/viewports/motions/contexts');
  process.exit(0);
} else {
  console.log(`GATE: FAIL — ${totalDiffs} cascade differences, ${kfDiffs} keyframe changes`);
  for (const d of firstDiffs) console.log('  ' + d);
  process.exit(1);
}

function trunc(s) { return s.length > 64 ? '…' + s.slice(-64) : s; }
function str(v) { if (v === undefined) return '∅'; const s = String(v); return s.length > 60 ? s.slice(0, 60) + '…' : s; }
