// check-tables.mjs — sanity checks on engine output (manual verification hooks)
import { readTables } from './engine.mjs';

const dir = process.argv[2] || '/tmp/engine-test';
const tables = readTables(dir);
const name = process.argv[3] || 's1-1440-no-preference';
const t = tables[name];
if (!t) { console.error('no table', name, 'have:', Object.keys(tables).slice(0, 5)); process.exit(1); }

function find(pred) {
  const out = [];
  t.elements.forEach(([p, props], i) => { if (pred(p, props)) out.push([i, p, props]); });
  return out;
}

console.log('--- .mlp-mba__tab (first, is-active) ---');
for (const [i, p, props] of find((p) => /mlp-mba__tab$/.test(p) || /mlp-mba__tab\./.test(p)).slice(0, 3)) {
  console.log(i, p);
  for (const k of ['color', 'background-color', 'border-color', 'font-size', 'font-weight', 'border-top-color']) {
    if (props[k]) console.log('   ', k, '=', props[k]);
  }
}

console.log('--- hero headline (h1 inside mlp-hero) ---');
for (const [i, p, props] of find((p) => /mlp-hero/.test(p) && /h1$/.test(p)).slice(0, 2)) {
  console.log(i, p);
  for (const k of ['color', 'font-size', 'font-weight', 'line-height']) if (props[k]) console.log('   ', k, '=', props[k]);
}

console.log('--- html element custom props (sample) ---');
const html = t.elements[0];
console.log(html[0], Object.keys(html[1]).filter((k) => k.startsWith('--')).slice(0, 8));
console.log('   --mlp-red =', html[1]['--mlp-red']);
console.log('   --mlp-navy =', html[1]['--mlp-navy']);

console.log('--- sticky button hover delta ---');
const hover = t.ctx.hover;
const hoverKeys = Object.keys(hover);
console.log('hover-delta elements:', hoverKeys.length);
for (const [i, props] of Object.entries(hover).slice(0, 3)) {
  console.log('  el', i, t.elements[Number(i)][0], JSON.stringify(props).slice(0, 160));
}

console.log('--- body color (inheritance check) ---');
const body = find((p) => p === 'body')[0];
if (body) console.log('body color =', body[2].color, 'font-family =', (body[2]['font-family'] || '').slice(0, 40));

console.log('--- element with inline --mlp-specialization-rows ---');
for (const [i, p, props] of find((p) => /mlp-mba__programs/.test(p)).slice(0, 2)) {
  console.log(i, p, 'rows=', props['--mlp-specialization-rows']);
}
