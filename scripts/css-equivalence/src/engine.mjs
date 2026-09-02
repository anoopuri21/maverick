// engine.mjs — cascade-equivalence engine (see README for semantics).
//
// For a fixture HTML + ordered CSS list, builds per-element winner tables for
// every (viewport, motion) combination:
//   base context  : effective value per property (declared winner, var-resolved,
//                   inherited from nearest ancestor when no declaration)
//   ctx deltas    : for hover / focus / focus-visible / focus-within — only the
//                   properties whose winner in that context comes from an
//                   interactive rule, resolved in that context.
// Winner = max of (important desc, inline, specificity, source order).
// Shorthands expand to longhands; unresolved var() ⇒ __initial__.

import fs from 'node:fs';
import path from 'node:path';
import zlib from 'node:zlib';
import { JSDOM } from 'jsdom';
import postcss from 'postcss';
import { specificity } from './specificity.js';
import { expandDecl } from './expand.js';

export const VIEWPORTS = [1440, 950, 860, 768, 680, 630, 610, 580, 540, 470, 390];
export const MOTIONS = ['no-preference', 'reduce'];
export const INTERACTIVE_CONTEXTS = ['hover', 'focus', 'focus-visible', 'focus-within'];

const INHERITED = new Set([
  'color', 'cursor', 'direction', 'font-family', 'font-size', 'font-size-adjust',
  'font-stretch', 'font-style', 'font-variant', 'font-variant-alternates', 'font-weight',
  'letter-spacing', 'line-height', 'text-align', 'text-align-last', 'text-indent',
  'text-justify', 'text-transform', 'white-space', 'word-break', 'word-spacing',
  'text-overflow', 'overflow-wrap', 'hyphens', 'visibility', 'border-collapse',
  'border-spacing', 'caption-side', 'empty-cells', 'orphans', 'widows', 'table-layout',
  'text-decoration-line', 'text-decoration-style', 'text-decoration-color',
  'text-underline-position', 'text-underline-offset', 'text-emphasis-color',
  'text-emphasis-style', 'scroll-behavior', 'text-wrap',
]);

const INITIAL_FOR = {
  'border-collapse': 'separate', 'border-spacing': '0', 'caption-side': 'top',
  'empty-cells': 'show', 'orphans': '2', 'widows': '2', 'table-layout': 'auto',
  'text-decoration-line': 'none', 'text-decoration-style': 'solid',
  'text-decoration-color': 'currentcolor', 'text-underline-position': 'auto',
  'text-underline-offset': '0', 'text-emphasis-color': 'currentcolor',
  'text-emphasis-style': 'none', 'scroll-behavior': 'auto', 'text-wrap': 'wrap',
};

// ------------------------------------------------------------------ CSS parse
function parseCssFiles(repoRoot, cssFiles) {
  const rules = [];
  let order = 0;
  for (const rel of cssFiles) {
    const file = path.join(repoRoot, 'public', rel);
    const css = fs.readFileSync(file, 'utf8');
    const root = postcss.parse(css, { from: file });
    for (const at of root.nodes) {
      if (at.type === 'atrule' && at.name === 'media') {
        walk(at, mediaText(at));
      } else if (at.type === 'atrule' && (at.name === 'keyframes' || at.name === 'font-face')) {
        continue;
      } else if (at.type === 'atrule') {
        throw new Error(`[engine] unsupported at-rule @${at.name} in ${rel}`);
      } else if (at.type === 'rule') {
        pushRule(at, null);
      }
    }
    function pushRule(ruleNode, media) {
      const decls = [];
      for (const d of ruleNode.nodes) {
        if (d.type === 'decl') decls.push({ prop: d.prop, value: d.value, important: Boolean(d.important) });
      }
      if (decls.length) rules.push({ selector: ruleNode.selector, decls, media, order: order++, file: rel });
    }
    function walk(node, media) {
      for (const child of node.nodes ?? []) {
        if (child.type === 'atrule' && child.name === 'media') walk(child, mediaText(child));
        else if (child.type === 'atrule' && (child.name === 'keyframes' || child.name === 'font-face')) continue;
        else if (child.type === 'atrule') throw new Error(`[engine] unsupported at-rule @${child.name} in ${rel}`);
        else if (child.type === 'rule') pushRule(child, media);
      }
    }
  }
    function mediaText(at) {
      return at.params.replace(/\s+/g, ' ').trim();
    }
  return rules;
}

