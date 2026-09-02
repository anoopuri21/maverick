// trim-is-args.mjs — remove non-matching arguments from :is()/:where() lists.
//
// Safety:
//   - an argument that matches no fixture element selects nothing, so the
//     rule's element set is unchanged;
//   - :where() args always carry zero specificity → removal never changes
//     the rule's effective specificity;
//   - :is() args: the function's specificity is the MAX of its args (spec),
//     so an arg may be removed only if the max over the remaining args is
//     unchanged.
//   - a function left with zero args matches nothing → its part dies; a rule
//     left with no parts dies.
//
// The gate (src/gate.mjs) must PASS afterwards.
// Usage: node src/trim-is-args.mjs

import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import postcss from 'postcss';
import { JSDOM } from 'jsdom';
import { specificity } from './specificity.js';

const here = path.dirname(fileURLToPath(import.meta.url));
const repoRoot = path.resolve(here, '..', '..', '..');
const file = path.join(repoRoot, 'public/assets/css/pages/mba-masters-landing.css');
const fixtureDir = path.join(here, '..', 'fixture');

const docs = ['s1', 's4'].map((st) =>
  new JSDOM(fs.readFileSync(path.join(fixtureDir, `mbm-${st}.html`), 'utf8')).window.document
);
// longest alternatives first (focus-visible before focus), bounded so
// :focus-visible is not eaten as :focus + "-visible". Only a literal ':'
// precedes these pseudo-class names, so no leading guard is needed
// (pseudo-element names like ::before are not in the alternation).
const qcache = new Map();
function stripInteractive(sel) {
  return sel.replace(
    /:(hover|focus-visible|focus-within|focus|active|target|visited)(?![\w-])/g,
    ''
  );
}
function matchesAny(sel) {
  let hit = qcache.get(sel);
  if (hit === undefined) {
    hit = false;
    for (const d of docs) {
      try {
        if (d.querySelectorAll(stripInteractive(sel)).length) { hit = true; break; }
      } catch {
        hit = 'ERR';
        break;
      }
    }
    qcache.set(sel, hit);
  }
  return hit;
}

// find top-level :is(...)/:where(...) occurrences in a selector part
function findFuncs(sel) {
  const out = [];
  const re = /:(is|where)\(/g;
  let m;
  while ((m = re.exec(sel))) {
    let depth = 0, end = -1;
    for (let i = m.index + m[0].length - 1; i < sel.length; i++) {
      if (sel[i] === '(') depth++;
      else if (sel[i] === ')') { depth--; if (depth === 0) { end = i; break; } }
    }
    if (end === -1) continue;
    out.push({ name: m[1], start: m.index, open: m.index + m[0].length - 1, end, inner: sel.slice(m.index + m[0].length, end) });
    re.lastIndex = end + 1;
  }
  return out;
}
const splitTop = (s) => {
  const out = [];
  let depth = 0, cur = '';
  for (const ch of s) {
    if (ch === '(') depth++;
    else if (ch === ')') depth--;
    if (ch === ',' && depth === 0) { out.push(cur); cur = ''; } else cur += ch;
  }
  out.push(cur);
  return out;
};

const css = fs.readFileSync(file, 'utf8');
const root = postcss.parse(css, { from: file });

let argsRemoved = 0, partsRemoved = 0, rulesRemoved = 0;
const log = [];

root.walkRules((rule) => {
  if (rule.parent?.type === 'atrule' && rule.parent.name === 'keyframes') return;
  const parts = splitTop(rule.selector);
  const newParts = [];
  for (const part of parts) {
    let sel = part;
    // iterate to fixpoint (nested :is inside :is args — rare but possible)
    for (let iter = 0; iter < 5; iter++) {
      const funcs = findFuncs(sel);
      let changed = false;
      for (const f of funcs) {
        const args = splitTop(f.inner).map((a) => a.trim()).filter(Boolean);
        if (!args.length) continue;
        const liveArgs = args.filter((a) => matchesAny(a) === true);
        const deadArgs = args.filter((a) => matchesAny(a) === false);
        const unknown = args.filter((a) => matchesAny(a) === 'ERR');
        // 'ERR' args (unsupported in jsdom) are always kept — conservative
        if (!deadArgs.length) continue;
        const survivors = [];
        if (liveArgs.length === 0) {
          if (deadArgs.length === args.length) {
            // nothing matches (and nothing unknown) → part selects nothing
            sel = null;
            break;
          }
          continue; // unknown matchers present → keep the part
        }
        if (f.name === 'is' && unknown.length) continue; // max may depend on unknown args
        if (f.name === 'where') {
          survivors.push(...liveArgs); // :where is always 0-specificity → drop all dead args
        } else {
          // :is() specificity = max of args (spec). Keep enough args so the
          // max is unchanged.
          const cmp = (a, b) => (a[0] - b[0]) || (a[1] - b[1]) || (a[2] - b[2]);
          const liveMax = liveArgs.reduce((m, a) => (cmp(specificity(a), m) > 0 ? specificity(a) : m), [0, 0, 0]);
          const deadMax = deadArgs.reduce((m, a) => (cmp(specificity(a), m) > 0 ? specificity(a) : m), [0, 0, 0]);
          survivors.push(...liveArgs);
          for (const a of deadArgs) {
            if (cmp(specificity(a), liveMax) <= 0) continue; // live args already cover the max
            if (cmp(specificity(a), deadMax) < 0) continue;  // below the dead max
            if (!survivors.some((s) => JSON.stringify(specificity(s)) === JSON.stringify(deadMax))) {
              survivors.push(a); // keep ONE arg carrying the dead max
            }
          }
        }
        if (survivors.length + unknown.length === args.length) continue; // nothing removed
        sel = sel.slice(0, f.start) + `:${f.name}(${[...survivors, ...unknown].join(', ')})` + sel.slice(f.end + 1);
        changed = true;
      }
      if (!changed) break;
    }
    if (sel === null) { partsRemoved++; log.push(`part removed: ${part.replace(/\s+/g, ' ').trim().slice(0, 90)}`); continue; }
    if (sel !== part) log.push(`trimmed: ${part.replace(/\s+/g, ' ').trim().slice(0, 70)} → ${sel.replace(/\s+/g, ' ').trim().slice(0, 70)}`);
    newParts.push(sel);
  }
  if (newParts.length === 0) {
    rulesRemoved++;
    rule.remove();
  } else if (newParts.length !== parts.length) {
    rule.selector = newParts.join(', ');
  }
});

fs.writeFileSync(file, root.toString() + (css.endsWith('\n') ? '' : '\n'));
console.log(`parts removed: ${partsRemoved}, rules removed: ${rulesRemoved}`);
for (const l of log.slice(0, 60)) console.log('  ' + l);
