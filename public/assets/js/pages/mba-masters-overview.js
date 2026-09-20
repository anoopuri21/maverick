/**
 * The Learning Blueprint — Overview section.
 * Polar card layout + resize-safe SVG connectors from core to each node.
 */
(function () {
  "use strict";

  var system = document.querySelector("[data-overview-blueprint]");
  if (!system) return;

  var foundations = system.querySelectorAll("[data-overview-foundation]");
  var spokes = system.querySelector(".blueprint-overview__diagram--spokes");
  var connectors = system.querySelector("[data-overview-connectors]");
  var orbitRing = system.querySelector("[data-overview-orbit-ring]");
  var core = system.querySelector("[data-overview-core]");
  var frame = system.closest("[data-overview-frame]") || system.parentElement;
  var reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  var mobileQuery = window.matchMedia("(max-width: 999px)");
  var supportsTrig =
    typeof CSS !== "undefined" &&
    typeof CSS.supports === "function" &&
    CSS.supports("left", "calc(1px * cos(0deg))");
  var GAP = 8;
  var drawRaf = 0;

  function intersects(a, b, gap) {
    return !(
      a.right + gap <= b.left ||
      a.left - gap >= b.right ||
      a.bottom + gap <= b.top ||
      a.top - gap >= b.bottom
    );
  }

  /* True when the polar layout would overlap or overflow; measured with the orbit layout applied. */
  function orbitNeedsFallback() {
    if (!supportsTrig) return true;

    var sysRect = system.getBoundingClientRect();
    var frameRect = frame ? frame.getBoundingClientRect() : sysRect;
    var coreRect = core.getBoundingClientRect();
    var cards = [];

    foundations.forEach(function (foundation) {
      var copy = foundation.querySelector(".blueprint-overview__foundation-copy");
      if (copy) cards.push(copy.getBoundingClientRect());
    });

    for (var i = 0; i < cards.length; i += 1) {
      var card = cards[i];
      if (card.left < frameRect.left - 1 || card.right > frameRect.right + 1) return true;
      if (card.top < sysRect.top - 1 || card.bottom > sysRect.bottom + 1) return true;
      if (intersects(card, coreRect, GAP)) return true;
      for (var j = i + 1; j < cards.length; j += 1) {
        if (intersects(card, cards[j], GAP)) return true;
      }
    }

    return false;
  }

  function revealAll() {
    system.classList.add("is-inview");
    foundations.forEach(function (foundation) {
      foundation.classList.add("is-inview");
    });
  }

  function clearConnectors() {
    if (connectors) connectors.innerHTML = "";
  }

  function syncOrbitRing(cx, cy, radius) {
    if (!orbitRing) return;
    orbitRing.setAttribute("cx", cx.toFixed(1));
    orbitRing.setAttribute("cy", cy.toFixed(1));
    orbitRing.setAttribute("r", Math.max(0, radius).toFixed(1));
  }

  function drawConnectors() {
    if (!spokes || !connectors || !core) return;

    if (mobileQuery.matches) {
      system.classList.remove("is-compact");
      clearConnectors();
      return;
    }

    system.classList.remove("is-compact");
    if (orbitNeedsFallback()) {
      system.classList.add("is-compact");
      clearConnectors();
      return;
    }

    var sysRect = system.getBoundingClientRect();
    var width = Math.max(1, Math.round(sysRect.width));
    var height = Math.max(1, Math.round(sysRect.height));
    spokes.setAttribute("viewBox", "0 0 " + width + " " + height);
    spokes.setAttribute("width", String(width));
    spokes.setAttribute("height", String(height));

    var coreRect = core.getBoundingClientRect();
    var cx = coreRect.left + coreRect.width / 2 - sysRect.left;
    var cy = coreRect.top + coreRect.height / 2 - sysRect.top;
    var coreR = Math.min(coreRect.width, coreRect.height) / 2;

    clearConnectors();
    var ns = "http://www.w3.org/2000/svg";
    var orbitSum = 0;
    var orbitCount = 0;

    foundations.forEach(function (foundation) {
      var node = foundation.querySelector(".blueprint-overview__foundation-node");
      if (!node) return;

      var nodeRect = node.getBoundingClientRect();
      var nx = nodeRect.left + nodeRect.width / 2 - sysRect.left;
      var ny = nodeRect.top + nodeRect.height / 2 - sysRect.top;
      var dx = nx - cx;
      var dy = ny - cy;
      var len = Math.sqrt(dx * dx + dy * dy) || 1;
      orbitSum += len;
      orbitCount += 1;
      var sx = cx + (dx / len) * coreR;
      var sy = cy + (dy / len) * coreR;

      var path = document.createElementNS(ns, "path");
      path.setAttribute(
        "d",
        "M" + sx.toFixed(1) + " " + sy.toFixed(1) + " L" + nx.toFixed(1) + " " + ny.toFixed(1)
      );
      if (reduced || system.classList.contains("is-inview")) {
        path.style.strokeDashoffset = "0";
      }
      connectors.appendChild(path);
    });

    if (orbitCount) {
      syncOrbitRing(cx, cy, orbitSum / orbitCount);
    }
  }

  function scheduleDraw() {
    if (drawRaf) cancelAnimationFrame(drawRaf);
    drawRaf = requestAnimationFrame(function () {
      drawRaf = 0;
      drawConnectors();
    });
  }

  if (reduced || !("IntersectionObserver" in window)) {
    revealAll();
  } else {
    var observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) return;
          revealAll();
          scheduleDraw();
          observer.disconnect();
        });
      },
      { threshold: 0.18, rootMargin: "0px 0px -8% 0px" }
    );
    observer.observe(system);
  }

  scheduleDraw();
  window.addEventListener("resize", scheduleDraw, { passive: true });
  if (typeof mobileQuery.addEventListener === "function") {
    mobileQuery.addEventListener("change", scheduleDraw);
  } else if (typeof mobileQuery.addListener === "function") {
    mobileQuery.addListener(scheduleDraw);
  }

  if (typeof ResizeObserver === "function") {
    var ro = new ResizeObserver(scheduleDraw);
    ro.observe(system);
    foundations.forEach(function (foundation) {
      var copy = foundation.querySelector(".blueprint-overview__foundation-copy");
      if (copy) ro.observe(copy);
    });
  }

  if (document.fonts && document.fonts.ready) {
    document.fonts.ready.then(scheduleDraw).catch(function () {});
  }
  window.addEventListener("load", scheduleDraw, { once: true });
})();