function evalMedia(text, vw, motion) {
  const ors = text.split(/\s+or\s+/);
  return ors.some((and) =>
    and.split(/\s+and\s+/).every((cond) => evalCond(cond.trim(), vw, motion))
  );
}

function evalCond(cond, vw, motion) {
  const m = cond.match(/^\(?([a-z-]+)\s*:\s*([^)]+)\)?$/);
  if (!m) throw new Error(`[engine] unsupported media condition: ${cond}`);
  const feature = m[1];
  const val = m[2].trim().replace(/px$/, '');
  switch (feature) {
    case 'max-width': return vw <= Number(val);
    case 'min-width': return vw >= Number(val);
    case 'prefers-reduced-motion':
      return val === 'reduce' ? motion === 'reduce' : motion === 'no-preference';
    default:
      throw new Error(`[engine] unsupported media feature: ${feature}`);
  }
}

// ---------------------------------------------------------- selector context
const INTERACTIVE = new Set(['hover', 'focus', 'focus-visible', 'focus-within']);

function splitTop(s, sep) {
  const out = [];
  let depth = 0, cur = '';
  for (const ch of s) {
    if (ch === '(') depth++;
    else if (ch === ')') depth--;
    if (ch === sep && depth === 0) { out.push(cur); cur = ''; } else cur += ch;
  }
  out.push(cur);
  return out;
}

function selectorInfo(selector) {
  let context = 'base';
  let s = selector.trim();
  let pseudo = null;
  const pe = s.match(/::(before|after|placeholder|marker|first-line|first-letter)\s*$/i);
  if (pe) {
    pseudo = pe[1].toLowerCase();
    s = s.slice(0, pe.index).trim();
  }
  const found = new Set();
  for (const m of s.matchAll(/:([a-z-]+)/g)) {
    if (INTERACTIVE.has(m[1])) found.add(m[1]);
  }
  if (found.size > 1) throw new Error(`[engine] selector mixes interactive contexts: ${selector}`);
  if (found.size === 1) {
    context = [...found][0];
    s = s.replace(/:(hover|focus-visible|focus-within|focus)\b/g, '');
    s = s.replace(/\s{2,}/g, ' ').replace(/\s+([,>~+])/g, '$1').trim();
  }
  return { context, matchSel: s, pseudo };
}

// --------------------------------------------------------------------- vars
function findMatchingParen(s, openIdx) {
  let depth = 0;
  for (let i = openIdx; i < s.length; i++) {
    if (s[i] === '(') depth++;
    else if (s[i] === ')') { depth--; if (depth === 0) return i; }
  }
  throw new Error('[engine] unbalanced var()');
}

