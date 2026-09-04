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

  function initTrust(trust) {
    var featured = document.querySelector('[data-mlp-reveal="trust-featured"]');
    var trustRail = document.querySelector('[data-mlp-reveal="trust-fan"]');
    var fanSvg = document.querySelector(".mlp-trust__fan-svg");

    if (trust && !prefersReduced() && typeof gsap !== "undefined") {
      ensureScrollTrigger();
      if (featured) {
        MLP.reveal(featured, {
          children: false,
          scroll: true,
          trigger: trust,
          y: 40,
          duration: 0.95,
        });
      }
      if (trustRail) {
        MLP.reveal(trustRail, {
          scroll: true,
          trigger: trust,
          stagger: 0.08,
          y: 28,
          duration: 0.7,
        });
      }
      MLP.count(trust, { duration: 1.5 });

      if (fanSvg && typeof ScrollTrigger !== "undefined") {
        var paths = fanSvg.querySelectorAll("path, line");
        MLP.whenInView(trust, "top 75%", function (instant) {
          if (instant) {
            gsap.set(paths, { strokeDasharray: 800, strokeDashoffset: 0, opacity: 1 });
            return;
          }
          gsap.set(paths, { strokeDasharray: 800, strokeDashoffset: 800, opacity: 0.15 });
          gsap.to(paths, {
            strokeDashoffset: 0,
            opacity: 1,
            duration: 1.4,
            stagger: 0.06,
            ease: "power2.out",
          });
        });
      }
    } else if (trust) {
      MLP.count(trust);
    }
  }

  function initOverview(overview) {
      var head = overview.querySelector('[data-mlp-reveal="overview-head"]');
      var rails = overview.querySelector('[data-mlp-reveal="overview-rails"]');
      var ctas = overview.querySelector('[data-mlp-reveal="overview-ctas"]');
      if (head) MLP.slideReveal(head, overview, { stagger: 0.08 });
      if (rails) MLP.slideReveal(rails, overview, { stagger: 0.07, duration: 0.65 });
      if (ctas) MLP.slideReveal(ctas, overview, { children: false, duration: 0.6 });
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
      var jTrack = journey.querySelector('[data-mlp-reveal="journey-track"]');
      var jCta = journey.querySelector('[data-mlp-reveal="journey-cta"]');
      var jFill = journey.querySelector(".mlp-journey__spine-fill");
      var jSteps = journey.querySelectorAll(".mlp-journey__step");

      if (jHead) MLP.slideReveal(jHead, journey, { stagger: 0.08 });
      if (jCta) MLP.slideReveal(jCta, journey, { children: false });

      if (jTrack && !prefersReduced() && typeof gsap !== "undefined") {
        ensureScrollTrigger();
        if (jSteps.length) {
          var jX = MLP_SLIDE_X["mlp-journey"] || -56;
          MLP.whenInView(jTrack, "top 90%", function (instant) {
            if (instant) {
              gsap.set(jSteps, { opacity: 1, x: 0 });
              return;
            }
            gsap.set(jSteps, { opacity: 0, x: jX });
            gsap.to(jSteps, {
              opacity: 1,
              x: 0,
              duration: 0.7,
              stagger: 0.1,
              ease: "power3.out",
            });
          });
        }
        if (jFill && typeof ScrollTrigger !== "undefined") {
          gsap.fromTo(
            jFill,
            { height: "0%" },
            {
              height: "100%",
              ease: "none",
              scrollTrigger: {
                trigger: jTrack,
                start: "top 70%",
                end: "bottom 55%",
                scrub: 0.6,
              },
            }
          );
        }
      }
  }

  function initMba(mbaRoot) {
      var mbaHead = mbaRoot.querySelector('[data-mlp-reveal="mba-head"]');
      if (mbaHead) MLP.slideReveal(mbaHead, mbaRoot, { stagger: 0.08 });

      var mbaChrome = mbaRoot.querySelector("[data-mlp-mba-tabs]");
      if (mbaChrome) {
        MLP.slideReveal(mbaChrome, mbaRoot, { children: false, duration: 0.8 });

        var tabs = mbaChrome.querySelectorAll("[data-mlp-mba-tab]");
        var panels = mbaChrome.querySelectorAll("[data-mlp-mba-panel]");
        tabs.forEach(function (tab) {
          tab.addEventListener("click", function () {
            var id = tab.getAttribute("data-mlp-mba-tab");
            tabs.forEach(function (t) {
              var on = t === tab;
              t.classList.toggle("is-active", on);
              t.setAttribute("aria-selected", on ? "true" : "false");
            });
            panels.forEach(function (panel) {
              var on = panel.getAttribute("data-mlp-mba-panel") === id;
              panel.classList.toggle("is-active", on);
              if (on) panel.removeAttribute("hidden");
              else panel.setAttribute("hidden", "hidden");
            });
          });
        });
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
      var feesHead = fees.querySelector('[data-mlp-reveal="fees-head"]');
      var feesMatrix = fees.querySelector('[data-mlp-reveal="fees-matrix"]');
      var feesNote = fees.querySelector('[data-mlp-reveal="fees-note"]');
      var feesCtas = fees.querySelector('[data-mlp-reveal="fees-ctas"]');
      if (feesHead) MLP.slideReveal(feesHead, fees, { stagger: 0.08 });
      if (feesMatrix) MLP.slideReveal(feesMatrix, fees, { stagger: 0.07, duration: 0.65 });
      if (feesNote) MLP.slideReveal(feesNote, fees, { children: false });
      if (feesCtas) MLP.slideReveal(feesCtas, fees, { children: false });
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
      var cMatrix = compare.querySelector('[data-mlp-reveal="compare-matrix"]');
      var cCta = compare.querySelector('[data-mlp-reveal="compare-cta"]');
      if (cHead) MLP.slideReveal(cHead, compare, { stagger: 0.08 });
      if (cMatrix) MLP.slideReveal(cMatrix, compare, { stagger: 0.06, duration: 0.7 });
      if (cCta) MLP.slideReveal(cCta, compare, { children: false, trigger: cCta, duration: 0.6 });
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

  function initPage() {
    var hero = document.querySelector(".mlp-hero");
    var copy = document.querySelector('[data-mlp-reveal="hero-copy"]');
    var form = document.querySelector('[data-mlp-reveal="hero-form"]');
    var bg = document.querySelector(".mlp-hero__bg");
    var kinetic = document.querySelector("[data-mlp-kinetic]");

    function markHeroReady() {
      if (hero) hero.classList.add("is-ready");
    }
    if (bg) {
      if (bg.complete) markHeroReady();
      else {
        bg.addEventListener("load", markHeroReady, { once: true });
        bg.addEventListener("error", markHeroReady, { once: true });
      }
    } else if (hero) {
      markHeroReady();
    }

    if (kinetic && !prefersReduced() && typeof gsap !== "undefined") {
      var lines = kinetic.querySelectorAll(".mlp-hero__line");
      gsap.set(lines, { yPercent: 110, opacity: 0 });
      gsap.to(lines, {
        yPercent: 0,
        opacity: 1,
        duration: 1,
        stagger: 0.12,
        ease: "power3.out",
        delay: 0.2,
      });
    }

    if (copy) {
      var kids = Array.prototype.filter.call(copy.children, function (el) {
        return !el.hasAttribute("data-mlp-kinetic");
      });
      if (kids.length && !prefersReduced() && typeof gsap !== "undefined") {
        gsap.set(kids, { opacity: 0, y: 24 });
        gsap.to(kids, {
          opacity: 1,
          y: 0,
          duration: 0.8,
          stagger: 0.09,
          ease: "power3.out",
          delay: 0.08,
        });
      }
    }
    if (form) MLP.reveal(form, { children: false, y: 28, delay: 0.35, duration: 0.9 });
    if (bg) MLP.parallax(bg, { yPercent: 8, trigger: hero });

    initMotionPauses();

    MLP.observeSection(".mlp-trust", initTrust, { rootMargin: "400px 0px" });
    MLP.observeSection("#mlp-overview", initOverview);
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

    // After browser scroll restoration, rescue any past-but-still-hidden reveals
    requestAnimationFrame(function () {
      requestAnimationFrame(function () {
        MLP.rescuePastReveals();
      });
    });
  }

  function bootPage() {
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
