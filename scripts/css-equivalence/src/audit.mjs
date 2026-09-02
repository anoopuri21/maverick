// audit.mjs — selector coverage audit for the target CSS files.
//
// Reports, per selector:
//   - match counts in each fixture state (s0 no-JS / s1 JS-final / s2 hero-pre-assembly)
//   - wins in a representative context (base @1440 no-preference) and interactive contexts
// Flags:
//   DEAD      — matches 0 elements in all states (cannot affect the live page)
//   SHADOWED  — matches elements but never wins any property in the representative context
//               (may still win in other viewports/contexts — treat as "verify" not "delete")
//
// Usage: node audit.mjs <targetsCsv> [--only-targets]
// By default all 5 cascade files are passed to the engine (globals affect nothing
// here except the DOM), but only the target files' rules are reported.

import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import { ruleCoverage } from './engine.mjs';

const here = path.dirname(fileURLToPath(import.meta.url));
const repoRoot = path.resolve(here, '..', '..', '..');
const fixtureDir = path.join(here, '..', 'fixture');

const args = process.argv.slice(2).filter((a) => !a.startsWith('--'));
const csv = args[0];
if (!csv) {
  console.error('usage: node audit.mjs <target1.css,target2.css,...>');
  process.exit(2);
}
const targets = csv.split(',').map((s) => s.trim()).filter(Boolean);
const cssFiles = [
  'assets/css/main.css',
  'assets/css/our-story.css',
  'assets/css/responsive.css',
  ...targets,
];

const fixtures = ['s0', 's1', 's2', 's3', 's4'].map((st) => ({
  label: st,
  html: fs.readFileSync(path.join(fixtureDir, `mbm-${st}.html`), 'utf8'),
}));

const { entries, n } = ruleCoverage({ repoRoot, cssFiles, fixtures });

let dead = 0, shadowed = 0, live = 0;
const deadRows = [], shadowedRows = [];
for (const e of entries) {
  if (!targets.includes(e.file)) continue;
  const m = Object.values(e.matches);
  const matchesAllZero = m.every((x) => x === 0 || x === 'ERR');
  const wins = e.baseWins + e.ctxWins;
  if (matchesAllZero) {
    dead++;
    deadRows.push(e);
  } else if (wins === 0) {
    shadowed++;
    shadowedRows.push(e);
  } else {
    live++;
  }
}

console.log(`elements per state: ${n}`);
console.log(`target rules: ${entries.filter((e) => targets.includes(e.file)).length}`);
console.log(`  LIVE (matches + wins):     ${live}`);
console.log(`  SHADOWED (matches, 0 wins): ${shadowed}`);
console.log(`  DEAD (0 matches):          ${dead}`);

console.log(`\n=== DEAD (match nothing in any state) — ${deadRows.length} ===`);
for (const e of deadRows) {
  console.log(`  [${e.media ?? 'always'}] ${e.context}${e.pseudo ? '::' + e.pseudo : ''}  ${e.selector}   (${e.file})`);
}

console.log(`\n=== SHADOWED (match, never win in representative context) — ${shadowedRows.length} ===`);
for (const e of shadowedRows) {
  const mm = Object.entries(e.matches).map(([k, v]) => `${k}=${v}`).join(' ');
  console.log(`  [${e.media ?? 'always'}] ${e.context}  ${e.selector}   (${mm}) (${e.file})`);
}
