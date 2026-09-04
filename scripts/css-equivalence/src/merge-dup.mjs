// merge-dup.mjs — merge rules with byte-identical declarations (same media,
// same important flags) into one rule at the FIRST member's position.
//
// Cascade equivalence argument: each selector part keeps its own pseudo
// context and element set; only the shared source position changes. A merge
// is therefore provably safe IFF no rule between the member positions
// competes (same specificity, same property) for a merged element — which is
// exactly what the gate verifies. If the gate FAILs, revert and exclude the
// offending group.
//
// Usage: node src/merge-dup.mjs [minGroupSize=2] [maxGroups=N]
//   (maxGroups: process only the N largest groups — for staged merges)

import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import postcss from 'postcss';

const here = path.dirname(fileURLToPath(import.meta.url));
const repoRoot = path.resolve(here, '..', '..', '..');
const file = path.join(repoRoot, 'public/assets/css/pages/mba-masters-landing.css');

const minSize = Number(process.argv[2] ?? 2);
const maxGroups = Number(process.argv[3] ?? 0);
const excludes = (process.argv[4] ?? '').split('|').filter(Boolean);
const placement = process.argv[5] ?? 'first'; // 'first' | 'last' | 'any'

const css = fs.readFileSync(file, 'utf8');
const root = postcss.parse(css, { from: file });

const mediaOf = (node) => {
  for (let p = node.parent; p; p = p.parent)
    if (p.type === 'atrule' && p.name === 'media') return p.params.replace(/\s+/g, ' ').trim();
  return '';
};

// group key: media + normalized decls (important flag included)
const groups = new Map();
root.walkRules((rule) => {
  const decls = rule.nodes.filter((n) => n.type === 'decl');
  if (!decls.length) return;
  const key =
    mediaOf(rule) +
    '||' +
    decls.map((d) => `${d.prop.replace(/\s+/g, '')}:${d.value.replace(/\s+/g, ' ')}${d.important ? '!important' : ''}`).join(';');
  if (!groups.has(key)) groups.set(key, []);
  groups.get(key).push(rule);
});

// ---- static competitor check (conservative) ------------------------------
// A merge moving member Mi from position pos(Mi) to pos(M1) is unsafe iff
// some rule R between pos(M1) and pos(Mi) sets a property of the group and
// could match the same element as Mi. "Could match" is approximated by
// sharing a class/id token (BEM: same block/component ⇒ possible overlap).
const allRules = [];
root.walkRules((rule) => {
  allRules.push({ rule, media: mediaOf(rule), pos: rule.source?.start?.line ?? 0 });
});
allRules.sort((a, b) => a.pos - b.pos);

const SHORTHANDS = {
  transition: ['transition-property', 'transition-duration', 'transition-timing-function', 'transition-delay'],
  margin: ['margin-top', 'margin-right', 'margin-bottom', 'margin-left'],
  padding: ['padding-top', 'padding-right', 'padding-bottom', 'padding-left'],
  border: ['border-top', 'border-right', 'border-bottom', 'border-left', 'border-top-color', 'border-right-color', 'border-bottom-color', 'border-left-color'],
  'border-top': ['border-top-width', 'border-top-style', 'border-top-color'],
  'border-bottom': ['border-bottom-width', 'border-bottom-style', 'border-bottom-color'],
  'border-left': ['border-left-width', 'border-left-style', 'border-left-color'],
  'border-right': ['border-right-width', 'border-right-style', 'border-right-color'],
  font: ['font-family', 'font-size', 'font-weight', 'font-style', 'line-height'],
  background: ['background-color', 'background-image', 'background-position', 'background-size', 'background-repeat'],
  grid: ['grid-template-columns', 'grid-template-rows', 'grid-auto-flow', 'grid-gap', 'grid-column-gap', 'grid-row-gap', 'justify-items', 'justify-content', 'align-items', 'align-content', 'align-self', 'justify-self', 'place-items', 'place-content', 'place-self'],
  inset: ['top', 'right', 'bottom', 'left'],
  outline: ['outline-color', 'outline-style', 'outline-width'],
  gap: ['column-gap', 'row-gap'],
};
function propsOf(decl) {
  if (SHORTHANDS[decl.prop]) return [...new Set([decl.prop, ...SHORTHANDS[decl.prop]])];
  return [decl.prop];
}
// ---- DOM-based overlap test (accurate for the fixture DOM) ---------------
// Two selector parts "could match the same element" iff some fixture element
// matches both (interactive pseudos stripped — :hover/:focus can become
// active on any element matching the structural part).
const { JSDOM } = await import('jsdom');
const fixtureDir = path.join(here, '..', 'fixture');
const doms = ['s1', 's4'].map((st) =>
  new JSDOM(fs.readFileSync(path.join(fixtureDir, `mbm-${st}.html`), 'utf8')).window.document
);
const stripInteractive = (sel) =>
  sel.replace(
    /:(hover|focus-visible|focus-within|focus|active|target|visited)(?![\w-])/g,
    ''
  );
