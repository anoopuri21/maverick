/**
 * Maverick Business Academy
 * Faculty Insights Slider — Single card per view
 * Professional slider with dots, counter, prev/next
 * Uses same scroll container as scroll-controls for drag support
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

    // Generate dots
    let dots = [];
    if (paginationEl) {
      paginationEl.innerHTML = "";
      cards.forEach((_, i) => {
        const btn = document.createElement("button");
        btn.type = "button";
        btn.className = "insights__pagination-dot" + (i === 0 ? " is-active" : "");
        btn.setAttribute("aria-label", "Go to faculty " + (i + 1));
        btn.dataset.fiDot = String(i);
        btn.addEventListener("click", () => scrollToIndex(i));
        paginationEl.appendChild(btn);
      });
      dots = Array.from(paginationEl.querySelectorAll("[data-fi-dot]"));
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
        dots.forEach((d, i) => {
          d.classList.toggle("is-active", i === currentIndex);
        });
      }
      if (prevBtn) prevBtn.disabled = currentIndex === 0;
      if (nextBtn) nextBtn.disabled = currentIndex === getMaxIndex();
    }

    function scrollToIndex(index, behavior) {
      const clamped = Math.max(0, Math.min(index, getMaxIndex()));
      const target = clamped * getScrollAmount();
      if (behavior === "auto") {
        container.scrollLeft = target;
      } else {
        container.scrollTo({ left: target, behavior: "smooth" });
      }
      updateUI(clamped);
    }

    // Click handlers
    if (prevBtn) {
      prevBtn.addEventListener("click", () => {
        scrollToIndex(currentIndex - 1);
      });
    }
    if (nextBtn) {
      nextBtn.addEventListener("click", () => {
        scrollToIndex(currentIndex + 1);
      });
    }

    // Keyboard
    container.setAttribute("tabindex", "0");
    container.addEventListener("keydown", (e) => {
      if (e.key === "ArrowLeft") {
        e.preventDefault();
        scrollToIndex(currentIndex - 1);
      } else if (e.key === "ArrowRight") {
        e.preventDefault();
        scrollToIndex(currentIndex + 1);
      }
    });

    // Sync on scroll
    container.addEventListener(
      "scroll",
      () => {
        if (ticking) return;
        ticking = true;
        requestAnimationFrame(() => {
          const scrollLeft = container.scrollLeft;
          const amount = getScrollAmount();
          const idx = Math.round(scrollLeft / amount);
          updateUI(idx);
          ticking = false;
        });
      },
      { passive: true }
    );

    // Resize
    let resizeTimer = null;
    window.addEventListener("resize", () => {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(() => {
        scrollToIndex(currentIndex, "auto");
      }, 150);
    });

    // Initial
    updateUI(0);

    // Expose for external refresh (e.g., after Read more expands)
    root.__fiUpdate = () => updateUI(currentIndex);
  }

  function init() {
    const sliders = document.querySelectorAll("[data-fi-slider]");
    sliders.forEach(initSlider);
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
