// expand.js — expand CSS shorthand declarations into longhands.
// Values are kept as raw strings (no numeric evaluation); this mirrors how
// the cascade actually works (a shorthand is a group of longhand declarations
// whose unspecified components reset to initial).

const INITIAL = {
  borderWidth: 'medium',
  borderStyle: 'none',
  borderColor: 'currentcolor',
  fontStyle: 'normal',
  fontWeight: 'normal',
  fontSize: 'medium',
  lineHeight: 'normal',
  textDecorationLine: 'none',
  textDecorationStyle: 'solid',
  textDecorationColor: 'currentcolor',
  listStyleType: '',
  listStyleImage: 'none',
  listStylePosition: 'outside',
};

const BORDER_STYLES = new Set([
  'none', 'hidden', 'dotted', 'dashed', 'solid', 'double', 'groove', 'ridge', 'inset', 'outset',
]);
const BORDER_WIDTHS = new Set(['thin', 'medium', 'thick']);

function topSplit(value, sep) {
  const out = [];
  let depth = 0, cur = '';
  for (const ch of value) {
    if (ch === '(') depth++;
    else if (ch === ')') depth--;
    if (ch === sep && depth === 0) { out.push(cur); cur = ''; } else cur += ch;
  }
  out.push(cur);
  return out.map((s) => s.trim()).filter((s) => s !== '');
}

function topWords(value) {
  // split on top-level whitespace
  const out = [];
  let depth = 0, cur = '';
  for (const ch of value) {
    if (ch === '(') depth++;
    else if (ch === ')') depth--;
    if (/\s/.test(ch) && depth === 0) {
      if (cur) { out.push(cur); cur = ''; }
    } else cur += ch;
  }
  if (cur) out.push(cur);
  return out;
}

function sides(value, longhand, fallback) {
  const parts = topWords(value);
  if (parts.length > 4) return null;
  const map = {};
  const [t, r = t, b = t, l = r] = parts;
  map[`${longhand}-top`] = t;
  map[`${longhand}-right`] = r;
  map[`${longhand}-bottom`] = b;
  map[`${longhand}-left`] = l;
  return map;
}

function boxSides(value, longhand) {
  const parts = topWords(value);
  if (parts.length > 4) return null;
  const [t, r = t, b = t, l = r] = parts;
  return {
    [`${longhand}-top`]: t,
    [`${longhand}-right`]: r,
    [`${longhand}-bottom`]: b,
    [`${longhand}-left`]: l,
  };
}

function borderTokens(value) {
  const words = topWords(value);
  let width = null, style = null, color = null;
  for (const w of words) {
    const wl = w.toLowerCase();
    if (width === null && (BORDER_WIDTHS.has(wl) || /^-?\d/.test(w) || /^(thin|medium|thick)$/.test(wl))) {
      width = w;
    } else if (style === null && BORDER_STYLES.has(wl)) {
      style = w;
    } else if (color === null) {
      color = w;
    } else return null;
  }
  return { width, style, color };
}

function expandBorderShorthand(side, value) {
  const t = borderTokens(value);
  if (!t) return null;
  const map = {};
  if (side) {
    if (t.width) map[`border-${side}-width`] = t.width;
    if (t.style) map[`border-${side}-style`] = t.style;
    if (t.color) map[`border-${side}-color`] = t.color;
    // unspecified side components reset to initial
    if (!t.width) map[`border-${side}-width`] = INITIAL.borderWidth;
    if (!t.style) map[`border-${side}-style`] = INITIAL.borderStyle;
    if (!t.color) map[`border-${side}-color`] = INITIAL.borderColor;
    return map;
  }
  // `border:` shorthand also sets the three aggregate longhands
  map['border-width'] = t.width ?? INITIAL.borderWidth;
  map['border-style'] = t.style ?? INITIAL.borderStyle;
  map['border-color'] = t.color ?? INITIAL.borderColor;
  return map;
}

