/**
 * blade-lite.js — minimal Blade → static HTML compiler for the CSS equivalence harness.
 *
 * Renders the MBM landing partials with sample data (see data.js/providers.js) so the
 * fixture DOM mirrors production markup (classes, ids, data-attrs, nesting) exactly.
 *
 * Supported syntax (the complete inventory used by the MBM partials):
 *   @php ... @endphp          → replaced by the partial's "provider" derived vars
 *   @if(cond) / @else / @endif → cond evaluated against sample data (incl. $x = expr)
 *   @foreach(EXPR as [K => ]V) → iterates sample collections
 *   @include('a.b.c')          → inlines allowed partials
 *   @push(...) ... @endpush / @section ... @endsection → dropped
 *   @csrf                      → hidden token input
 *   @selected(expr)            → "selected" / ""
 *   {{ expr }} / {!! expr !!}  → small PHP-subset expression evaluator
 *   {{-- comment --}}          → dropped
 */

export class Collection {
  constructor(arr) {
    this.arr = (arr ?? []).slice();
  }
  get length() { return this.arr.length; }
  isNotEmpty() { return this.arr.length > 0; }
  isEmpty() { return this.arr.length === 0; }
  count() { return this.arr.length; }
  take(n) { return new Collection(this.arr.slice(0, n)); }
  values() { return this; }
  all() { return this.arr.slice(); }
  first() { return this.arr[0]; }
  chunk(size) {
    const out = [];
    for (let i = 0; i < this.arr.length; i += size) out.push(new Collection(this.arr.slice(i, i + size)));
    return new Collection(out);
  }
}

