// remove-dead.mjs — remove dead rule entries (audit DEAD list) from target files.
//
// A "dead triple" is (file, media, selectorPart): a comma part of a rule
// selector that matches 0 elements in every fixture state × viewport × motion.
// Removing it cannot change any computed style (it never matches).
//
// Safety invariants relied upon:
//   - match counts depend only on (media, selectorPart), not on rule position —
//     so every occurrence of a dead triple is dead, and a live selector can
//     never share a triple with a dead one.
//   - the audit engine only walks @media (these files contain no
//     @supports/@container/@layer), so every rule in the target files was
//     visible to the audit.
//
// Usage: node src/remove-dead.mjs <targetsCsv> [triplesJson]
//   triplesJson — [["file","media","context","pseudo","selector"], ...]
//   (default: /tmp/dead-triples.json from the current audit run)
//
// The gate (src/gate.mjs) MUST be run afterwards and PASS.

import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import postcss from 'postcss';

const here = path.dirname(fileURLToPath(import.meta.url));
const repoRoot = path.resolve(here, '..', '..', '..');

const args = process.argv.slice(2);
const csv = args[0];
if (!csv) {
  console.error('usage: node remove-dead.mjs <targetsCsv> [triplesJson]');
  process.exit(2);
}
const targets = csv.split(',').map((s) => s.trim()).filter(Boolean);
const triplesPath = args[1] ?? '/tmp/dead-triples.json';
const triples = JSON.parse(fs.readFileSync(triplesPath, 'utf8'));

const normMedia = (m) => (m === 'always' ? '' : m.replace(/\s+/g, ' ').trim());
const normSel = (s) => s.replace(/\s+/g, ' ').trim();

const deadByFile = new Map();
for (const [file, media, , , sel] of triples) {
  const key = `${normMedia(media)}||${normSel(sel)}`;
  if (!deadByFile.has(file)) deadByFile.set(file, new Set());
  deadByFile.get(file).add(key);
}

// split a selector list on TOP-LEVEL commas only (commas inside
// :not()/:is()/:where()/() stay with their part)
function splitTopCommas(sel) {
  const parts = [];
  let depth = 0;
  let cur = '';
  for (const ch of sel) {
    if (ch === '(') depth++;
    else if (ch === ')') depth--;
    if (ch === ',' && depth === 0) {
      parts.push(cur);
      cur = '';
    } else cur += ch;
  }
  parts.push(cur);
  return parts;
}

let totalFull = 0;
let totalParts = 0;
for (const t of targets) {
  const file = path.join(repoRoot, 'public', t);
  const css = fs.readFileSync(file, 'utf8');
  const deadSet = deadByFile.get(t) ?? new Set();
  const root = postcss.parse(css, { from: t });

  let fullRemoved = 0;
  let partRemoved = 0;
  const toRemove = [];
  root.walkRules((rule) => {
    let media = '';
    for (let p = rule.parent; p; p = p.parent) {
      if (p.type === 'atrule' && p.name === 'media') {
        media = normMedia(p.params);
        break;
      }
    }
    const parts = splitTopCommas(rule.selector);
    const kept = [];
    for (const part of parts) {
      const key = `${media}||${normSel(part)}`;
      if (deadSet.has(key)) partRemoved++;
      else kept.push(part);
    }
    if (kept.length === 0) {
      toRemove.push(rule);
    } else if (kept.length < parts.length) {
      rule.selector = kept.join(', ');
    }
  });
  for (const rule of toRemove) {
    fullRemoved++;
    rule.remove();
  }
  if (fullRemoved || partRemoved) {
    fs.writeFileSync(file, root.toString() + (css.endsWith('\n') ? '' : '\n'));
  }
  console.log(`${t}: rules fully removed=${fullRemoved}  dead parts removed=${partRemoved} (of ${deadSet.size} dead triples)`);
  totalFull += fullRemoved;
  totalParts += partRemoved;
}
console.log(`TOTAL: ${totalFull} rules, ${totalParts} selector parts removed`);