// --------------------------------------------------------------------- core
function loadSetup({ repoRoot, cssFiles, fixtureHtml }) {
  const dom = new JSDOM(fixtureHtml, { url: 'https://mbm.test/', pretendToBeVisual: true });
  const { document } = dom.window;
  const elements = Array.from(document.querySelectorAll('*'));
  const n = elements.length;
  const idxOf = new WeakMap();
  elements.forEach((el, i) => idxOf.set(el, i));

  // paths + parent chain
  const paths = new Array(n);
  const parentIdx = new Array(n).fill(-1);
  (function walk(el, parentPath, parentI) {
    const kids = Array.from(el.children);
    for (const child of kids) {
      const i = idxOf.get(child);
      const id = child.id ? `#${child.id}` : '';
      let seg = child.localName.toLowerCase() + id;
      if (child.className && typeof child.className === 'string' && child.className.trim()) {
        seg += '.' + child.className.trim().split(/\s+/).slice(0, 2).join('.');
      }
      if (kids.filter((k) => k.localName === child.localName).length > 1) {
        const pos = kids.filter((k) => k.localName === child.localName).indexOf(child) + 1;
        seg += `:${pos}`;
      }
      paths[i] = parentPath ? `${parentPath}>${seg}` : seg;
      parentIdx[i] = parentI;
      walk(child, paths[i], i);
    }
  })(document.documentElement, '', idxOf.get(document.documentElement));
  parentIdx[0] = -1;
  paths[0] = 'html';

  // inline declarations
  const inlineDecls = new Array(n).fill(null);
  for (let i = 0; i < n; i++) {
    const st = elements[i].getAttribute('style');
    if (!st) continue;
    const decls = [];
    for (const part of st.split(';')) {
      const ci = part.indexOf(':');
      if (ci === -1) continue;
      const prop = part.slice(0, ci).trim();
      const rest = part.slice(ci + 1).trim();
      decls.push({ prop, value: rest.replace(/\s*!important\s*$/i, ''), important: /!important/i.test(rest) });
    }
    if (decls.length) inlineDecls[i] = decls;
  }

  // rules
  const rawRules = parseCssFiles(repoRoot, cssFiles);
  const rules = [];
  for (const r of rawRules) {
    for (const part of splitTop(r.selector, ',')) {
      if (!part.trim()) continue;
      const { context, matchSel, pseudo } = selectorInfo(part);
      const spec = specificity(part);
      rules.push({ matchSel, context, pseudo, spec, decls: r.decls, order: r.order, media: r.media, rawSel: part.trim(), file: r.file });
    }
  }

  // precompute matches (DOM is identical across viewports/motions)
  const matchLists = new Array(rules.length);
  const matchWarn = [];
  for (let ri = 0; ri < rules.length; ri++) {
    try {
      const set = new Set(Array.from(document.querySelectorAll(rules[ri].matchSel), (el) => idxOf.get(el)));
      matchLists[ri] = [...set];
    } catch (e) {
      matchWarn.push({ sel: rules[ri].rawSel, err: e.message });
      matchLists[ri] = [];
    }
  }

  // ::before/::after only exist if SOME matching rule sets `content`.
  // Track, per (element, pseudo), whether any matching rule declares content;
  // decls from content-less pseudo rules cannot render and are excluded.
  const GENERATED = ['before', 'after'];
  const pseudoGenerated = new Set();
  for (let ri = 0; ri < rules.length; ri++) {
    const r = rules[ri];
    if (!r.pseudo || !GENERATED.includes(r.pseudo)) continue;
    if (!r.decls.some((d) => d.prop.toLowerCase() === 'content')) continue;
    for (const ei of matchLists[ri]) pseudoGenerated.add(`${ei}:${r.pseudo}`);
  }

  function better(a, b) {
    if (a.important !== b.important) return a.important;
    if (a.inline !== b.inline) return a.inline;
    for (let k = 0; k < 3; k++) if (a.spec[k] !== b.spec[k]) return a.spec[k] > b.spec[k];
    return a.order >= b.order;
  }

  function addDecl(map, prop, value, important, inline, spec, order) {
    const cand = { value, important, inline, spec, order };
    const cur = map.get(prop);
    if (!cur || better(cand, cur)) map.set(prop, cand);
  }

  function collectContext(vw, motion, context) {
    const cands = new Array(n).fill(null);
    for (let ri = 0; ri < rules.length; ri++) {
      const r = rules[ri];
      if (r.context !== context || !mediaApplies(r.media, vw, motion)) continue;
      const prefix = r.pseudo ? `${r.pseudo}|` : '';
      const gen = r.pseudo && GENERATED.includes(r.pseudo) ? `${r.pseudo}` : null;
      for (const ei of matchLists[ri]) {
        if (gen && !pseudoGenerated.has(`${ei}:${gen}`)) continue; // pseudo not generated
        let map = cands[ei];
        if (!map) { map = new Map(); cands[ei] = map; }
        for (const d of r.decls) {
          const exp = expandDecl(d.prop, d.value);
          if (!exp) { addDecl(map, prefix + `${d.prop}\x00raw`, d.value, d.important, false, r.spec, r.order); continue; }
          for (const [p, v] of Object.entries(exp)) addDecl(map, prefix + p, v, d.important, false, r.spec, r.order);
        }
      }
    }
    // inline styles (context-independent)
    for (let ei = 0; ei < n; ei++) {
      if (!inlineDecls[ei]) continue;
      let map = cands[ei];
      if (!map) { map = new Map(); cands[ei] = map; }
      for (const d of inlineDecls[ei]) {
        const exp = expandDecl(d.prop, d.value);
        if (!exp) { addDecl(map, `${d.prop}\x00raw`, d.value, d.important, true, [1, 0, 0], 1 << 30); continue; }
        for (const [p, v] of Object.entries(exp)) addDecl(map, p, v, d.important, true, [1, 0, 0], 1 << 30);
      }
    }
    return cands;
  }

  function mediaApplies(media, vw, motion) {
    return media ? evalMedia(media, vw, motion) : true;
  }

  // -------- var resolution with ancestor custom-property lookup --------
  function makeResolver(baseCands, ctxCands) {
    const customMemo = new Array(n).fill(null);
    const ctxCustomMemo = new Map();

    function declared(ei, prop, ctxName) {
      if (ctxName !== 'base') {
        const cm = ctxCands.get(ctxName)?.[ei];
        const w = cm && cm.get(prop);
        if (w) return w.value;
      }
      const bm = baseCands[ei];
      const w = bm && bm.get(prop);
      return w ? w.value : undefined;
    }

    function customResolved(ei, name, ctxName, seen) {
      const memo = ctxName === 'base' ? customMemo : (ctxCustomMemo.get(ctxName) ?? (ctxCustomMemo.set(ctxName, new Array(n).fill(null)), ctxCustomMemo.get(ctxName)));
      if (memo[ei] && Object.prototype.hasOwnProperty.call(memo[ei], name)) {
        const v = memo[ei][name];
        return v === null ? undefined : v;
      }
      if (!memo[ei]) memo[ei] = {};
      const seenKey = `${ei}:${name}`;
      if (seen.has(seenKey)) { memo[ei][name] = null; return undefined; }
      seen.add(seenKey);
      const raw = declared(ei, name, ctxName);
      let v;
      if (raw !== undefined) {
        const rv = resolveVar(ei, raw, ctxName, seen);
        v = rv === '__initial__' ? null : rv;
      } else if (parentIdx[ei] !== -1) {
        v = customResolved(parentIdx[ei], name, ctxName, seen);
        v = v === undefined ? null : v;
      } else {
        v = null;
      }
      memo[ei][name] = v === undefined ? null : v;
      seen.delete(seenKey);
      return v === null ? undefined : v;
    }

    function resolveVar(ei, raw, ctxName, seen) {
      seen = seen || new Set();
      if (typeof raw !== 'string' || !raw.includes('var(')) return raw;
      let out = '';
      let i = 0;
      for (;;) {
        const idx = raw.indexOf('var(', i);
        if (idx === -1) { out += raw.slice(i); break; }
        out += raw.slice(i, idx);
        const end = findMatchingParen(raw, idx + 3);
        const inner = raw.slice(idx + 4, end);
        const parts = splitTop(inner, ',');
        const name = parts[0].trim();
        const fallback = parts.length > 1 ? parts.slice(1).join(',').trim() : null;
        const v = customResolved(ei, name, ctxName, seen);
        if (v === undefined) {
          if (fallback !== null) out += resolveVar(ei, fallback, ctxName, seen);
          else return '__initial__';
        } else {
          out += v;
        }
        i = end + 1;
      }
      return out;
    }
    return { resolveVar };
  }

  function tableFor(vw, motion) {
    const baseCands = collectContext(vw, motion, 'base');
    const ctxCands = new Map();
    for (const ctx of INTERACTIVE_CONTEXTS) {
      const c = collectContext(vw, motion, ctx);
      if (c.some(Boolean)) ctxCands.set(ctx, c);
    }
    const { resolveVar } = makeResolver(baseCands, ctxCands);

    // full property universe for this table
    const allProps = new Set(INHERITED);
    for (let ei = 0; ei < n; ei++) {
      if (!baseCands[ei]) continue;
      for (const p of baseCands[ei].keys()) allProps.add(p);
    }

    const baseOut = new Array(n);
    const baseMemo = new Array(n).fill(null);

    function baseValue(ei, prop) {
      if (baseMemo[ei] && Object.prototype.hasOwnProperty.call(baseMemo[ei], prop)) {
        const v = baseMemo[ei][prop];
        return v === null ? undefined : v;
      }
      const map = baseCands[ei];
      const w = map && map.get(prop);
      let v;
      if (w) {
        v = resolveVar(ei, w.value, 'base');
      } else if (INHERITED.has(prop) && parentIdx[ei] !== -1) {
        v = baseValue(parentIdx[ei], prop);
      } else {
        v = undefined;
      }
      if (!baseMemo[ei]) baseMemo[ei] = {};
      baseMemo[ei][prop] = v === undefined ? null : v;
      return v;
    }

    for (let ei = 0; ei < n; ei++) {
      const out = {};
      for (const p of allProps) {
        if (!baseCands[ei] && !INHERITED.has(p)) continue;
        const v = baseValue(ei, p);
        if (v !== undefined) out[p] = v;
        else if (w0(ei, p)) out[p] = '__initial__';
      }
      baseOut[ei] = out;
      function w0(ei2, p2) {
        const m = baseCands[ei2];
        return Boolean(m && m.get(p2));
      }
    }

    const ctxOut = {};
    for (const ctx of INTERACTIVE_CONTEXTS) {
      const c = ctxCands.get(ctx);
      if (!c) { ctxOut[ctx] = {}; continue; }
      const out = {};
      for (let ei = 0; ei < n; ei++) {
        const map = c[ei];
        if (!map || map.size === 0) continue;
        const el = {};
        for (const [p, w] of map) {
          el[p] = resolveVar(ei, w.value, ctx);
        }
        out[String(ei)] = el;
      }
      ctxOut[ctx] = out;
    }
    return { base: baseOut, ctx: ctxOut };
  }

  return { elements, n, paths, parentIdx, rules, matchLists, matchWarn, collectContext, tableFor };
}

