/**
 * Maverick Business Academy
 * Faculty Insights Slider — Single card per view, CLS-safe
 * Professional slider with dots, counter, prev/next
 * Uses static dots from Blade to prevent CLS, JS only enhances active state
 * Core Web Vitals: no layout shift, transform/opacity only
 */
(function () {
  "use strict";

  const GAP = 32;

  function pad(num) {
    return String(num).padStart(2, "0");
  }

  function initSlider(root) {
    const container = root.querySelector("[data-fi-container]") || root.querySelector("[data-scroll-container]");
    const track = root.querySelector("[data-fi-track]");
    const cards = track ? track.querySelectorAll("[data-fi-card]") : [];
    const paginationEl = root.querySelector("[data-fi-pagination]");
    const currentEl = root.querySelector("[data-fi-current]");
    const totalEl = root.querySelector("[data-fi-total]");
    const prevBtn = root.querySelector("[data-fi-prev]");
    const nextBtn = root.querySelector("[data-fi-next]");

    if (!container || !track || !cards.length) return;

    const total = cards.length;
    if (totalEl) totalEl.textContent = pad(total);

    // CLS-safe: Use existing dots from Blade if present, else generate
    let dots = [];
    if (paginationEl) {
      dots = Array.from(paginationEl.querySelectorAll("[data-fi-dot]"));
      if (dots.length === 0) {
        // Fallback generate if Blade didn't output dots
        paginationEl.innerHTML = "";
        cards.forEach(function (_, i) {
          var btn = document.createElement("button");
          btn.type = "button";
          btn.className = "insights__pagination-dot" + (i === 0 ? " is-active" : "");
          btn.setAttribute("aria-label", "Go to faculty " + (i + 1));
          btn.dataset.fiDot = String(i);
          paginationEl.appendChild(btn);
        });
        dots = Array.from(paginationEl.querySelectorAll("[data-fi-dot]"));
      }
      // Bind click to existing dots
      dots.forEach(function (dot, i) {
        if (!dot.dataset.fiBound) {
          dot.dataset.fiBound = "1";
          dot.addEventListener("click", function () {
            scrollToIndex(i);
          });
        }
      });
    }

    let currentIndex = 0;
    let ticking = false;

    function getMaxIndex() {
      return total - 1;
    }

    function getScrollAmount() {
      return container.clientWidth + GAP;
    }

    function updateUI(index) {
      currentIndex = Math.max(0, Math.min(index, getMaxIndex()));
      if (currentEl) currentEl.textContent = pad(currentIndex + 1);
      if (dots.length) {
        dots.forEach(function (d, i) {
          d.classList.toggle("is-active", i === currentIndex);
        });
      }
      if (prevBtn) prevBtn.disabled = currentIndex === 0;
      if (nextBtn) nextBtn.disabled = currentIndex === getMaxIndex();
    }

    function scrollToIndex(index, behavior) {
      var clamped = Math.max(0, Math.min(index, getMaxIndex()));
      var target = clamped * getScrollAmount();
      if (behavior === "auto") {
        container.scrollLeft = target;
      } else {
        container.scrollTo({ left: target, behavior: "smooth" });
      }
      updateUI(clamped);
    }

    if (prevBtn) {
      prevBtn.addEventListener("click", function () {
        scrollToIndex(currentIndex - 1);
      });
    }
    if (nextBtn) {
      nextBtn.addEventListener("click", function () {
        scrollToIndex(currentIndex + 1);
      });
    }

    // Keyboard accessibility
    container.setAttribute("tabindex", "0");
    container.addEventListener("keydown", function (e) {
      if (e.key === "ArrowLeft") {
        e.preventDefault();
        scrollToIndex(currentIndex - 1);
      } else if (e.key === "ArrowRight") {
        e.preventDefault();
        scrollToIndex(currentIndex + 1);
      }
    });

    // Sync on scroll - RAF throttled, no CLS
    container.addEventListener(
      "scroll",
      function () {
        if (ticking) return;
        ticking = true;
        requestAnimationFrame(function () {
          var scrollLeft = container.scrollLeft;
          var amount = getScrollAmount();
          var idx = Math.round(scrollLeft / amount);
          updateUI(idx);
          ticking = false;
        });
      },
      { passive: true }
    );

    // Resize - recalc without animation to avoid CLS
    var resizeTimer = null;
    window.addEventListener("resize", function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function () {
        scrollToIndex(currentIndex, "auto");
      }, 150);
    });

    // Initial UI
    updateUI(0);

    // Expose for external refresh if needed (no toggle now, but kept)
    root.__fiUpdate = function () {
      updateUI(currentIndex);
    };
  }

  function init() {
    var sliders = document.querySelectorAll("[data-fi-slider]");
    sliders.forEach(initSlider);
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