export function escapeHtml(s) {
  return String(s)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

// ---------------------------------------------------------------------------
// PHP-subset expression evaluator
// ---------------------------------------------------------------------------
const OPS3 = ['===', '!=='];
const OPS2 = ['->', '??', '?:', '==', '!=', '>=', '<=', '||', '&&', '::'];
const OPS1 = ['+', '-', '.', '(', ')', '[', ']', '{', '}', ',', '?', ':', '=', '>', '<'];
const PREC = { '||': 2, '&&': 3, '===': 4, '!==': 4, '==': 4, '!=': 4, '>': 5, '<': 5, '>=': 5, '<=': 5, '+': 6, '-': 6, '.': 6 };

function tokenizeExpr(s) {
  const tokens = [];
  let i = 0;
  while (i < s.length) {
    const c = s[i];
    if (/\s/.test(c)) { i++; continue; }
    const three = s.slice(i, i + 3);
    if (OPS3.includes(three)) { tokens.push({ t: 'op', v: three }); i += 3; continue; }
    const two = s.slice(i, i + 2);
    if (OPS2.includes(two)) { tokens.push({ t: 'op', v: two }); i += 2; continue; }
    if (OPS1.includes(c)) { tokens.push({ t: 'op', v: c }); i++; continue; }
    if (c === '$') {
      const m = s.slice(i).match(/^\$[A-Za-z_][A-Za-z0-9_]*/);
      if (!m) throw new Error(`[blade-lite] bad variable at "${s.slice(i, i + 20)}"`);
      tokens.push({ t: 'var', v: m[0].slice(1) });
      i += m[0].length;
      continue;
    }
    if (/[0-9]/.test(c)) {
      const m = s.slice(i).match(/^\d+(\.\d+)?/);
      tokens.push({ t: 'num', v: parseFloat(m[0]) });
      i += m[0].length;
      continue;
    }
    if (c === "'" || c === '"') {
      let j = i + 1, out = '';
      while (j < s.length) {
        if (s[j] === '\\') { out += s[j + 1]; j += 2; continue; }
        if (s[j] === c) break;
        out += s[j]; j++;
      }
      if (j >= s.length) throw new Error(`[blade-lite] unterminated string in "${s.slice(i, i + 30)}"`);
      tokens.push({ t: 'str', v: out });
      i = j + 1;
      continue;
    }
    if (c === '\\') {
      // fully-qualified class: \App\Support\MlpProse::html
      const m = s.slice(i).match(/^\\[A-Za-z_][A-Za-z0-9_]*(\\[A-Za-z_][A-Za-z0-9_]*)*/);
      if (m) {
        tokens.push({ t: 'id', v: m[0].slice(1) });
        i += m[0].length;
        continue;
      }
    }
    if (/[A-Za-z_]/.test(c)) {
      const m = s.slice(i).match(/^[A-Za-z_][A-Za-z0-9_]*/);
      tokens.push({ t: 'id', v: m[0] });
      i += m[0].length;
      continue;
    }
    throw new Error(`[blade-lite] unexpected char "${c}" in "${s.slice(Math.max(0, i - 10), i + 20)}"`);
  }
  return tokens;
}

// --- helper implementations mirroring app/helpers.php (local-path subset) ---
function filledJS(v) {
  if (v === null || v === undefined || v === '') return false;
  if (typeof v === 'string') return v.trim() !== '';
  if (Array.isArray(v)) return v.length > 0;
  return true;
}
function mediaUrl(a, b) {
  const v = a || b || '';
  if (!filledJS(v)) return '';
  if (/^(https?:|\/\/|data:|blob:)/.test(String(v))) return String(v);
  return '/' + String(v).replace(/^\/+/, '');
}

export function makeEvaluator(ctx) {
  // scope: array of variable layers (top = last)
  const scope = ctx.scope;

  function lookup(name) {
    for (let i = scope.length - 1; i >= 0; i--) {
      if (Object.prototype.hasOwnProperty.call(scope[i], name)) return scope[i][name];
    }
    throw new Error(`[blade-lite] unknown variable $${name}`);
  }

  function parseExpr(tokens, pos, minPrec) {
    let { node: left, pos: p } = parsePrefix(tokens, pos);
    for (;;) {
      const tok = tokens[p];
      if (!tok || tok.t !== 'op') break;
      const v = tok.v;
      if (v === '??' || v === '?:') {
        if (minPrec > 1) break;
        const right = parseExpr(tokens, p + 1, 1);
        left = { k: v === '??' ? 'coalesce' : 'elvis', left, right: right.node };
        p = right.pos;
        continue;
      }
      if (v === '?') {
        if (minPrec > 1) break;
        const a = parseExpr(tokens, p + 1, 1);
        if (tokens[a.pos]?.v !== ':') throw new Error('[blade-lite] expected ":" in ternary');
        const b = parseExpr(tokens, a.pos + 1, 1);
        left = { k: 'ternary', left, a: a.node, b: b.node };
        p = b.pos;
        continue;
      }
      const prec = PREC[v];
      if (prec !== undefined && prec >= minPrec) {
        const right = parseExpr(tokens, p + 1, prec + 1);
        left = { k: 'bin', op: v, left, right: right.node };
        p = right.pos;
        continue;
      }
      break;
    }
    return { node: left, pos: p };
  }

  function parseArgs(tokens, pos) {
    // tokens[pos] === '('
    let p = pos + 1;
    const nodes = [];
    if (tokens[p]?.t === 'op' && tokens[p].v === ')') return { nodes, pos: p + 1 };
    for (;;) {
      const e = parseExpr(tokens, p, 0);
      nodes.push(e.node);
      const q = e.pos;
      if (tokens[q]?.t === 'op' && tokens[q].v === ',') { p = q + 1; continue; }
      if (tokens[q]?.t === 'op' && tokens[q].v === ')') return { nodes, pos: q + 1 };
      throw new Error('[blade-lite] bad argument list');
    }
  }

  function postfix(node, tokens, pos) {
    for (;;) {
      const tok = tokens[pos];
      if (tok?.t === 'op' && tok.v === '->') {
        const m = tokens[pos + 1];
        if (m?.t !== 'id') throw new Error('[blade-lite] expected name after ->');
        let p = pos + 2;
        if (tokens[p]?.t === 'op' && tokens[p].v === '(') {
          const args = parseArgs(tokens, p);
          node = { k: 'method', obj: node, name: m.v, args: args.nodes };
          p = args.pos;
        } else {
          node = { k: 'prop', obj: node, name: m.v };
        }
        pos = p;
        continue;
      }
      if (tok?.t === 'op' && tok.v === '[') {
        const inner = parseExpr(tokens, pos + 1, 0);
        if (tokens[inner.pos]?.v !== ']') throw new Error('[blade-lite] expected "]"');
        node = { k: 'index', obj: node, key: inner.node };
        pos = inner.pos + 1;
        continue;
      }
      break;
    }
    return { node, pos };
  }

  function parsePrefix(tokens, pos) {
    const tok = tokens[pos];
    if (!tok) throw new Error('[blade-lite] unexpected end of expression');
    if (tok.t === 'num' || tok.t === 'str') return { node: { k: 'lit', v: tok.v }, pos: pos + 1 };
    if (tok.t === 'id') {
      const name = tok.v;
      if (name === 'null') return { node: { k: 'lit', v: null }, pos: pos + 1 };
      if (name === 'true') return { node: { k: 'lit', v: true }, pos: pos + 1 };
      if (name === 'false') return { node: { k: 'lit', v: false }, pos: pos + 1 };
      if (name === 'STR_PAD_LEFT') return { node: { k: 'lit', v: 1 }, pos: pos + 1 };
      if (tokens[pos + 1]?.v === '::') {
        const m = tokens[pos + 2];
        if (m?.t !== 'id') throw new Error(`[blade-lite] expected method after ${name}::`);
        const args = parseArgs(tokens, pos + 3);
        return { node: { k: 'classcall', cls: name, method: m.v, args: args.nodes }, pos: args.pos };
      }
      if (tokens[pos + 1]?.v === '(') {
        const args = parseArgs(tokens, pos + 1);
        const r = postfix({ k: 'call', fn: name, args: args.nodes }, tokens, args.pos);
        return r;
      }
      throw new Error(`[blade-lite] bare identifier ${name}`);
    }
    if (tok.t === 'var') {
      return postfix({ k: 'var', name: tok.v }, tokens, pos + 1);
    }
    if (tok.t === 'op' && tok.v === '(') {
      const nxt = tokens[pos + 1];
      if (nxt?.t === 'id' && ['string', 'float', 'int', 'bool', 'double'].includes(nxt.v) &&
          tokens[pos + 2]?.v === ')') {
        const inner = parseExpr(tokens, pos + 3, 0);
        return { node: { k: 'cast', type: nxt.v, expr: inner.node }, pos: inner.pos };
      }
      const inner = parseExpr(tokens, pos + 1, 0);
      if (tokens[inner.pos]?.v !== ')') throw new Error('[blade-lite] expected ")"');
      return { node: inner.node, pos: inner.pos + 1 };
    }
    if (tok.t === 'op' && tok.v === '-') {
      const inner = parseExpr(tokens, pos + 1, 6);
      return { node: { k: 'neg', expr: inner.node }, pos: inner.pos };
    }
    if (tok.t === 'op' && tok.v === '[') {
      let p = pos + 1;
      const items = [];
      if (tokens[p]?.v === ']') return { node: { k: 'arr', items }, pos: p + 1 };
      for (;;) {
        const val = parseExpr(tokens, p, 0);
        let q = val.pos;
        if (tokens[q]?.v === '=>') {
          const key = parseExpr(tokens, q + 1, 0);
          items.push({ key: key.node, val: val.node });
          q = key.pos;
        } else {
          items.push({ val: val.node });
        }
        if (tokens[q]?.v === ',') { p = q + 1; continue; }
        if (tokens[q]?.v === ']') return { node: { k: 'arr', items }, pos: q + 1 };
        throw new Error('[blade-lite] bad array literal');
      }
    }
    throw new Error(`[blade-lite] unexpected token ${tok.t}:${tok.v}`);
  }

  const oldValues = {};

  function callFunction(name, args) {
    const [a, b, c, d] = args;
    switch (name) {
      case 'filled': {
        if (a === null || a === undefined || a === '') return false;
        if (typeof a === 'string') return a.trim() !== '';
        if (Array.isArray(a)) return a.length > 0;
        return true;
      }
      case 'empty': {
        if (a === null || a === undefined || a === '') return true;
        if (typeof a === 'string') return a.trim() === '';
        if (Array.isArray(a)) return a.length === 0;
        return false;
      }
      case 'trim': return String(a ?? '').trim();
      case 'strtolower': return String(a ?? '').toLowerCase();
      case 'strtoupper': return String(a ?? '').toUpperCase();
      case 'strip_tags': return String(a ?? '').replace(/<[^>]*>/g, '');
      case 'str_contains': return String(a ?? '').includes(String(b ?? ''));
      case 'str_pad': return String(a ?? '').padStart(Number(b) || 0, String(c ?? ' '));
      case 'explode': {
        let parts = String(b ?? '').split(String(a ?? ''));
        if (c !== undefined) parts = parts.slice(0, Number(c));
        return parts;
      }
      case 'number_format': return Number(a ?? 0).toFixed(Number(b ?? 0));
      case 'rtrim': return String(a ?? '').replace(/[\s.]+$/, '');
      case 'ltrim': return String(a ?? '').replace(/^\s+/, '');
      case 'max': return Math.max(...args);
      case 'min': return Math.min(...args);
      case 'count': {
        if (a instanceof Collection) return a.count();
        if (Array.isArray(a)) return a.length;
        if (a && typeof a === 'object') return Object.keys(a).length;
        throw new Error('[blade-lite] count() on scalar');
      }
      case 'mb_substr':
      case 'substr': {
        const s = String(a ?? '');
        const start = Number(b);
        const len = c === undefined ? undefined : Number(c);
        return len === undefined ? s.slice(start) : s.slice(start, start + len);
      }
      case 'mb_strtolower': return String(a ?? '').toLowerCase();
      case 'mb_strtoupper': return String(a ?? '').toUpperCase();
      case 'strcasecmp': return String(a ?? '').toLowerCase() === String(b ?? '').toLowerCase() ? 0 : 1;
      case 'ceil': return Math.ceil(Number(a));
      case 'preg_replace': {
        const map = {
          '/[^0-9.]/': /[^\d.]/g,
          '/[0-9.,\\s]/': /[\d.,\s]/g,
          '/^(?:Executive\\s+)?MBA\\s+in\\s+/i': /^(?:Executive\s+)?MBA\s+in\s+/i,
        };
        const re = map[String(a)];
        if (!re) throw new Error(`[blade-lite] unknown preg_replace pattern ${a}`);
        return String(b ?? '').replace(re, String(c ?? ''));
      }
      case 'preg_split': return String(a ?? '').split(/\s+/).filter(Boolean);
      case 'session': return null;
      case 'old': return Object.prototype.hasOwnProperty.call(oldValues, String(a)) ? oldValues[a] : (b ?? '');
      case 'route': return a === 'mba-masters-landing.enquire' ? '/online-mba-masters-uae/enquire' : `/${String(a ?? '')}`;
      case 'media_url': return mediaUrl(a, b);
      case 'settings_media_url': {
        const v = a && a[b];
        return filledJS(v) ? mediaUrl(v) : '';
      }
      case 'mlp_image_url': {
        const fb = b && b.fallback;
        const u = mediaUrl(a, fb);
        if (!u) return '';
        if (!u.includes('/upload/')) return u;
        if (/\/upload\/(?:[^/]+,)?f_auto[,\/]/.test(u)) return u;
        const width = (b && (b.w ?? b.width)) || 1600;
        return u.replace('/upload/', `/upload/f_auto,q_auto,w_${width}/`);
      }
      case 'cached_asset': return '/' + String(a ?? '').replace(/^\/+/, '');
      case 'mlp_image_url': {
        const opts = c || {};
        return a || opts.fallback || '';
      }
      case 'cached_asset': return `/${String(a)}`;
      case 'asset': return `/${String(a)}`;
      case 'edu_href': return a || null;
      case 'youtube_thumbnail_url': return 'https://i.ytimg.com/vi/4p0rsCEljgo/hqdefault.jpg';
      case 'youtube_embed_url': return 'https://www.youtube.com/embed/4p0rsCEljgo?autoplay=1';
      case 'collect': return new Collection(a);
      case 'e': return escapeHtml(a ?? '');
      case 'url': return `/${String(a ?? '')}`;
      default:
        throw new Error(`[blade-lite] unknown function ${name}()`);
    }
  }

  function callClass(cls, method, args) {
    const plain = cls.replace(/^\\?App\\Support\\/, '').replace(/^\\?Illuminate\\Support\\/, '');
    if (plain === 'MlpProse') {
      const x = String(args[0] ?? '');
      if (x.startsWith('UL:')) {
        return `<p>${x.slice(3)}</p><ul><li>Sample point one</li><li>Sample point two</li></ul>`;
      }
      if (x.startsWith('OL:')) {
        return `<p>${x.slice(3)}</p><ol><li>First step</li><li>Second step</li></ol>`;
      }
      return `<p>${x}</p>`;
    }
    if (plain === 'Str') {
      if (method === 'slug') return String(args[0] ?? '').toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
      if (method === 'lower') return String(args[0] ?? '').toLowerCase();
      if (method === 'uuid') return 'fixture-uuid-0001';
      throw new Error(`[blade-lite] unknown Str::${method}`);
    }
    throw new Error(`[blade-lite] unknown class call ${cls}::${method}`);
  }

  function evalNode(n) {
    switch (n.k) {
      case 'lit': return n.v;
      case 'var': return lookup(n.name);
      case 'neg': return -evalNode(n.expr);
      case 'bin': {
        const op = n.op;
        if (op === '+') return evalNode(n.left) + evalNode(n.right);
        if (op === '-') return evalNode(n.left) - evalNode(n.right);
        if (op === '.') return `${evalNode(n.left) ?? ''}${evalNode(n.right) ?? ''}`;
        if (op === '===') return evalNode(n.left) === evalNode(n.right);
        if (op === '!==') return evalNode(n.left) !== evalNode(n.right);
        if (op === '==') return evalNode(n.left) == evalNode(n.right);
        if (op === '!=') return evalNode(n.left) != evalNode(n.right);
        if (op === '||') return evalNode(n.left) || evalNode(n.right);
        if (op === '&&') return evalNode(n.left) && evalNode(n.right);
        const l = evalNode(n.left);
        const r = evalNode(n.right);
        if (op === '>') return l > r;
        if (op === '<') return l < r;
        if (op === '>=') return l >= r;
        if (op === '<=') return l <= r;
        throw new Error(`[blade-lite] unknown op ${op}`);
      }
      case 'coalesce': {
        const a = evalNode(n.left);
        return a === null || a === undefined ? evalNode(n.right) : a;
      }
      case 'elvis': {
        const a = evalNode(n.left);
        return a ? a : evalNode(n.right);
      }
      case 'ternary': return evalNode(n.left) ? evalNode(n.a) : evalNode(n.b);
      case 'cast': {
        const v = evalNode(n.expr);
        if (n.type === 'string') return String(v ?? '');
        if (n.type === 'int') return parseInt(v, 10);
        if (n.type === 'float' || n.type === 'double') return parseFloat(v);
        if (n.type === 'bool') return Boolean(v);
        throw new Error(`[blade-lite] unknown cast ${n.type}`);
      }
      case 'prop': {
        const o = evalNode(n.obj);
        if (o === null || o === undefined) return null;
        return o[n.name] ?? null;
      }
      case 'index': {
        const o = evalNode(n.obj);
        const k = evalNode(n.key);
        if (o === null || o === undefined) return null;
        if (o instanceof Collection) return o.arr[k] ?? null;
        return o[k] ?? null;
      }
      case 'method': {
        const o = evalNode(n.obj);
        if (o === null || o === undefined) throw new Error(`[blade-lite] call ${n.name}() on null`);
        const args = n.args.map(evalNode);
        if (typeof o[n.name] !== 'function') throw new Error(`[blade-lite] unknown method ${n.name} on ${typeof o}`);
        return o[n.name](...args);
      }
      case 'call': return callFunction(n.fn, n.args.map(evalNode));
      case 'classcall': return callClass(n.cls, n.method, n.args.map(evalNode));
      case 'arr': {
        const obj = {};
        let auto = 0;
        for (const it of n.items) {
          if (it.key) obj[String(evalNode(it.key))] = evalNode(it.val);
          else obj[auto++] = evalNode(it.val);
        }
        return obj;
      }
      default:
        throw new Error(`[blade-lite] unknown node ${n.k}`);
    }
  }

  return {
    pushLayer(obj) { scope.push(obj); },
    popLayer() { scope.pop(); },
    depth() { return scope.length; },
    popTo(depth) { while (scope.length > depth) scope.pop(); },
    scopeView() {
      const view = {};
      for (const layer of scope) Object.assign(view, layer);
      return view;
    },
    evaluate(expr) {
      const src = String(expr).trim();
      if (src === '') return '';
      const tokens = tokenizeExpr(src);
      const { node, pos } = parseExpr(tokens, 0, 0);
      if (pos < tokens.length) {
        // assignment condition: $x = expr (e.g. @if($url = media_url(...)))
        if (tokens[0].t === 'var' && tokens[1]?.v === '=') {
          const { node: rhs } = parseExpr(tokens, 2, 0);
          const val = evalNode(rhs);
          scope.push({ [tokens[0].v]: val });
          return val;
        }
        throw new Error(`[blade-lite] trailing tokens in "${src}"`);
      }
      return evalNode(node);
    },
  };
}

// ---------------------------------------------------------------------------
// Blade tokenizer + compiler
// ---------------------------------------------------------------------------
function readBalanced(src, start) {
  let depth = 0;
  for (let i = start; i < src.length; i++) {
    const ch = src[i];
    if (ch === "'" || ch === '"') {
      const end = src.indexOf(ch, i + 1);
      if (end === -1) throw new Error('[blade-lite] unbalanced quote');
      i = end;
      continue;
    }
    if (ch === '(') depth++;
    else if (ch === ')') { depth--; if (depth === 0) return i + 1; }
  }
  throw new Error('[blade-lite] unbalanced parens');
}

export function tokenizeBlade(src) {
  const tokens = [];
  let i = 0;
  while (i < src.length) {
    const at = src.indexOf('@', i);
    const bb = src.indexOf('{{', i);
    const braw = src.indexOf('{!!', i);
    const cands = [];
    if (at !== -1) cands.push([at, 'dir']);
    if (bb !== -1) cands.push([bb, 'expr']);
    if (braw !== -1) cands.push([braw, 'raw']);
    if (cands.length === 0) {
      if (i < src.length) tokens.push({ type: 'text', text: src.slice(i) });
      break;
    }
    cands.sort((x, y) => x[0] - y[0]);
    const [p, kind] = cands[0];
    if (p > i) tokens.push({ type: 'text', text: src.slice(i, p) });

    if (kind === 'expr') {
      // blade comment? "{{- ... --}}"
      if (src.slice(p, p + 3) === '{{-') {
        const end = src.indexOf('--}}', p);
        if (end === -1) throw new Error('[blade-lite] unterminated blade comment');
        i = end + 4;
        continue;
      }
      const end = src.indexOf('}}', p + 2);
      if (end === -1) throw new Error('[blade-lite] unterminated {{ }}');
      tokens.push({ type: 'expr', text: src.slice(p + 2, end) });
      i = end + 2;
      continue;
    }
    if (kind === 'raw') {
      const end = src.indexOf('!!}', p + 3);
      if (end === -1) throw new Error('[blade-lite] unterminated {!! !!}');
      tokens.push({ type: 'expr', text: src.slice(p + 3, end), raw: true });
      i = end + 3;
      continue;
    }
    const m = src.slice(p).match(/^@([A-Za-z_]+)/);
    if (!m) throw new Error(`[blade-lite] bad directive at ${p}`);
    const name = m[1];
    let j = p + m[0].length;
    if (name === 'php') {
      const end = src.indexOf('@endphp', j);
      if (end === -1) throw new Error('[blade-lite] unterminated @php');
      tokens.push({ type: 'php', body: src.slice(j, end) });
      i = end + 7;
      continue;
    }
    let args = null;
    let k = j;
    while (k < src.length && /\s/.test(src[k])) k++;
    if (src[k] === '(') {
      const end = readBalanced(src, k);
      args = src.slice(k + 1, end - 1);
      j = end;
    }
    tokens.push({ type: 'directive', name, args });
    i = j;
  }
  return tokens;
}

function findMatching(tokens, startIdx, openName, closeName) {
  let depth = 0;
  for (let i = startIdx; i < tokens.length; i++) {
    const t = tokens[i];
    if (t.type === 'directive') {
      if (t.name === openName) depth++;
      else if (t.name === closeName) {
        depth--;
        if (depth === 0) return i;
      }
    }
  }
  throw new Error(`[blade-lite] no matching @${closeName}`);
}

export function compileTokens(tokens, ctx) {
  let out = '';
  const n = tokens.length;
  let idx = 0;
  let pushDepth = 0;
  const ifStack = [];

  const active = () => pushDepth === 0 && ifStack.every((f) => f.active);
  function emit(s) { if (active()) out += s; }

  while (idx < n) {
    const tok = tokens[idx];

    if (tok.type === 'text') { emit(tok.text); idx++; continue; }

    if (tok.type === 'expr') {
      const val = ctx.ev.evaluate(tok.text);
      emit(tok.raw ? String(val ?? '') : escapeHtml(String(val ?? '')));
      idx++;
      continue;
    }

    if (tok.type === 'php') {
      const provider = ctx.providers?.[ctx.current];
      if (provider) {
        const vars = provider({ ...ctx, scope: ctx.ev.scopeView() });
        if (vars) ctx.ev.pushLayer(vars);
      }
      idx++;
      continue;
    }

    if (tok.type !== 'directive') throw new Error(`[blade-lite] unhandled token ${tok.type}`);
    const { name, args } = tok;
    switch (name) {
      case 'if': {
        let cond;
        const am = String(args ?? '').match(/^\s*\$([A-Za-z_][A-Za-z0-9_]*)\s*=\s*([\s\S]+)$/);
        if (am) {
          const val = ctx.ev.evaluate(am[2]);
          ctx.ev.pushLayer({ [am[1]]: val });
          cond = val;
        } else {
          cond = ctx.ev.evaluate(args);
        }
        ifStack.push({ active: !!cond, preDepth: ctx.ev.depth() });
        idx++;
        continue;
      }
      case 'else': {
        const f = ifStack[ifStack.length - 1];
        if (!f) throw new Error('[blade-lite] @else without @if');
        f.active = !f.active;
        idx++;
        continue;
      }
      case 'endif': {
        const f = ifStack.pop();
        // drop the assignment layer (e.g. @if($url = ...)) and any @php layers
        // pushed inside the if/else branches — they must not leak past @endif
        if (f && typeof f.preDepth === 'number') ctx.ev.popTo(f.preDepth);
        idx++;
        continue;
      }
      case 'foreach': {
        const hm = String(args ?? '').match(/^\s*(.+?)\s+as\s+(.+?)\s*$/);
        if (!hm) throw new Error(`[blade-lite] bad foreach header: ${args}`);
        const listExpr = hm[1];
        const varPart = hm[2];
        let keyName = null, varName;
        const km = varPart.match(/^(\$[A-Za-z_][A-Za-z0-9_]*)\s*=>\s*(\$[A-Za-z_][A-Za-z0-9_]*)$/);
        if (km) {
          keyName = km[1].slice(1);
          varName = km[2].slice(1);
        } else {
          varName = varPart.replace(/^\$/, '');
        }
        const endIdx = findMatching(tokens, idx, 'foreach', 'endforeach');
        const body = tokens.slice(idx + 1, endIdx);
        if (active()) {
          const list = ctx.ev.evaluate(listExpr);
          const arr = list instanceof Collection ? list.arr : Array.isArray(list) ? list : [];
          for (let e = 0; e < arr.length; e++) {
            const layer = {};
            if (keyName) layer[keyName] = e;
            layer[varName] = arr[e];
            const before = ctx.ev.depth();
            ctx.ev.pushLayer(layer);
            const sub = compileTokens(body, ctx);
            out += sub;
            // pop the iteration layer AND any @php layers pushed inside the body
            ctx.ev.popTo(before);
          }
        }
        idx = endIdx + 1;
        continue;
      }
      case 'endforeach':
        throw new Error('[blade-lite] stray @endforeach');
      case 'include': {
        const pm = String(args ?? '').match(/^'([^']+)'/);
        if (!pm) throw new Error(`[blade-lite] bad include args: ${args}`);
        const path = pm[1].replace(/\./g, '/');
        if (!SUPPORTED_INCLUDES.has(path)) {
          ctx.log.push(`skip include ${pm[1]}`);
          idx++;
          continue;
        }
        const file = `${ctx.viewsRoot}/${path}.blade.php`;
        const src = ctx.readFile(file);
        const subTokens = tokenizeBlade(src);
        const sub = compileTokens(subTokens, { ...ctx, current: path.split('/').pop().replace('.blade.php', '') });
        emit(sub);
        idx++;
        continue;
      }
      case 'push':
      case 'section':
        pushDepth++;
        idx++;
        continue;
      case 'endpush':
      case 'endsection':
        pushDepth = Math.max(0, pushDepth - 1);
        idx++;
        continue;
      case 'csrf':
        emit('<input type="hidden" name="_token" value="fixture-token">');
        idx++;
        continue;
      case 'selected': {
        const cond = ctx.ev.evaluate(args);
        emit(cond ? 'selected' : '');
        idx++;
        continue;
      }
      case 'yield':
      case 'extends':
      case 'hasSection':
      case 'endhasSection':
      case 'unless':
      case 'endunless':
      case 'endelse':
        idx++;
        continue;
      default:
        ctx.log.push(`unknown directive @${name}`);
        idx++;
        continue;
    }
  }
  return out;
}

const SUPPORTED_INCLUDES = new Set([
  'pages/mba-masters-landing/partials/enquire-form',
]);
