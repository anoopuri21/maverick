// compare.mjs — diff two winner-table directories (baseline vs candidate).
// Table files are named {state}-{viewport}-{motion}.json.gz.
// Usage: node compare.mjs <baseDir> <candDir> [maxReport]
// Exit 0 = identical, 1 = differences found, 2 = missing/uneven tables.

import fs from 'node:fs';
import path from 'node:path';
import { readTables } from './engine.mjs';

const [, , baseDir, candDir, maxReportArg] = process.argv;
if (!baseDir || !candDir) {
  console.error('usage: node compare.mjs <baseDir> <candDir> [maxReport]');
  process.exit(2);
}
const maxReport = maxReportArg ? Number(maxReportArg) : 40;
const base = readTables(baseDir);
const cand = readTables(candDir);

const baseNames = new Set(Object.keys(base));
const candNames = new Set(Object.keys(cand));
const missingInCand = [...baseNames].filter((n) => !candNames.has(n));
const missingInBase = [...candNames].filter((n) => !baseNames.has(n));
if (missingInCand.length || missingInBase.length) {
  console.error('table mismatch:');
  if (missingInCand.length) console.error('  missing in candidate:', missingInCand.slice(0, 10));
  if (missingInBase.length) console.error('  missing in baseline:', missingInBase.slice(0, 10));
  process.exit(2);
}

const names = [...baseNames].sort();
let totalDiffs = 0;
let totalElements = 0;
let totalProps = 0;
const reported = [];

for (const name of names) {
  const B = base[name];
  const C = cand[name];
  if (B.n !== C.n) {
    totalDiffs++;
    reported.push({ table: name, where: '(element count)', base: B.n, cand: C.n });
    continue;
  }
  // base context
  for (let i = 0; i < B.elements.length; i++) {
    const [pb, Ab] = B.elements[i];
    const [pc, Ac] = C.elements[i];
    if (pb !== pc) {
      totalDiffs++;
      reported.push({ table: name, where: `elem ${i} (path)`, base: pb, cand: pc });
      continue;
    }
    const props = new Set([...Object.keys(Ab), ...Object.keys(Ac)]);
    for (const p of props) {
      totalProps++;
      const vb = Ab[p];
      const vc = Ac[p];
      if (vb !== vc) {
        totalDiffs++;
        if (reported.length < maxReport) reported.push({ table: name, where: `${i} ${trunc(pb)} [${p}]`, base: vb, cand: vc });
      }
    }
    totalElements++;
    // interactive context deltas
    for (const ctx of ['hover', 'focus', 'focus-visible', 'focus-within']) {
      const cb = B.ctx?.[ctx] ?? {};
      const cc = C.ctx?.[ctx] ?? {};
      const keys = new Set([...Object.keys(cb), ...Object.keys(cc)]);
      for (const k of keys) {
        const eb = cb[k] ?? {};
        const ec = cc[k] ?? {};
        const cprops = new Set([...Object.keys(eb), ...Object.keys(ec)]);
        for (const p of cprops) {
          totalProps++;
          const vb = eb[p];
          const vc = ec[p];
          if (vb !== vc) {
            totalDiffs++;
            if (reported.length < maxReport) reported.push({ table: name, where: `${k} ${trunc(pc)} [${ctx} ${p}]`, base: vb, cand: vc });
          }
        }
      }
    }
  }
}

console.log(`tables compared: ${names.length}`);
console.log(`elements: ${totalElements}   props checked: ${totalProps}`);
console.log(`DIFFS: ${totalDiffs}`);
if (reported.length) {
  console.log(`\nfirst ${reported.length} differences:`);
  for (const r of reported) {
    console.log(`  [${r.table}] ${r.where}\n      base: ${str(r.base)}\n      cand: ${str(r.cand)}`);
  }
}
process.exit(totalDiffs > 0 ? 1 : 0);

function trunc(s) {
  return s.length > 70 ? '…' + s.slice(-70) : s;
}
function str(v) {
  if (v === undefined) return '∅ (absent)';
  const s = String(v);
  return s.length > 80 ? s.slice(0, 80) + '…' : s;
}
