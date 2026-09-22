/**
 * MBA Masters landing — Phase 0 motion primitives
 * Design system: docs/mlp-design-system.md
 * Mid-page refresh fix: docs/mlp-scroll-reveal-fix.md
 */
(function () {
  var MLP = window.MLPMotion || {};
  var rescueQueue = [];

  function prefersReduced() {
    return window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  }

  function ensureScrollTrigger() {
    if (typeof gsap === "undefined") return false;
    if (typeof ScrollTrigger !== "undefined") {
      gsap.registerPlugin(ScrollTrigger);
      return true;
    }
    return typeof gsap !== "undefined";
  }

  /** Parse ST start like "top 80%" → 0.8 (viewport ratio). */
  function startViewportRatio(start) {
    var m = String(start || "top 80%").match(/(\d+(?:\.\d+)?)%/);
    return m ? parseFloat(m[1]) / 100 : 0.8;
  }

  /**
   * True when trigger has already crossed its start line (in view or above).
   * Used so mid-page refresh does not leave upper sections at opacity:0.
   */
  MLP.isPastStart = function (trigger, start) {
    if (!trigger || !trigger.getBoundingClientRect) return false;
    var vh = window.innerHeight || document.documentElement.clientHeight || 1;
    return trigger.getBoundingClientRect().top <= vh * startViewportRatio(start);
  };

  /**
   * Fade + slide children (or node) once into view.
   * Supports y and/or x offset. If already past start on init → show immediately.
   * @param {Element|string} target
   * @param {{x?: number, y?: number, duration?: number, stagger?: number, delay?: number, trigger?: Element, children?: boolean, scroll?: boolean, start?: string}} opts
   */
  MLP.reveal = function (target, opts) {
    opts = opts || {};
    var el = typeof target === "string" ? document.querySelector(target) : target;
    if (!el) return;

    if (prefersReduced() || typeof gsap === "undefined") {
      return;
    }

    ensureScrollTrigger();
    var nodes = opts.children === false ? el : el.children;
    if (!nodes || !nodes.length) nodes = [el];

    var hasX = opts.x != null;
    var x = hasX ? opts.x : 0;
    var y = opts.y != null ? opts.y : hasX ? 0 : 32;
    var trigger = opts.trigger || el;
    var start = opts.start || "top 80%";
    var done = false;
    var from = { opacity: 0, x: x, y: y };
    var toVisible = { opacity: 1, x: 0, y: 0 };

    function show(immediate) {
      if (done) return;
      done = true;
      if (immediate) {
        gsap.set(nodes, toVisible);
        return;
      }
      gsap.fromTo(
        nodes,
        from,
        {
          opacity: 1,
          x: 0,
          y: 0,
          duration: opts.duration != null ? opts.duration : 0.85,
          stagger: opts.stagger != null ? opts.stagger : 0.1,
          ease: "power3.out",
          delay: opts.delay || 0,
          overwrite: "auto",
        }
      );
    }

    if (!opts.scroll || typeof ScrollTrigger === "undefined") {
      gsap.set(nodes, from);
      show(false);
      return;
    }

    if (MLP.isPastStart(trigger, start)) {
      done = true;
      return;
    }

    ScrollTrigger.create({
      trigger: trigger,
      start: start,
      once: true,
      onEnter: function () {
        show(false);
      },
      onEnterBack: function () {
        show(true);
      },
    });

    rescueQueue.push({
      trigger: trigger,
      start: start,
      rescue: function () {
        show(true);
      },
    });
  };

  /** Alternating L/R slide for sections after hero + trust (document order). */
  var MLP_SLIDE_SECTIONS = [
    "mlp-overview",
    "mlp-why",
    "mlp-journey",
    "mlp-mba",
    "mlp-masters",
    "mlp-fees",
    "mlp-class",
    "mlp-career",
    "mlp-alumni",
    "mlp-learning",
    "mlp-partners",
    "mlp-testimonials",
    "mlp-compare",
    "mlp-faq",
    "mlp-final",
  ];
  var MLP_SLIDE_X = {};
  MLP_SLIDE_SECTIONS.forEach(function (id, i) {
    // Odd after trust (1-based): from right (+x); even: from left (−x)
    MLP_SLIDE_X[id] = i % 2 === 0 ? 56 : -56;
  });

  MLP.slideReveal = function (target, sectionEl, opts) {
    opts = opts || {};
    var id = sectionEl && sectionEl.id;
    var x = MLP_SLIDE_X[id] != null ? MLP_SLIDE_X[id] : 0;
    return MLP.reveal(
      target,
      Object.assign(
        {
          scroll: true,
          trigger: sectionEl,
          x: x,
          y: 0,
          start: "top 90%",
        },
        opts
      )
    );
  };

  /**
   * Arm a scroll callback. If already past start, runs immediately with instant=true.
   * @param {Element} trigger
   * @param {string} start
   * @param {(instant: boolean) => void} onEnter
   */
  MLP.whenInView = function (trigger, start, onEnter) {
    if (!trigger || typeof onEnter !== "function") return;
    start = start || "top 80%";
    var done = false;

    function fire(instant) {
      if (done) return;
      done = true;
      onEnter(instant);
    }

    if (prefersReduced() || typeof gsap === "undefined") {
      fire(true);
      return;
    }

    ensureScrollTrigger();
    if (typeof ScrollTrigger === "undefined") {
      fire(true);
      return;
    }

    if (MLP.isPastStart(trigger, start)) {
      fire(true);
      return;
    }

    ScrollTrigger.create({
      trigger: trigger,
      start: start,
      once: true,
      onEnter: function () {
        fire(false);
      },
      onEnterBack: function () {
        fire(true);
      },
    });

    rescueQueue.push({
      trigger: trigger,
      start: start,
      rescue: function () {
        fire(true);
      },
    });
  };

  /**
   * IO-gate section init — content stays CSS-visible until armed.
   * @param {string|Element} selector
   * @param {(el: Element, instant: boolean) => void} initFn
   * @param {{immediate?: boolean, rootMargin?: string, start?: string}} opts
   */
  MLP.observeSection = function (selector, initFn, opts) {
    opts = opts || {};
    var el = typeof selector === "string" ? document.querySelector(selector) : selector;
    if (!el || typeof initFn !== "function") return;

    var rootMargin = opts.rootMargin != null ? opts.rootMargin : "280px 0px 280px 0px";
    var start = opts.start || "top 95%";

    function run(instant) {
      if (el.dataset.mlpSectionInit === "1") return;
      el.dataset.mlpSectionInit = "1";
      initFn(el, instant);
      requestAnimationFrame(function () {
        MLP.rescuePastReveals();
      });
    }

    if (opts.immediate || prefersReduced() || typeof gsap === "undefined") {
      run(true);
      return;
    }

    if (MLP.isPastStart(el, start)) {
      run(true);
      return;
    }

    if (!("IntersectionObserver" in window)) {
      run(false);
      return;
    }

    var io = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            io.disconnect();
            run(MLP.isPastStart(el, start));
          }
        });
      },
      { rootMargin: rootMargin, threshold: 0 }
    );
    io.observe(el);
  };

  /** Re-measure triggers after browser scroll restoration. */
  MLP.refreshTriggers = function () {
    if (typeof ScrollTrigger !== "undefined") {
      ScrollTrigger.refresh();
    }
  };

  /**
   * After scroll restoration: force-show any armed reveal that is already past start
   * but never received onEnter (mid-page soft/hard refresh bug).
   */
  MLP.rescuePastReveals = function () {
    rescueQueue.forEach(function (item) {
      if (item && item.trigger && MLP.isPastStart(item.trigger, item.start) && typeof item.rescue === "function") {
        item.rescue();
      }
    });
    MLP.refreshTriggers();
  };

  /**
   * Count-up for [data-mlp-count] elements.
   */
  MLP.count = function (root, opts) {
    opts = opts || {};
    if (prefersReduced() || typeof gsap === "undefined") return;

    ensureScrollTrigger();
    var scope = root || document;
    var values = scope.querySelectorAll("[data-mlp-count]");

    values.forEach(function (node) {
      var raw = parseFloat(node.getAttribute("data-mlp-count") || "0");
      if (!raw) return;
      var suffix = node.getAttribute("data-mlp-suffix") || "";
      var obj = { n: 0 };
      var run = function () {
        gsap.to(obj, {
          n: raw,
          duration: opts.duration != null ? opts.duration : 1.35,
          ease: "power2.out",
          onUpdate: function () {
            var decimals = String(raw).indexOf(".") >= 0 ? 1 : 0;
            node.textContent = obj.n.toFixed(decimals) + suffix;
          },
        });
      };
      MLP.whenInView(node, "top 90%", function (instant) {
        if (instant) {
          var decimals = String(raw).indexOf(".") >= 0 ? 1 : 0;
          node.textContent = raw.toFixed(decimals) + suffix;
          return;
        }
        run();
      });
    });
  };

  /**
   * Subtle parallax on background media.
   */
  MLP.parallax = function (target, opts) {
    opts = opts || {};
    var el = typeof target === "string" ? document.querySelector(target) : target;
    if (!el || prefersReduced() || typeof gsap === "undefined") return;
    if (typeof ScrollTrigger === "undefined") return;

    ensureScrollTrigger();
    gsap.to(el, {
      yPercent: opts.yPercent != null ? opts.yPercent : 12,
      ease: "none",
      scrollTrigger: {
        trigger: opts.trigger || el.parentElement || el,
        start: "top top",
        end: "bottom top",
        scrub: true,
      },
    });
  };

  window.MLPMotion = MLP;

  function bindTabs(root, tabAttr, paneAttr, opts) {
    opts = opts || {};
    if (!root || root.dataset.mlpTabsBound === "1") return;
    root.dataset.mlpTabsBound = "1";

    root.addEventListener("click", function (event) {
      var tab = event.target.closest("[" + tabAttr + "]");
      if (!tab || !root.contains(tab)) return;

      var key = tab.getAttribute(tabAttr);
      if (!key) return;
      if (opts.preventDefault) event.preventDefault();

      root.querySelectorAll("[" + tabAttr + "]").forEach(function (other) {
        var on = other === tab;
        other.classList.toggle("is-active", on);
        other.setAttribute("aria-selected", on ? "true" : "false");
      });

      root.querySelectorAll("[" + paneAttr + "]").forEach(function (pane) {
        var on = pane.getAttribute(paneAttr) === key;
        pane.classList.toggle("is-active", on);
        if (opts.hidden) {
          if (on) pane.removeAttribute("hidden");
          else pane.setAttribute("hidden", "hidden");
        }
      });
    });
  }

  MLP.observeInView = function (selector, className) {
    className = className || "is-inview";
    var targets = document.querySelectorAll(selector);
    if (!targets.length) return;

    function reveal(el) {
      el.classList.add(className);
    }

    if (prefersReduced() || !("IntersectionObserver" in window)) {
      targets.forEach(reveal);
      return;
    }

    var observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) return;
          reveal(entry.target);
          observer.unobserve(entry.target);
        });
      },
      { threshold: 0.16, rootMargin: "0px 0px -8% 0px" }
    );

    targets.forEach(function (target) {
      observer.observe(target);
    });
  };

  /** Pause CSS marquees / prose rings when off-screen. */
  function initMotionPauses() {
    if (!("IntersectionObserver" in window)) return;

    document.querySelectorAll("[data-mlp-alumni-marquee]").forEach(function (marquee) {
      if (marquee.dataset.mlpMarqueeInit === "1") return;
      marquee.dataset.mlpMarqueeInit = "1";
      var io = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            if (prefersReduced()) {
              marquee.classList.add("is-paused");
              return;
            }
            marquee.classList.toggle("is-paused", !entry.isIntersecting);
          });
        },
        { threshold: 0 }
      );
      io.observe(marquee);
    });

    document.querySelectorAll(".mlp-page .mlp-prose").forEach(function (prose) {
      if (prose.dataset.mlpProsePauseInit === "1") return;
      prose.dataset.mlpProsePauseInit = "1";
      var io = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            if (prefersReduced()) {
              prose.classList.add("is-offscreen");
              return;
            }
            prose.classList.toggle("is-offscreen", !entry.isIntersecting);
          });
        },
        { threshold: 0, rootMargin: "80px 0px" }
      );
      io.observe(prose);
    });
  }

  function initHeroAssembly() {
    var hero = document.querySelector("[data-hero-assembly]");
    if (!hero) return;

    var reduced = prefersReduced();
    var image = hero.querySelector("[data-hero-image]");
    var wash = hero.querySelector("[data-hero-wash]");
    var registration = hero.querySelector("[data-hero-registration]");
    var masthead = hero.querySelector("[data-hero-masthead]");
    var statement = hero.querySelector("[data-hero-statement]");
    var titleLines = hero.querySelectorAll("[data-hero-title-line]");
    var enquiry = hero.querySelector("[data-hero-enquiry]");
    var folio = hero.querySelector("[data-hero-folio]");

    function showFinalState() {
      hero.classList.add("is-hero-assembled");
      [image, wash, registration, masthead, statement, enquiry, folio].forEach(function (element) {
        if (element) element.removeAttribute("data-hero-pending");
      });
      titleLines.forEach(function (line) {
        line.removeAttribute("data-hero-pending");
      });
    }

    if (reduced || typeof window.gsap === "undefined") {
      showFinalState();
      return;
    }

    var tl = window.gsap.timeline({
      defaults: { ease: "power3.out" },
      onComplete: showFinalState,
    });

    window.gsap.set([image, wash], { opacity: 0 });
    window.gsap.set(image, { scale: 1.08, filter: "saturate(0.35) contrast(1.05) brightness(0.42) blur(4px)" });
    window.gsap.set(registration, { opacity: 0, clipPath: "inset(0 100% 0 0)" });
    window.gsap.set(masthead ? masthead.children : [], { opacity: 0, y: -14 });
    window.gsap.set(statement ? statement.children : [], { opacity: 0, y: 22 });
    window.gsap.set(titleLines, { opacity: 0, yPercent: 115 });
    window.gsap.set(enquiry, { opacity: 0, x: 42, clipPath: "inset(0 0 0 100%)" });
    window.gsap.set(folio ? folio.children : [], { opacity: 0, y: 12 });

    tl.to([image, wash], { opacity: 1, duration: 0.42 }, 0)
      .to(image, { scale: 1.035, filter: "saturate(0.68) contrast(1.08) brightness(0.64) blur(0px)", duration: 1.05 }, 0)
      .to(registration, { opacity: 1, clipPath: "inset(0 0% 0 0)", duration: 0.72 }, 0.16)
      .to(masthead ? masthead.children : [], { opacity: 1, y: 0, duration: 0.55, stagger: 0.06 }, 0.34)
      .to(statement ? statement.children : [], { opacity: 1, y: 0, duration: 0.62, stagger: 0.07 }, 0.58)
      .to(titleLines, { opacity: 1, yPercent: 0, duration: 0.82, stagger: 0.11 }, 0.72)
      .to(enquiry, { opacity: 1, x: 0, clipPath: "inset(0 0% 0 0)", duration: 0.9 }, 1.03)
      .to(folio ? folio.children : [], { opacity: 1, y: 0, duration: 0.5, stagger: 0.06 }, 1.42);
  }

  function initTrust(trust) {
    MLP.count(trust, { duration: 1.5 });
  }

  function initOverviewBlueprint(overview) {
    var system = overview.querySelector("[data-overview-blueprint]") || overview;
    if (!system || !system.hasAttribute("data-overview-blueprint")) return;

    var foundations = system.querySelectorAll("[data-overview-foundation]");
    var spokes = system.querySelector(".blueprint-overview__diagram--spokes");
    var connectors = system.querySelector("[data-overview-connectors]");
    var orbitRing = system.querySelector("[data-overview-orbit-ring]");
    var core = system.querySelector("[data-overview-core]");
    var frame = system.closest("[data-overview-frame]") || system.parentElement;
    var reduced = prefersReduced();
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

    function orbitNeedsFallback() {
      if (!supportsTrig || !core) return true;

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
  }

  function initOverview(overview) {
    initOverviewBlueprint(overview);
  }

  function initWhy(why) {
      var whyHead = why.querySelector('[data-mlp-reveal="why-head"]');
      var whyChapters = why.querySelector('[data-mlp-reveal="why-chapters"]');
      if (whyHead) MLP.slideReveal(whyHead, why, { stagger: 0.08 });
      if (whyChapters) {
        MLP.slideReveal(whyChapters, why, {
          trigger: whyChapters,
          stagger: 0.12,
          duration: 0.85,
        });
      }
  }

  function initJourney(journey) {
      var jHead = journey.querySelector('[data-mlp-reveal="journey-head"]');
      var jList = journey.querySelector('[data-mlp-reveal="journey-steps"]');
      var jSteps = journey.querySelectorAll(".mlp-journey__step");

      if (jHead) MLP.slideReveal(jHead, journey, { stagger: 0.08 });

      if (!jList || !jSteps.length) return;

      if (prefersReduced() || typeof gsap === "undefined") {
        jSteps.forEach(function (step) {
          step.style.opacity = "1";
          step.style.transform = "none";
        });
        return;
      }

      ensureScrollTrigger();
      gsap.set(jSteps, { opacity: 0, y: 56 });
      MLP.whenInView(jList, "top 85%", function (instant) {
        if (instant) {
          gsap.set(jSteps, { opacity: 1, y: 0, clearProps: "willChange" });
          return;
        }
        gsap.fromTo(
          jSteps,
          { opacity: 0, y: 56 },
          {
            opacity: 1,
            y: 0,
            duration: 0.8,
            stagger: 0.12,
            ease: "power3.out",
            clearProps: "willChange",
          }
        );
      });
  }

  function initMba(mbaRoot) {
      var mbaHead = mbaRoot.querySelector('[data-mlp-reveal="mba-head"]');
      if (mbaHead) MLP.slideReveal(mbaHead, mbaRoot, { stagger: 0.08 });

      var mbaChrome = mbaRoot.querySelector("[data-mlp-mba-tabs]");
      if (mbaChrome) {
        MLP.slideReveal(mbaChrome, mbaRoot, { children: false, duration: 0.8 });
        bindTabs(mbaChrome, "data-mlp-mba-tab", "data-mlp-mba-panel", { hidden: true });
      }

      mbaRoot.querySelectorAll("[data-mlp-mba-showcase]").forEach(function (showcase) {
        MLP.slideReveal(showcase, mbaRoot, { children: false, trigger: showcase, duration: 0.85 });
      });
  }

  function initMasters(masters) {
      var mastersHead = masters.querySelector('[data-mlp-reveal="masters-head"]');
      var mastersList = masters.querySelector('[data-mlp-reveal="masters-list"]');
      if (mastersHead) MLP.slideReveal(mastersHead, masters, { stagger: 0.08 });
      if (mastersList) MLP.slideReveal(mastersList, masters, { children: false, duration: 0.7 });
      masters.querySelectorAll("[data-mlp-masters-showcase]").forEach(function (showcase) {
        MLP.slideReveal(showcase, masters, { children: false, trigger: showcase, duration: 0.85 });
      });
  }

  function initFees(fees) {
      var feesBase = fees.querySelector('[data-mlp-reveal="fees-base"]');
      if (feesBase) MLP.slideReveal(feesBase, fees, { children: false });
  }

  function initClass(classSection) {
      var classAnimate = !prefersReduced() && typeof gsap !== "undefined";
      var industryNodes = classSection.querySelectorAll("[data-mlp-class-industry]");

      if (classAnimate) {
        ensureScrollTrigger();

        var clHead = classSection.querySelector('[data-mlp-reveal="class-head"]');
        var clAside = classSection.querySelector('[data-mlp-reveal="class-head-aside"]');
        var clKicker = classSection.querySelector(".mlp-class__kicker");
        var classX = MLP_SLIDE_X["mlp-class"] || -56;
        if (clHead) MLP.slideReveal(clHead, classSection, { stagger: 0.1 });
        if (clAside) MLP.slideReveal(clAside, classSection, { stagger: 0.1, delay: 0.18 });
        if (clKicker && typeof ScrollTrigger !== "undefined") {
          MLP.whenInView(classSection, "top 90%", function (instant) {
            if (instant) {
              gsap.set(clKicker, { scaleX: 1 });
              return;
            }
            gsap.fromTo(
              clKicker,
              { scaleX: 0 },
              { scaleX: 1, duration: 0.7, ease: "power2.out" }
            );
          });
        }

        var dropRule = classSection.querySelector("[data-mlp-class-droprule]");
        if (dropRule && typeof ScrollTrigger !== "undefined") {
          gsap.fromTo(
            dropRule,
            { scaleY: 0 },
            {
              scaleY: 1,
              ease: "none",
              scrollTrigger: { trigger: dropRule, start: "top 92%", end: "top 60%", scrub: 0.6 },
            }
          );
        }

        var metricsBand = classSection.querySelector("[data-mlp-class-metrics]");
        if (metricsBand) {
          MLP.whenInView(metricsBand, "top 90%", function (instant) {
            if (instant) {
              gsap.set(metricsBand, { opacity: 1, x: 0, y: 0 });
              gsap.set(metricsBand.children, { opacity: 1, x: 0, y: 0 });
              return;
            }
            gsap.set(metricsBand, { opacity: 0, x: classX, y: 0 });
            gsap.set(metricsBand.children, { opacity: 0, x: classX * 0.4, y: 0 });
            var bandTl = gsap.timeline();
            bandTl
              .to(metricsBand, { opacity: 1, x: 0, duration: 0.85, ease: "power3.out" })
              .to(
                metricsBand.children,
                { opacity: 1, x: 0, duration: 0.6, stagger: 0.09, ease: "power3.out" },
                "-=0.45"
              );
          });
        }

        MLP.count(classSection, { duration: 1.4 });

        var regionsPanel = classSection.querySelector('[data-mlp-reveal="class-regions"]');
        if (regionsPanel) {
          MLP.slideReveal(regionsPanel, classSection, { children: false, trigger: regionsPanel, duration: 0.8 });
          var regionItems = regionsPanel.querySelectorAll(".mlp-class__region");
          if (regionItems.length) {
            MLP.whenInView(regionsPanel, "top 90%", function (instant) {
              if (instant) {
                gsap.set(regionItems, { opacity: 1, x: 0 });
                return;
              }
              gsap.set(regionItems, { opacity: 0, x: classX * 0.5 });
              gsap.to(regionItems, {
                opacity: 1,
                x: 0,
                duration: 0.6,
                stagger: 0.08,
                ease: "power3.out",
              });
            });
          }
        }

        var indPanel = classSection.querySelector('[data-mlp-reveal="class-industries"]');
        if (indPanel) MLP.slideReveal(indPanel, classSection, { children: false, trigger: indPanel, duration: 0.8 });

        industryNodes.forEach(function (row) {
          var frame = row.querySelector("[data-mlp-class-frame]");
          MLP.whenInView(row, "top 90%", function (instant) {
            if (instant) {
              gsap.set(row, { opacity: 1, x: 0 });
              if (frame) gsap.set(frame, { clipPath: "inset(0 0% 0 0)" });
              row.classList.add("is-inview");
              return;
            }
            var rowTl = gsap.timeline({ defaults: { ease: "power3.out" } });
            rowTl.fromTo(row, { opacity: 0, x: classX }, { opacity: 1, x: 0, duration: 0.55 }, 0);
            if (frame) {
              rowTl.fromTo(
                frame,
                { clipPath: "inset(0 100% 0 0)" },
                { clipPath: "inset(0 0% 0 0)", duration: 0.7 },
                0.12
              );
            }
            rowTl.add(function () {
              row.classList.add("is-inview");
            }, 0.45);
          });
        });
      } else {
        // Reduced motion / no GSAP — content visible, bars fill via IO
        if (industryNodes.length && "IntersectionObserver" in window) {
          var classIo = new IntersectionObserver(
            function (entries) {
              entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                  entry.target.classList.add("is-inview");
                  classIo.unobserve(entry.target);
                }
              });
            },
            { threshold: 0.3, rootMargin: "0px 0px -6% 0px" }
          );
          industryNodes.forEach(function (node) {
            classIo.observe(node);
          });
        } else {
          industryNodes.forEach(function (node) {
            node.classList.add("is-inview");
          });
        }
      }
  }

  function initCareer(career) {
      var careerAnimate = !prefersReduced() && typeof gsap !== "undefined";
      var stories = career.querySelectorAll("[data-mlp-career-story]");
      var careerStage = career.querySelector("[data-mlp-career-stage]");
      var careerSpine = career.querySelector("[data-mlp-career-spine]");

      var careerHead = career.querySelector('[data-mlp-reveal="career-head"]');
      if (careerHead) MLP.slideReveal(careerHead, career, { stagger: 0.08 });

      if (careerAnimate) {
        ensureScrollTrigger();

        if (careerSpine && careerStage && typeof ScrollTrigger !== "undefined") {
          gsap.fromTo(
            careerSpine,
            { scaleY: 0 },
            {
              scaleY: 1,
              ease: "none",
              scrollTrigger: { trigger: careerStage, start: "top 72%", end: "bottom 58%", scrub: 0.6 },
            }
          );
        }

        stories.forEach(function (story) {
          var feature = story.classList.contains("mlp-career__item--feature");
          var beat = feature ? 1.15 : 1;
          var frame = story.querySelector("[data-mlp-career-frame]");
          var node = story.querySelector("[data-mlp-career-node]");
          var who = story.querySelector("[data-mlp-career-who]");
          var rule = story.querySelector("[data-mlp-career-rule]");
          var roles = story.querySelectorAll("[data-mlp-career-role]");
          var arrow = story.querySelector("[data-mlp-career-arrow]");
          var quote = story.querySelector("[data-mlp-career-quote]");

          var careerX = MLP_SLIDE_X["mlp-career"] || 56;
          MLP.whenInView(story, "top 90%", function (instant) {
            if (instant) {
              gsap.set(story, { opacity: 1, x: 0 });
              if (node) gsap.set(node, { scale: 1 });
              if (frame) gsap.set(frame, { clipPath: "inset(0% 0 0 0)" });
              if (who) gsap.set(who, { opacity: 1, y: 0 });
              if (rule) gsap.set(rule, { scaleX: 1 });
              if (roles.length) gsap.set(roles, { opacity: 1, y: 0 });
              if (arrow) gsap.set(arrow, { opacity: 0.9 });
              if (quote) gsap.set(quote, { opacity: 1, y: 0 });
              story.classList.add("is-inview");
              return;
            }

            var storyTl = gsap.timeline({ defaults: { ease: "power3.out" } });

            storyTl.fromTo(story, { opacity: 0, x: careerX }, { opacity: 1, x: 0, duration: 0.7 * beat }, 0);
            if (node) storyTl.fromTo(node, { scale: 0 }, { scale: 1, duration: 0.45, ease: "power3.out" }, 0.15);
            if (frame) {
              storyTl.fromTo(
                frame,
                { clipPath: "inset(100% 0 0 0)" },
                { clipPath: "inset(0% 0 0 0)", duration: 0.85 * beat },
                0.1
              );
            }

            if (who) storyTl.fromTo(who, { opacity: 0, y: 16 }, { opacity: 1, y: 0, duration: 0.55 * beat }, 0.35 * beat);
            if (rule) storyTl.fromTo(rule, { scaleX: 0 }, { scaleX: 1, duration: 0.5, ease: "power2.out" }, 0.55 * beat);

            if (roles.length) {
              storyTl.fromTo(
                roles,
                { opacity: 0, y: 18 },
                { opacity: 1, y: 0, duration: 0.5 * beat, stagger: 0.16 },
                0.55 * beat
              );
            }
            if (arrow) storyTl.fromTo(arrow, { opacity: 0 }, { opacity: 0.9, duration: 0.35 }, 0.8 * beat);
            if (quote) storyTl.fromTo(quote, { opacity: 0, y: 12 }, { opacity: 1, y: 0, duration: 0.55 }, 0.95 * beat);

            storyTl.add(function () {
              story.classList.add("is-inview");
            }, 0);
          });
        });
      } else {
        stories.forEach(function (story) {
          story.classList.add("is-inview");
        });
      }
  }

  function initAlumni(alumni) {
      var alumniHead = alumni.querySelector('[data-mlp-reveal="alumni-head"]');
      var alumniTrust = alumni.querySelector('[data-mlp-reveal="alumni-trust"]');
      if (alumniHead) MLP.slideReveal(alumniHead, alumni, { stagger: 0.08 });
      if (alumniTrust) MLP.slideReveal(alumniTrust, alumni, { children: false, trigger: alumniTrust, duration: 0.65 });
  }

  function initLearning(learning) {
      var learningCopy = learning.querySelector('[data-mlp-reveal="learning-copy"]');
      var learningMedia = learning.querySelector('[data-mlp-reveal="learning-media"]');
      var learningKicker = learning.querySelector(".mlp-learning__kicker");
      var learningPlate = learning.querySelector("[data-mlp-learning-plate]");

      if (learningCopy) MLP.slideReveal(learningCopy, learning, { stagger: 0.08 });
      if (learningMedia) MLP.slideReveal(learningMedia, learning, { children: false, duration: 0.9 });

      if (learningKicker) {
        MLP.whenInView(learning, "top 90%", function (instant) {
          if (typeof gsap === "undefined") return;
          if (instant) {
            gsap.set(learningKicker, { scaleX: 1 });
            return;
          }
          gsap.fromTo(learningKicker, { scaleX: 0 }, { scaleX: 1, duration: 0.7, ease: "power2.out" });
        });
      }

      if (learningPlate && !prefersReduced() && typeof gsap !== "undefined") {
        MLP.whenInView(learningPlate, "top 90%", function (instant) {
          if (instant) {
            gsap.set(learningPlate, { clipPath: "polygon(8% 0, 100% 0, 100% 92%, 0 100%)" });
            return;
          }
          gsap.fromTo(
            learningPlate,
            { clipPath: "polygon(8% 0, 8% 0, 8% 100%, 0 100%)" },
            {
              clipPath: "polygon(8% 0, 100% 0, 100% 92%, 0 100%)",
              duration: 1.05,
              ease: "power3.out",
            }
          );
        });
      }
  }

  function initPartners(partners) {
      var partnersHead = partners.querySelector('[data-mlp-reveal="partners-head"]');
      var partnersStage = partners.querySelector('[data-mlp-reveal="partners-stage"]');
      var partnersTrust = partners.querySelector('[data-mlp-reveal="partners-trust"]');
      if (partnersHead) MLP.slideReveal(partnersHead, partners, { stagger: 0.08 });
      if (partnersStage) MLP.slideReveal(partnersStage, partners, { stagger: 0.07, duration: 0.7 });
      if (partnersTrust) MLP.slideReveal(partnersTrust, partners, { children: false, trigger: partnersTrust, duration: 0.65 });
  }

  function initTestimonials(testimonials) {
      var tHead = testimonials.querySelector('[data-mlp-reveal="testimonials-head"]');
      var tRail = testimonials.querySelector('[data-mlp-reveal="testimonials-rail"]');
      if (tHead) MLP.slideReveal(tHead, testimonials, { stagger: 0.08 });
      if (tRail) MLP.slideReveal(tRail, testimonials, { stagger: 0.1 });
  }

  function initCompare(compare) {
      var cHead = compare.querySelector('[data-mlp-reveal="compare-head"]');
      var cRows = compare.querySelector(".archive-parallel__rows");
      var cSteps = compare.querySelectorAll(".archive-parallel__row");

      bindTabs(compare, "data-compare-tab", "data-compare-pane", { preventDefault: true });

      if (cHead) MLP.slideReveal(cHead, compare, { stagger: 0.08 });

      if (!cRows || !cSteps.length) return;

      if (prefersReduced() || typeof gsap === "undefined") {
        cSteps.forEach(function (step) {
          step.style.opacity = "1";
          step.style.transform = "none";
        });
        return;
      }

      ensureScrollTrigger();
      gsap.set(cSteps, { opacity: 0, y: 48 });
      MLP.whenInView(cRows, "top 85%", function (instant) {
        if (instant) {
          gsap.set(cSteps, { opacity: 1, y: 0, clearProps: "willChange" });
          return;
        }
        gsap.fromTo(
          cSteps,
          { opacity: 0, y: 48 },
          {
            opacity: 1,
            y: 0,
            duration: 0.8,
            stagger: 0.12,
            ease: "power3.out",
            clearProps: "willChange",
          }
        );
      });
  }

  function initFaq(faq) {
      var faqHead = faq.querySelector('[data-mlp-reveal="faq-head"]');
      if (faqHead) MLP.slideReveal(faqHead, faq, { stagger: 0.08 });

      faq.querySelectorAll("[data-mlp-faq-toggle]").forEach(function (btn) {
        btn.addEventListener("click", function () {
          var row = btn.closest("[data-mlp-faq-row]");
          var panel = row ? row.querySelector("[data-mlp-faq-panel]") : null;
          if (!row || !panel) return;
          var open = row.classList.contains("is-open");

          faq.querySelectorAll("[data-mlp-faq-row].is-open").forEach(function (other) {
            if (other === row) return;
            other.classList.remove("is-open");
            var otherBtn = other.querySelector("[data-mlp-faq-toggle]");
            var otherPanel = other.querySelector("[data-mlp-faq-panel]");
            if (otherBtn) otherBtn.setAttribute("aria-expanded", "false");
            if (otherPanel) otherPanel.hidden = true;
          });

          if (open) {
            row.classList.remove("is-open");
            btn.setAttribute("aria-expanded", "false");
            panel.hidden = true;
          } else {
            row.classList.add("is-open");
            btn.setAttribute("aria-expanded", "true");
            panel.hidden = false;
          }
        });
      });
  }

  function initFinal(finalSec) {
      var finalCopy = finalSec.querySelector('[data-mlp-reveal="final-copy"]');
      var finalForm = finalSec.querySelector('[data-mlp-reveal="final-form"]');
      if (finalCopy) MLP.slideReveal(finalCopy, finalSec, { stagger: 0.08 });
      if (finalForm) MLP.slideReveal(finalForm, finalSec, { children: false, duration: 0.85 });
  }

  function initAccreditations(section) {
    var wrapper = section && section.querySelector(".accred-slider-wrapper");
    var track = wrapper && wrapper.querySelector(".accred-slider-track");
    if (!track || track.dataset.mlpAccreditationSlider === "1") return;
    track.dataset.mlpAccreditationSlider = "1";
    if (prefersReduced()) return;

    var cards = Array.prototype.slice.call(track.children);
    if (cards.length < 2) return;

    cards.forEach(function (card) {
      var clone = card.cloneNode(true);
      clone.setAttribute("aria-hidden", "true");
      track.appendChild(clone);
    });

    track.classList.add("is-landing-slider");
  }

  function initPartnerWall(wall) {
    if (!wall || wall.dataset.mlpPartnerWall === "1") return;
    wall.dataset.mlpPartnerWall = "1";

    var track = wall.querySelector("[data-partner-track]");
    var toggle = wall.querySelector("[data-partner-toggle]");
    if (!track) return;

    track.classList.add("is-marquee");
    var reduced = prefersReduced();
    var paused = reduced;

    function setPaused(value) {
      paused = value;
      wall.classList.toggle("is-paused", paused);
      if (toggle) {
        toggle.setAttribute("aria-pressed", paused ? "true" : "false");
        toggle.textContent = paused ? "Play" : "Pause";
      }
    }

    if (toggle && !reduced) {
      toggle.addEventListener("click", function () {
        setPaused(!paused);
      });
    }

    if ("IntersectionObserver" in window) {
      var wallIo = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            wall.classList.toggle("is-offscreen", !entry.isIntersecting);
          });
        },
        { threshold: 0 }
      );
      wallIo.observe(wall);
    }

    setPaused(reduced);
  }

  function initInlineYoutube(section) {
    var players = (section || document).querySelectorAll("[data-inline-youtube]");
    players.forEach(function (player) {
      if (player.dataset.mlpYoutubeInit === "1") return;
      player.dataset.mlpYoutubeInit = "1";

      var trigger = player.querySelector("[data-inline-youtube-trigger]");
      var embedUrl = player.getAttribute("data-video-embed");
      if (!trigger || !embedUrl) return;

      trigger.addEventListener("click", function () {
        if (player.classList.contains("is-playing")) return;

        var iframe = document.createElement("iframe");
        iframe.className = "archive-video-proof__iframe";
        iframe.src = embedUrl;
        iframe.title = player.getAttribute("data-video-title") || "Video player";
        iframe.loading = "eager";
        iframe.allow = "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share";
        iframe.referrerPolicy = "strict-origin-when-cross-origin";
        iframe.setAttribute("allowfullscreen", "");

        player.classList.add("is-playing");
        trigger.replaceWith(iframe);
      });
    });
  }

  function initTopicDesk(desk) {
    if (!desk) return;

    var tabs = Array.prototype.slice.call(desk.querySelectorAll("[data-topic-tab]"));
    var panels = Array.prototype.slice.call(desk.querySelectorAll("[data-topic-panel]"));
    var reduced = prefersReduced();
    var activeIndex = 0;

    function activate(index, moveFocus) {
      if (!tabs.length || !panels.length) return;
      activeIndex = (index + tabs.length) % tabs.length;

      tabs.forEach(function (tab, tabIndex) {
        var active = tabIndex === activeIndex;
        tab.classList.toggle("is-active", active);
        tab.setAttribute("aria-selected", active ? "true" : "false");
        tab.setAttribute("tabindex", active ? "0" : "-1");
      });

      panels.forEach(function (panel, panelIndex) {
        var active = panelIndex === activeIndex;
        if (active) {
          panel.hidden = false;
          panel.setAttribute("aria-hidden", "false");
          panel.classList.remove("is-switching");
          window.requestAnimationFrame(function () {
            panel.classList.add("is-switching");
          });
        } else {
          panel.hidden = true;
          panel.setAttribute("aria-hidden", "true");
          panel.classList.remove("is-switching");
        }
      });

      if (moveFocus) tabs[activeIndex].focus();
    }

    tabs.forEach(function (tab, index) {
      tab.setAttribute("tabindex", index === 0 ? "0" : "-1");
      tab.addEventListener("click", function () {
        activate(index, false);
      });
      tab.addEventListener("keydown", function (event) {
        if (event.key === "ArrowDown" || event.key === "ArrowRight") {
          event.preventDefault();
          activate(index + 1, true);
        }
        if (event.key === "ArrowUp" || event.key === "ArrowLeft") {
          event.preventDefault();
          activate(index - 1, true);
        }
        if (event.key === "Home") {
          event.preventDefault();
          activate(0, true);
        }
        if (event.key === "End") {
          event.preventDefault();
          activate(tabs.length - 1, true);
        }
      });
    });

    activate(0, false);
    if (reduced) desk.classList.add("is-reduced-motion");
  }

  function initLuxuryTestimonials(carousel) {
    if (!carousel) return;

    var slides = Array.prototype.slice.call(carousel.querySelectorAll("[data-testimonial-slide]"));
    var previous = carousel.querySelector("[data-testimonial-prev]");
    var next = carousel.querySelector("[data-testimonial-next]");
    var toggle = carousel.querySelector("[data-testimonial-toggle]");
    var current = carousel.querySelector("[data-testimonial-current]");
    var reduced = prefersReduced();
    var activeIndex = 0;
    var paused = reduced;
    var hovered = false;
    var focused = false;
    var timer = null;
    var resumeTimer = null;
    var startX = null;

    function updateToggle() {
      if (!toggle) return;
      toggle.setAttribute("aria-pressed", paused ? "true" : "false");
      toggle.textContent = paused ? "Play" : "Pause";
    }

    function showSlide(index) {
      if (!slides.length) return;
      activeIndex = (index + slides.length) % slides.length;

      slides.forEach(function (slide, slideIndex) {
        var active = slideIndex === activeIndex;
        slide.hidden = !active;
        slide.setAttribute("aria-hidden", active ? "false" : "true");
        slide.classList.toggle("is-active", active);
        slide.classList.remove("is-entering");
        if (active && !reduced) {
          window.requestAnimationFrame(function () {
            slide.classList.add("is-entering");
          });
        }
      });

      if (current) current.textContent = String(activeIndex + 1).padStart(2, "0");
    }

    function goNext() {
      showSlide(activeIndex + 1);
    }

    function goPrevious() {
      showSlide(activeIndex - 1);
    }

    function scheduleResume() {
      if (reduced) return;
      window.clearTimeout(resumeTimer);
      paused = true;
      updateToggle();
      resumeTimer = window.setTimeout(function () {
        if (!hovered && !focused) {
          paused = false;
          updateToggle();
        }
      }, 4200);
    }

    function startTimer() {
      if (reduced || slides.length < 2) return;
      window.clearInterval(timer);
      timer = window.setInterval(function () {
        if (!paused && !hovered && !focused) goNext();
      }, 6000);
    }

    if (previous) previous.addEventListener("click", function () { goPrevious(); scheduleResume(); });
    if (next) next.addEventListener("click", function () { goNext(); scheduleResume(); });
    if (toggle) {
      toggle.addEventListener("click", function () {
        paused = !paused;
        updateToggle();
        startTimer();
      });
    }

    carousel.addEventListener("mouseenter", function () { hovered = true; });
    carousel.addEventListener("mouseleave", function () { hovered = false; });
    carousel.addEventListener("focusin", function () { focused = true; });
    carousel.addEventListener("focusout", function () { focused = false; });
    carousel.addEventListener("keydown", function (event) {
      if (event.key === "ArrowLeft") {
        event.preventDefault();
        goPrevious();
        scheduleResume();
      }
      if (event.key === "ArrowRight") {
        event.preventDefault();
        goNext();
        scheduleResume();
      }
    });

    carousel.addEventListener("pointerdown", function (event) {
      startX = event.clientX;
      scheduleResume();
    });
    carousel.addEventListener("pointerup", function (event) {
      if (startX === null) return;
      var distance = event.clientX - startX;
      startX = null;
      if (Math.abs(distance) > 44) {
        if (distance < 0) goNext();
        else goPrevious();
      }
    });
    carousel.addEventListener("pointercancel", function () { startX = null; });

    showSlide(0);
    updateToggle();
    startTimer();

    window.addEventListener("beforeunload", function () {
      window.clearInterval(timer);
      window.clearTimeout(resumeTimer);
    }, { once: true });
  }

  function initPage() {
    initMotionPauses();

    MLP.observeInView(
      "[data-archive-class], [data-archive-career], [data-archive-learning], [data-archive-element], [data-closing-element], [data-closing-voices]"
    );

    MLP.observeSection(".mlp-trust", initTrust, { rootMargin: "400px 0px" });
    MLP.observeSection("#mlp-overview", initOverview, { rootMargin: "400px 0px" });
    MLP.observeSection("#mlp-why", initWhy);
    MLP.observeSection("#mlp-journey", initJourney);
    MLP.observeSection("#mlp-mba", initMba);
    MLP.observeSection("#mlp-masters", initMasters);
    MLP.observeSection("#mlp-fees", initFees);
    MLP.observeSection("#mlp-class", initClass);
    MLP.observeSection("#mlp-career", initCareer);
    MLP.observeSection("#mlp-alumni", initAlumni);
    MLP.observeSection("#mlp-learning", initLearning);
    MLP.observeSection("#mlp-partners", initPartners);
    MLP.observeSection("#mlp-testimonials", initTestimonials);
    MLP.observeSection("#mlp-compare", initCompare);
    MLP.observeSection("#mlp-faq", initFaq);
    MLP.observeSection("#mlp-final", initFinal);
    MLP.observeSection("#accreditations", initAccreditations);
    MLP.observeSection("[data-partner-wall]", initPartnerWall);
    MLP.observeSection("#mlp-video-proof", initInlineYoutube);
    MLP.observeSection("[data-topic-desk]", initTopicDesk);
    MLP.observeSection("[data-luxury-testimonials]", initLuxuryTestimonials);

    requestAnimationFrame(function () {
      requestAnimationFrame(function () {
        MLP.rescuePastReveals();
      });
    });
  }

  function bootPage() {
    initHeroAssembly();
    setTimeout(initPage, 50);
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", bootPage);
  } else {
    bootPage();
  }

  window.addEventListener("load", function () {
    var hero = document.querySelector(".mlp-hero");
    if (hero && !hero.classList.contains("is-ready")) {
      hero.classList.add("is-ready");
    }
    if (hero && !hero.classList.contains("is-hero-assembled")) {
      hero.classList.add("is-hero-assembled");
    }
    setTimeout(function () {
      MLP.rescuePastReveals();
    }, 50);
  });
  window.addEventListener("pageshow", function () {
    setTimeout(function () {
      MLP.rescuePastReveals();
    }, 50);
  });
})();