export function buildTables({ repoRoot, cssFiles, fixtureHtml, label }) {
  const setup = loadSetup({ repoRoot, cssFiles, fixtureHtml });
  const { n, paths, tableFor, matchWarn } = setup;
  const results = {};
  for (const vw of VIEWPORTS) {
    for (const motion of MOTIONS) {
      const { base, ctx } = tableFor(vw, motion);
      results[`${label}-${vw}-${motion}`] = {
        n,
        elements: base.map((props, ei) => [paths[ei], props]),
        ctx: Object.fromEntries(INTERACTIVE_CONTEXTS.map((c) => [c, ctx[c]])),
      };
    }
  }
  return { results, matchWarn, n };
}

export function writeTables(outDir, results) {
  fs.mkdirSync(outDir, { recursive: true });
  for (const [name, table] of Object.entries(results)) {
    fs.writeFileSync(path.join(outDir, `${name}.json.gz`), zlib.gzipSync(JSON.stringify(table), { level: 9 }));
  }
}

export function readTables(dir) {
  const out = {};
  for (const f of fs.readdirSync(dir)) {
    if (!f.endsWith('.json.gz')) continue;
    out[f.slice(0, -'.json.gz'.length)] = JSON.parse(zlib.gunzipSync(fs.readFileSync(path.join(dir, f))).toString('utf8'));
  }
  return out;
}

