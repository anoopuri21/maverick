// specificity.js — CSS specificity (a, b, c) for a single compound-ish selector
// (one comma-separated part). Semantics:
//   #id / .class / [attr] / most :pseudo-classes  → b
//   element / pseudo-element                       → c
//   :not(X) / :is(X) / :has(X) → max/sum of inner (CSS spec: :not & :is take the
//   highest specificity of their arguments; :where takes zero).

const PSEUDO_ELEMENT = new Set([
  'before', 'after', 'first-line', 'first-letter', 'marker', 'selection',
  'placeholder', 'backdrop', 'file-selector-button',
]);

function countBalanced(s, start) {
  // s[start] === '(' ; return index just past matching ')'
  let depth = 0;
  for (let i = start; i < s.length; i++) {
    if (s[i] === '(') depth++;
    else if (s[i] === ')') { depth--; if (depth === 0) return i + 1; }
  }
  return -1;
}

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

function specOfSelectorPart(part) {
  let a = 0, b = 0, c = 0;
  const s = part.trim();
  let i = 0;
  while (i < s.length) {
    const ch = s[i];
    if (ch === '#') {
      a++; i++;
      while (i < s.length && /[A-Za-z0-9_-]/.test(s[i])) i++;
      continue;
    }
    if (ch === '.') {
      b++; i++;
      while (i < s.length && /[A-Za-z0-9_-]/.test(s[i])) i++;
      continue;
    }
    if (ch === '[') {
      b++;
      i = s.indexOf(']', i);
      if (i === -1) break;
      i++;
      continue;
    }
    if (ch === ':') {
      const m = s.slice(i).match(/^:([a-zA-Z-]+)/);
      if (!m) { i++; continue; }
      const name = m[1].toLowerCase();
      i += m[0].length;
      if (name === 'where') {
        if (s[i] === '(') i = countBalanced(s, i) === -1 ? s.length : countBalanced(s, i);
        continue; // contributes nothing
      }
      if (name === 'not' || name === 'is' || name === 'has' || name === 'any' || name === 'dir' || name === 'lang') {
        if (s[i] === '(') {
          const end = countBalanced(s, i);
          if (end === -1) { b++; break; }
          const inner = s.slice(i + 1, end - 1);
          const parts = splitTop(inner, ',');
          let ma = 0, mb = 0, mc = 0;
          for (const p of parts) {
            const r = specOfSelectorPart(p);
            ma = Math.max(ma, r[0]); mb = Math.max(mb, r[1]); mc = Math.max(mc, r[2]);
          }
          a += ma; b += mb; c += mc;
          i = end;
          continue;
        }
        b++;
        continue;
      }
      if (name === 'nth-child' || name === 'nth-last-child') {
        b++;
        if (s[i] === '(') i = countBalanced(s, i) === -1 ? s.length : countBalanced(s, i);
        continue;
      }
      if (PSEUDO_ELEMENT.has(name)) { c++; continue; }
      b++;
      continue;
    }
    if (ch === '>') { i++; continue; }
    if (ch === '~') { i++; continue; }
    if (ch === ' ') { while (i < s.length && s[i] === ' ') i++; continue; }
    if (ch === '*') { i++; continue; }
    if (ch === '&') { i++; continue; }
    if (/[A-Za-z_]/.test(ch)) {
      c++; // element name
      while (i < s.length && /[A-Za-z0-9_-]/.test(s[i])) i++;
      continue;
    }
    i++;
  }
  return [a, b, c];
}

export function specificity(selector) {
  const parts = splitTop(selector, ',');
  let a = 0, b = 0, c = 0;
  for (const p of parts) {
    if (!p.trim()) continue;
    const [pa, pb, pc] = specOfSelectorPart(p);
    a += pa; b += pb; c += pc;
  }
  return [a, b, c];
}