function expandTransition(value) {
  const tracks = topSplit(value, ',');
  const props = [], durs = [], timings = [], delays = [];
  const EASINGS = new Set(['ease', 'linear', 'ease-in', 'ease-out', 'ease-in-out', 'step-start', 'step-end']);
  for (const track of tracks) {
    const words = topWords(track);
    let prop = null, dur = null, timing = null, delay = null;
    for (const w of words) {
      const wl = w.toLowerCase();
      if (/^cubic-bezier\(/.test(wl) || EASINGS.has(wl)) {
        if (timing === null) timing = w;
      } else if (/^-?(\d+\.?\d*|\d*\.\d+)(ms|s)$/.test(wl)) {
        if (dur === null) dur = w;
        else delay = w;
      } else {
        if (prop === null) prop = w;
      }
    }
    props.push(prop ?? 'all');
    durs.push(dur ?? '0s');
    timings.push(timing ?? 'ease');
    delays.push(delay ?? '0s');
  }
  return {
    'transition-property': props.join(', '),
    'transition-duration': durs.join(', '),
    'transition-timing-function': timings.join(', '),
    'transition-delay': delays.join(', '),
  };
}

function expandFont(value) {
  const words = topWords(value);
  if (words.length < 3) return null;
  let i = 0;
  const out = {};
  const FONTS = new Set(['italic', 'oblique']);
  const WEIGHTS = new Set(['normal', 'bold', 'bolder', 'lighter', '100', '200', '300', '400', '500', '600', '700', '800', '900']);
  if (FONTS.has(words[i].toLowerCase())) { out['font-style'] = words[i]; i++; }
  if (i < words.length && WEIGHTS.has(words[i].toLowerCase()) && !/^[\d.]/.test(words[i]) && !WEIGHTS.has(words[i])) {
    // weight only if followed by size
  }
  if (i < words.length && WEIGHTS.has(words[i].toLowerCase())) { out['font-weight'] = words[i]; i++; }
  // size (optionally /line-height)
  if (i >= words.length) return null;
  const sizeTok = words[i];
  if (sizeTok.includes('/')) {
    const [s, lh] = sizeTok.split('/');
    out['font-size'] = s;
    out['line-height'] = lh;
  } else {
    out['font-size'] = sizeTok;
  }
  i++;
  out['font-family'] = words.slice(i).join(' ');
  if (!out['font-style']) out['font-style'] = INITIAL.fontStyle;
  if (!out['font-weight']) out['font-weight'] = INITIAL.fontWeight;
  if (!out['line-height']) out['line-height'] = INITIAL.lineHeight;
  return out;
}

function isGradient(w) {
  const wl = w.toLowerCase();
  return wl.startsWith('linear-gradient(') || wl.startsWith('radial-gradient(') || wl.startsWith('conic-gradient(') || wl.startsWith('repeating-');
}

function expandBackground(value) {
  const layers = topSplit(value, ',');
  const images = [];
  let color = null;
  for (const layer of layers) {
    if (isGradient(layer) || layer.startsWith('url(') || layer.toLowerCase().startsWith('url')) {
      images.push(layer);
    } else {
      // single color-ish token (var, hex, rgb(a), named, transparent)
      const w = topWords(layer);
      if (w.length === 1) color = layer;
      else return null; // position/size/repeat modifiers present — treat as raw
    }
  }
  const map = {};
  if (images.length) map['background-image'] = images.length === 1 ? images[0] : images.join(', ');
  if (color) map['background-color'] = color;
  return map;
}

const ANIM_KEYWORDS = {
  'infinite': 'iteration', 'alternate': 'direction', 'alternate-reverse': 'direction',
  'reverse': 'direction', 'normal': 'direction', 'forwards': 'fill', 'backwards': 'fill',
  'both': 'fill', 'none': 'fill-or-name', 'running': 'play', 'paused': 'play',
  'ease': 'timing', 'linear': 'timing', 'ease-in': 'timing', 'ease-out': 'timing',
  'ease-in-out': 'timing', 'step-start': 'timing', 'step-end': 'timing',
};

function expandAnimation(value) {
  const v = value.trim();
  if (v === 'none') return { 'animation-name': 'none' };
  const words = topWords(v);
  const out = {
    'animation-name': '', 'animation-duration': '0s', 'animation-timing-function': 'ease',
    'animation-delay': '0s', 'animation-iteration-count': '1', 'animation-direction': 'normal',
    'animation-fill-mode': 'none', 'animation-play-state': 'running',
  };
  let name = null;
  for (const w of words) {
    const wl = w.toLowerCase();
    if (/^-?(\d+\.?\d*|\d*\.\d+)(ms|s)$/.test(wl)) {
      if (!out['animation-duration'] || out['animation-duration'] === '0s' && !/durationSet/.test('x')) {
        if (out.__dur) out['animation-delay'] = w;
        else { out['animation-duration'] = w; out.__dur = 1; }
      }
      continue;
    }
    if (/^cubic-bezier\(/.test(wl) || wl in ANIM_KEYWORDS) {
      const kind = ANIM_KEYWORDS[wl];
      if (kind === 'timing') out['animation-timing-function'] = w;
      else if (kind === 'iteration') out['animation-iteration-count'] = w;
      else if (kind === 'direction') out['animation-direction'] = w;
      else if (kind === 'fill') out['animation-fill-mode'] = w;
      else if (kind === 'play') out['animation-play-state'] = w;
      else if (kind === 'fill-or-name' && name === null) name = w; // 'none' as name
      continue;
    }
    if (name === null) name = w;
  }
  out['animation-name'] = name ?? 'none';
  delete out.__dur;
  return out;
}

const SHORTHANDS = {
  margin: (v) => boxSides(v, 'margin'),
  padding: (v) => boxSides(v, 'padding'),
  'margin-top': (v) => ({ 'margin-top': v }),
  'border': (v) => expandBorderShorthand(null, v),
  'border-top': (v) => expandBorderShorthand('top', v),
  'border-right': (v) => expandBorderShorthand('right', v),
  'border-bottom': (v) => expandBorderShorthand('bottom', v),
  'border-left': (v) => expandBorderShorthand('left', v),
  'border-width': (v) => {
    const p = topWords(v);
    if (p.length > 4) return null;
    const [t, r = t, b = t, l = r] = p;
    return { 'border-top-width': t, 'border-right-width': r, 'border-bottom-width': b, 'border-left-width': l };
  },
  'border-style': (v) => {
    const p = topWords(v);
    if (p.length > 4) return null;
    const [t, r = t, b = t, l = r] = p;
    return { 'border-top-style': t, 'border-right-style': r, 'border-bottom-style': b, 'border-left-style': l };
  },
  'border-color': (v) => {
    const p = topWords(v);
    if (p.length > 4) return null;
    const [t, r = t, b = t, l = r] = p;
    return { 'border-top-color': t, 'border-right-color': r, 'border-bottom-color': b, 'border-left-color': l };
  },
  'outline': (v) => {
    const t = borderTokens(v);
    if (!t) return null;
    const map = {};
    if (t.width) map['outline-width'] = t.width; else map['outline-width'] = 'medium';
    if (t.style) map['outline-style'] = t.style; else map['outline-style'] = 'none';
    if (t.color) map['outline-color'] = t.color; else map['outline-color'] = 'auto';
    return map;
  },
  'list-style': (v) => {
    const words = topWords(v);
    const TYPES = new Set(['disc', 'circle', 'square', 'decimal', 'none', 'armenian', 'georgian', 'lower-roman', 'upper-roman', 'lower-alpha', 'upper-alpha', 'cjk-ideographic', 'ethiopic-numeric', 'hebrew', 'japanese-indic', 'kannada', 'khmer', 'korean-hangul', 'lao', 'oriya', 'persian', 'simplified-chinese', 'tamil', 'thai', 'trad-chinese', 'ukrainian', 'ethiopic-haleh-ama']);
    const POS = new Set(['inside', 'outside']);
    let type = null, pos = null, img = null;
    for (const w of words) {
      const wl = w.toLowerCase();
      if (TYPES.has(wl)) type = w;
      else if (POS.has(wl)) pos = w;
      else if (wl.startsWith('url(') || isGradient(wl)) img = w;
      else if (wl === 'none') type = 'none';
    }
    return {
      'list-style-type': type ?? INITIAL.listStyleType,
      'list-style-image': img ?? INITIAL.listStyleImage,
      'list-style-position': pos ?? INITIAL.listStylePosition,
    };
  },
  'text-decoration': (v) => {
    const words = topWords(v);
    const LINES = new Set(['underline', 'overline', 'line-through', 'none', 'blink']);
    const STYLES = new Set(['solid', 'double', 'dotted', 'dashed', 'wavy']);
    let line = null, style = null, color = null;
    for (const w of words) {
      const wl = w.toLowerCase();
      if (LINES.has(wl)) line = w;
      else if (STYLES.has(wl)) style = w;
      else color = w;
    }
    return {
      'text-decoration-line': line ?? INITIAL.textDecorationLine,
      'text-decoration-style': style ?? INITIAL.textDecorationStyle,
      'text-decoration-color': color ?? INITIAL.textDecorationColor,
    };
  },
  'transition': expandTransition,
  'overflow': (v) => {
    const p = topWords(v);
    if (p.length === 1) return { 'overflow-x': p[0], 'overflow-y': p[0] };
    if (p.length === 2) return { 'overflow-x': p[0], 'overflow-y': p[1] };
    return null;
  },
  'inset': (v) => {
    const p = topWords(v);
    if (p.length > 4) return null;
    const [t, r = t, b = t, l = r] = p;
    return { top: t, right: r, bottom: b, left: l };
  },
  'place-items': (v) => {
    const p = topWords(v);
    return { 'align-items': p[0], 'justify-items': p[1] ?? p[0] };
  },
  'place-content': (v) => {
    const p = topWords(v);
    return { 'align-content': p[0], 'justify-content': p[1] ?? p[0] };
  },
  'font': expandFont,
  'animation': expandAnimation,
  'background': expandBackground,
};

/**
 * Expand a single declaration into a map of longhand -> raw value.
 * Non-shorthand or custom properties pass through as-is.
 * Returns null when expansion is impossible (caller records raw).
 */
export function expandDecl(prop, value) {
  const p = prop.trim();
  if (p.startsWith('--')) return { [p]: value };
  const fn = SHORTHANDS[p.toLowerCase()];
  if (!fn) return { [p]: value };
  const map = fn(value.trim());
  return map; // may be null → raw
}