// ------------------------------------------------------------- rule coverage
// For each selector: match counts per fixture state + how often the rule wins
// (base context, 1440px, no-preference — a representative context) across all
// elements/properties. Used by audit.mjs to find dead/shadowed rules.
// ------------------------------------------------------------- rule coverage
// Per selector: match counts in each fixture state + win counts in a
// representative context (base @ 1440 no-preference, plus interactive
// contexts). audit.mjs uses this to flag dead/shadowed rules.
export function ruleCoverage({ repoRoot, cssFiles, fixtures }) {
  const first = loadSetup({ repoRoot, cssFiles, fixtureHtml: fixtures[0].html });
  const { rules, collectContext } = first;
  const entries = rules.map((r) => ({
    selector: r.rawSel,
    matchSel: r.matchSel,
    media: r.media,
    context: r.context,
    pseudo: r.pseudo,
    file: r.file,
    matches: {},
    baseWins: 0,
    ctxWins: 0,
  }));
  for (const f of fixtures) {
    const dom = new JSDOM(f.html, { url: 'https://mbm.test/', pretendToBeVisual: true });
    const d = dom.window.document;
    for (let ri = 0; ri < rules.length; ri++) {
      try { entries[ri].matches[f.label] = d.querySelectorAll(rules[ri].matchSel).length; }
      catch { entries[ri].matches[f.label] = 'ERR'; }
    }
  }
  const orderToRi = new Map(rules.map((r, i) => [r.order, i]));
  const tally = (cands, field) => {
    for (const map of cands) {
      if (!map) continue;
      for (const w of map.values()) {
        const ri = orderToRi.get(w.order);
        if (ri !== undefined) entries[ri][field]++;
      }
    }
  };
  tally(collectContext(1440, 'no-preference', 'base'), 'baseWins');
  for (const ctx of INTERACTIVE_CONTEXTS) tally(collectContext(1440, 'no-preference', ctx), 'ctxWins');
  return { entries, n: first.n };
}


// CLI
const [, , fixtureArg, cssArg, outArg, labelArg] = process.argv;
if (fixtureArg && cssArg && outArg && labelArg) {
  const repoRoot = path.resolve(path.dirname(new URL(import.meta.url).pathname), '..', '..', '..');
  const { results, matchWarn, n } = buildTables({
    repoRoot,
    cssFiles: cssArg.split(',').map((s) => s.trim()).filter(Boolean),
    fixtureHtml: fs.readFileSync(path.resolve(fixtureArg), 'utf8'),
    label: labelArg,
  });
  writeTables(path.resolve(outArg), results);
  console.log(`engine: n=${n} tables=${Object.keys(results).length} match-warnings=${matchWarn.length}`);
  for (const w of matchWarn.slice(0, 10)) console.warn('  warn:', w.sel, '->', w.err);
}
