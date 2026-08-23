(function () {
  const NODES = [
    { id: '840', code: 'US', name: 'UNITED STATES', lon: -97.2, lat: 39.5, dx: 15, dy: -14, a: 'start' },
    { id: '152', code: 'CL', name: 'CHILE', lon: -70.9, lat: -30.0, dx: 15, dy: 10, a: 'start' },
    { id: '604', code: 'PE', name: 'PERU', lon: -75.3, lat: -9.6, dx: -16, dy: -4, a: 'end' },
    { id: '826', code: 'UK', name: 'UNITED KINGDOM', lon: -2.8, lat: 54.3, dx: -18, dy: -26, a: 'end' },
    { id: '756', code: 'CH', name: 'SWITZERLAND', lon: 8.3, lat: 46.9, dx: 16, dy: 26, a: 'start' },
    { id: '348', code: 'HU', name: 'HUNGARY', lon: 19.3, lat: 47.3, dx: -18, dy: -24, a: 'end' },
    { id: '498', code: 'MD', name: 'MOLDOVA', lon: 28.7, lat: 47.2, dx: 13, dy: -22, a: 'start' },
    { id: '642', code: 'RO', name: 'ROMANIA', lon: 24.9, lat: 45.9, dx: 25, dy: 20, a: 'start' },
    { id: '818', code: 'EG', name: 'EGYPT', lon: 30.2, lat: 26.4, dx: -11, dy: -22, a: 'end' },
    { id: '760', code: 'SY', name: 'SYRIA', lon: 38.2, lat: 34.9, dx: 20, dy: -16, a: 'start' },
    { id: '682', code: 'SA', name: 'SAUDI ARABIA', lon: 45.2, lat: 23.9, dx: -16, dy: 16, a: 'end' },
    { id: '784', code: 'AE', name: 'UAE', lon: 54.3, lat: 23.9, dx: 17, dy: -14, a: 'start' },
    { id: '887', code: 'YE', name: 'YEMEN', lon: 47.7, lat: 15.3, dx: 20, dy: 18, a: 'start' },
    { id: '566', code: 'NG', name: 'NIGERIA', lon: 8.3, lat: 9.6, dx: -6, dy: -24, a: 'end' },
    { id: '288', code: 'GH', name: 'GHANA', lon: -1.3, lat: 7.7, dx: -2, dy: 26, a: 'middle' },
    { id: '178', code: 'CG', name: 'CONGO', lon: 15.4, lat: -0.6, dx: -18, dy: 8, a: 'end' },
    { id: '800', code: 'UG', name: 'UGANDA', lon: 32.3, lat: 1.4, dx: 15, dy: 12, a: 'start' },
    { id: '716', code: 'ZW', name: 'ZIMBABWE', lon: 29.8, lat: -19.0, dx: 13, dy: 22, a: 'start' },
    { id: '356', code: 'IN', name: 'INDIA', lon: 78.9, lat: 22.2, dx: -16, dy: -6, a: 'end' },
    { id: '144', code: 'LK', name: 'SRI LANKA', lon: 80.8, lat: 7.6, dx: 15, dy: 11, a: 'start' },
    { id: '462', code: 'MV', name: 'MALDIVES', lon: 73.2, lat: 3.1, dx: -14, dy: 18, a: 'end' },
    { id: '104', code: 'MM', name: 'MYANMAR', lon: 96.2, lat: 21.3, dx: -15, dy: -10, a: 'end' },
    { id: '156', code: 'CN', name: 'CHINA', lon: 104.5, lat: 35.8, dx: 17, dy: -12, a: 'start' },
    { id: '458', code: 'MY', name: 'MALAYSIA', lon: 102.2, lat: 3.9, dx: 19, dy: 7, a: 'start' },
    { id: '704', code: 'VN', name: 'VIETNAM', lon: 108.2, lat: 14.1, dx: 15, dy: -15, a: 'start' },
    { id: '344', code: 'HK', name: 'HONG KONG', lon: 114.17, lat: 22.32, dx: 19, dy: -8, a: 'start' },
    { id: '036', code: 'AU', name: 'AUSTRALIA', lon: 134.2, lat: -25.4, dx: 16, dy: 15, a: 'start' },
  ];
  const SELECTED = new Set(NODES.map((n) => n.id));

  function hideLoader() {
    const loader = document.getElementById('gap-loader');
    if (!loader) return;
    loader.style.opacity = '0';
    setTimeout(() => loader.remove(), 600);
  }

  function waitForDeps(retries) {
    if (window.d3 && window.topojson) {
      build();
      return;
    }
    if (retries <= 0) {
      hideLoader();
      return;
    }
    setTimeout(() => waitForDeps(retries - 1), 50);
  }

  async function loadWorld(d3) {
    const urls = [
      'https://cdn.jsdelivr.net/npm/world-atlas@2/countries-50m.json',
      'https://cdn.jsdelivr.net/npm/world-atlas@2/countries-110m.json',
    ];
    for (const u of urls) {
      try {
        const t = await d3.json(u);
        if (t && t.objects && t.objects.countries) return t;
      } catch (e) {
        /* try next */
      }
    }
    return null;
  }

  async function build() {
    const d3 = window.d3;
    const topojson = window.topojson;
    const mapEl = document.getElementById('gap-map');
    if (!mapEl || !d3 || !topojson) {
      hideLoader();
      return;
    }

    const W = 1280;
    const H = 680;
    const svg = d3.select('#gap-map');
    svg.append('rect').attr('class', 'panel').attr('width', W).attr('height', H).attr('rx', 14);
    svg.append('clipPath').attr('id', 'gapPanelClip')
      .append('rect').attr('width', W).attr('height', H).attr('rx', 14);
    const root = svg.append('g').attr('clip-path', 'url(#gapPanelClip)');

    const projection = d3.geoNaturalEarth1();
    const geoPath = d3.geoPath(projection);

    const topo = await loadWorld(d3);
    if (!topo) {
      hideLoader();
      return;
    }

    const fid = (f) => String(f.id).padStart(3, '0');
    let features = topojson.feature(topo, topo.objects.countries).features;
    features = features.filter((f) => fid(f) !== '010');

    const landFC = { type: 'FeatureCollection', features };
    const hotFts = features.filter((f) => SELECTED.has(fid(f)));
    const hotFC = { type: 'FeatureCollection', features: hotFts };

    projection.fitExtent([[9, 9], [W - 9, H - 8]], landFC);

    root.append('path')
      .datum({ type: 'Sphere' })
      .attr('class', 'land')
      .style('fill', 'rgba(255,255,255,.02)')
      .style('stroke', 'rgba(255,255,255,.10)')
      .attr('d', geoPath);

    root.append('path')
      .datum(d3.geoGraticule().step([20, 20])())
      .attr('class', 'grat')
      .attr('d', geoPath);

    const equator = { type: 'LineString', coordinates: d3.range(-180, 181, 5).map((l) => [l, 0]) };
    const gwmch = { type: 'LineString', coordinates: d3.range(-80, 81, 5).map((p) => [0, p]) };
    root.selectAll(null).data([equator, gwmch]).join('path')
      .attr('class', 'meri').attr('d', geoPath);

    root.append('path')
      .datum(landFC)
      .attr('class', 'land')
      .attr('d', geoPath)
      .style('fill', 'rgba(255,255,255,.05)');

    if (hotFts.length) {
      root.append('path').datum(hotFC).attr('class', 'glow').attr('d', geoPath);
    }

    let pinned = null;
    function focus(code) {
      svg.selectAll('.on').classed('on', false);
      if (code) {
        svg.selectAll('[data-code]')
          .filter(function () { return d3.select(this).attr('data-code') === code; })
          .classed('on', true);
      }
    }

    root.selectAll('path.hot')
      .data(hotFts).join('path')
      .attr('class', 'hot')
      .attr('d', geoPath)
      .attr('data-code', (d) => (NODES.find((n) => n.id === fid(d)) || {}).code)
      .on('mouseenter', (ev, d) => focus((NODES.find((n) => n.id === fid(d)) || {}).code))
      .on('mouseleave', () => focus(pinned))
      .on('click', (ev, d) => {
        ev.stopPropagation();
        const c = (NODES.find((n) => n.id === fid(d)) || {}).code;
        pinned = pinned === c ? null : c;
        focus(pinned);
      });

    const pins = root.append('g').attr('class', 'pins')
      .selectAll('g.pin')
      .data(NODES).join('g')
      .attr('class', 'pin')
      .attr('data-code', (d) => d.code)
      .style('--d', (d, i) => 0.55 + i * 0.055 + 's')
      .on('mouseenter', (ev, d) => focus(d.code))
      .on('mouseleave', () => focus(pinned))
      .on('click', (ev, d) => {
        ev.stopPropagation();
        pinned = pinned === d.code ? null : d.code;
        focus(pinned);
      });

    const LBS = [];
    pins.each(function (d, i) {
      const g = d3.select(this);
      const p = projection([d.lon, d.lat]);
      if (!p) return;
      const [x, y] = p;
      g.attr('transform', `translate(${x},${y})`);

      g.append('circle').attr('class', 'halo').attr('r', 5);
      g.append('circle').attr('class', 'ring').attr('r', 5)
        .style('animation-delay', -(i % 7) * 0.42 + 's');
      g.append('circle').attr('class', 'ring').attr('r', 5)
        .style('animation-delay', -((i % 7) * 0.42) - 1.6 + 's');
      g.append('circle').attr('class', 'dot').attr('r', 2.4);

      const line = g.append('line').attr('class', 'lead');
      const sq = g.append('rect').attr('class', 'sq').attr('width', 3).attr('height', 3);
      const chip = g.append('rect').attr('class', 'chip').attr('rx', 5);
      const t = g.append('text').attr('class', 'lbl').text(d.name)
        .attr('text-anchor', d.a === 'end' ? 'end' : (d.a === 'middle' ? 'middle' : 'start'));

      LBS.push({ d, a: d.a, x, y, px: d.dx, py: d.dy, ox: d.dx, oy: d.dy, w: 0, el: t, line, sq, chip });
    });

    try {
      if (document.fonts && document.fonts.ready) await document.fonts.ready;
    } catch (e) { /* ignore */ }

    LBS.forEach((l) => {
      l.w = (l.el.node().getComputedTextLength && l.el.node().getComputedTextLength()) || l.d.name.length * 6.2;
    });
    const pinPts = LBS.map((l) => [l.x, l.y]);

    function boxOf(l) {
      if (l.a === 'start') return { x1: l.x + l.px + 8, x2: l.x + l.px + 8 + l.w, y1: l.y + l.py - 6, y2: l.y + l.py + 6 };
      if (l.a === 'end') return { x1: l.x + l.px - 8 - l.w, x2: l.x + l.px - 8, y1: l.y + l.py - 6, y2: l.y + l.py + 6 };
      const cy = l.y + l.py + (l.py >= 0 ? 11 : -11);
      return { x1: l.x + l.px - l.w / 2, x2: l.x + l.px + l.w / 2, y1: cy - 6, y2: cy + 6 };
    }

    function moveL(l, dx, dy) {
      l.px += dx;
      l.py += dy;
      let ddx = l.px - l.ox;
      let ddy = l.py - l.oy;
      const m = Math.hypot(ddx, ddy);
      const MAX = 36;
      if (m > MAX) {
        l.px = l.ox + (ddx / m) * MAX;
        l.py = l.oy + (ddy / m) * MAX;
      }
      const b = boxOf(l);
      if (b.x1 < 6) l.px += 6 - b.x1;
      if (b.x2 > W - 6) l.px -= b.x2 - (W - 6);
      if (b.y1 < 8) l.py += 8 - b.y1;
      if (b.y2 > H - 4) l.py -= b.y2 - (H - 4);
    }

    const PAD = 5;
    for (let it = 0; it < 400; it++) {
      let moved = false;
      for (let i = 0; i < LBS.length; i++) {
        for (let j = i + 1; j < LBS.length; j++) {
          const bi = boxOf(LBS[i]);
          const bj = boxOf(LBS[j]);
          const ox = Math.min(bi.x2, bj.x2) - Math.max(bi.x1, bj.x1) + PAD;
          const oy = Math.min(bi.y2, bj.y2) - Math.max(bi.y1, bj.y1) + PAD;
          if (ox > 0 && oy > 0) {
            moved = true;
            if (oy <= ox) {
              const s = (bj.y1 + bj.y2) >= (bi.y1 + bi.y2) ? 1 : -1;
              moveL(LBS[i], 0, -s * oy / 2);
              moveL(LBS[j], 0, s * oy / 2);
            } else {
              const s = (bj.x1 + bj.x2) >= (bi.x1 + bi.x2) ? 1 : -1;
              moveL(LBS[i], -s * ox / 2, 0);
              moveL(LBS[j], s * ox / 2, 0);
            }
          }
        }
        for (let k = 0; k < pinPts.length; k++) {
          if (k === i) continue;
          const [qx, qy] = pinPts[k];
          const b = boxOf(LBS[i]);
          if (qx > b.x1 - 7 && qx < b.x2 + 7 && qy > b.y1 - 7 && qy < b.y2 + 7) {
            const s = (b.y1 + b.y2) / 2 - qy >= 0 ? 1 : -1;
            moveL(LBS[i], 0, s * 2.5);
            moved = true;
          }
        }
      }
      if (!moved) break;
    }

    LBS.forEach((l) => {
      const px = l.px;
      const py = l.py;
      if (Math.hypot(px, py) > 10) {
        l.line.attr('x1', px * 0.24).attr('y1', py * 0.24)
          .attr('x2', px * 0.86).attr('y2', py * 0.86);
      } else {
        l.line.attr('opacity', 0);
      }
      l.sq.attr('x', px - 1.5).attr('y', py - 1.5);
      if (l.a === 'start') {
        l.el.attr('x', px + 8).attr('y', py).attr('dominant-baseline', 'central');
      } else if (l.a === 'end') {
        l.el.attr('x', px - 8).attr('y', py).attr('dominant-baseline', 'central');
      } else {
        l.el.attr('x', px).attr('y', py + (py >= 0 ? 11 : -11));
      }

      const b = boxOf(l);
      l.chip.attr('x', b.x1 - l.x - 7.5)
        .attr('y', b.y1 - l.y - 5)
        .attr('width', (b.x2 - b.x1) + 15)
        .attr('height', (b.y2 - b.y1) + 10);
    });

    const zoom = d3.zoom()
      .scaleExtent([1, 9])
      .filter((e) => (e.type === 'mousedown' && e.button === 0) || e.type === 'touchstart')
      .translateExtent([[-W * 0.4, -H * 0.4], [W * 1.4, H * 1.4]])
      .on('start', () => svg.classed('grabbing', true))
      .on('end', () => svg.classed('grabbing', false))
      .on('zoom', (e) => root.attr('transform', e.transform));
    svg.call(zoom).on('dblclick.zoom', null).on('wheel.zoom', null);

    let downPos = null;
    svg.on('pointerdown', (e) => { downPos = [e.clientX, e.clientY]; });
    svg.on('click', (e) => {
      if (!downPos) return;
      if (Math.hypot(e.clientX - downPos[0], e.clientY - downPos[1]) < 6) {
        pinned = null;
        focus(null);
      }
    });

    const zi = () => svg.transition().duration(350).call(zoom.scaleBy, 1.7);
    const zo = () => svg.transition().duration(350).call(zoom.scaleBy, 1 / 1.7);
    const zr = () => svg.transition().duration(650).ease(d3.easeCubicInOut).call(zoom.transform, d3.zoomIdentity);
    const zin = document.getElementById('gap-zin');
    const zout = document.getElementById('gap-zout');
    const zreset = document.getElementById('gap-zreset');
    if (zin) zin.addEventListener('click', zi);
    if (zout) zout.addEventListener('click', zo);
    if (zreset) zreset.addEventListener('click', zr);

    requestAnimationFrame(() => {
      hideLoader();
      svg.classed('ready', true);
      setTimeout(() => svg.classed('t-settled', true), 2800);
    });

    if (window.lucide) window.lucide.createIcons();
  }

  function boot() {
    if (!document.getElementById('gap-map')) return;
    waitForDeps(80);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})();