const qcache = new Map();
function matchesInDoms(sel) {
  let set = qcache.get(sel);
  if (!set) {
    set = new Set();
    for (const doc of doms) {
      try {
        for (const el of doc.querySelectorAll(sel)) set.add(el);
      } catch {
        return null; // unsupported selector → fall back to "assume overlap"
      }
    }
    qcache.set(sel, set);
  }
  return set;
}
function partsCanMatch(partA, partB) {
  const a = stripInteractive(partA), b = stripInteractive(partB);
  const sa = matchesInDoms(a), sb = matchesInDoms(b);
  if (sa === null || sb === null) return true; // unknown → conservative
  if (sa.size > sb.size) return [...sb].some((el) => sa.has(el));
  return [...sa].some((el) => sb.has(el));
}
function competitorBetween(groupSet, propSet, fromPos, toPos, memberSel) {
  const memberParts = memberSel.split(',').map((s) => s.trim()).filter(Boolean);
  for (const R of allRules) {
    if (R.pos <= fromPos || R.pos >= toPos) continue;
    if (groupSet.has(R.rule)) continue; // removed by this same merge
    let sets = false;
    for (const d of R.rule.nodes) {
      if (d.type !== 'decl') continue;
      if (propsOf(d).some((p) => propSet.has(p))) { sets = true; break; }
    }
    if (!sets) continue;
    const rParts = R.rule.selector.split(',').map((s) => s.trim()).filter(Boolean);
    for (const rp of rParts)
      for (const mp of memberParts)
        if (partsCanMatch(rp, mp)) return R;
  }
  return null;
}

let groupCount = 0;
let mergedRules = 0;
const applied = [];
const skipped = [];
for (const [key, rules] of groups) {
  if (rules.length < minSize) continue;
  if (excludes.some((x) => key.includes(x))) continue;
  if (maxGroups && groupCount >= maxGroups) break;
  const propSet = new Set(
    rules[0].nodes.filter((d) => d.type === 'decl').flatMap((d) => propsOf(d))
  );
  // each member must move to rules[0]'s position without a competitor in between
  const groupSet = new Set(rules);
  // Per-member subset merge: anchor at a, merge every member i whose window
  // (pos_a, pos_i) has no EXTERNAL competitor (remaining same-group members
  // are harmless — identical declarations).
  const clearAt = (a) => {
    const aPos = rules[a].source.start.line;
    const clear = new Set([a]);
    for (let i = 0; i < rules.length; i++) {
      if (i === a) continue;
      const mPos = rules[i].source.start.line;
      const [lo, hi] = aPos < mPos ? [aPos, mPos] : [mPos, aPos];
      if (!competitorBetween(groupSet, propSet, lo, hi, rules[i].selector)) clear.add(i);
    }
    return clear;
  };
  let best = null;
  const order =
    placement === 'first' ? [0] :
    placement === 'last' ? [rules.length - 1] :
    Array.from({ length: rules.length }, (_, a) => a);
  for (const a of order) {
    const clear = clearAt(a);
    if (clear.size >= 2 && (!best || clear.size > best.clear.size)) best = { a, clear };
    if (best && best.clear.size === rules.length) break;
  }
  if (!best || best.clear.size < 2) {
    skipped.push(`${key.split('||')[1].slice(0, 60)}  (no safe anchor)`);
    continue;
  }
  groupCount++;
  const anchorRule = rules[best.a];
  const parts = [];
  for (const i of [...best.clear].sort((x, y) => x - y))
    for (const part of rules[i].selector.split(',')) {
      const p = part.replace(/\s+/g, ' ').trim();
      if (p) parts.push(p);
    }
  anchorRule.selector = parts.join(',\n  ');
  for (const i of best.clear) if (rules[i] !== anchorRule) rules[i].remove();
  mergedRules += best.clear.size - 1;
  applied.push({ key, count: best.clear.size, first: parts[0], anchor: best.a });
}

fs.writeFileSync(file, root.toString() + (css.endsWith('\n') ? '' : '\n'));
console.log(`merged ${groupCount} groups, removed ${mergedRules} redundant rules`);
for (const a of applied) console.log(`  x${a.count}  ${a.key.split('||')[1].slice(0, 70)}`);
if (skipped.length) {
  console.log(`skipped ${skipped.length} groups (static competitor):`);
  for (const s of skipped) console.log(`  - ${s}`);
}
