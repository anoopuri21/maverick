/**
 * Programme listing — category filter + pagination + infinite scroll.
 *
 * Desktop: Server-side pagination links (claymorphism style).
 * Mobile:   Infinite scroll — auto-loads next 12 programs when the user
 *           scrolls to the bottom sentinel. No URL change (AJAX only).
 */

(function () {
  "use strict";

  const filterEl = document.querySelector("[data-pl-filter]");
  const gridEl = document.querySelector("[data-pl-grid]");
  const sentinelEl = document.querySelector("[data-pl-sentinel]");
  const loadMoreEl = document.querySelector("[data-pl-load-more]");

  if (!gridEl) return;

  // ---------------------------------------------------------------
  // Reveal-on-scroll observer for cards (runs on ALL devices)
  // ---------------------------------------------------------------
  const prefersReduced =
    window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  const cardIo = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("is-revealed");
          cardIo.unobserve(entry.target);
        }
      });
    },
    { rootMargin: "0px 0px -10% 0px", threshold: 0.05 }
  );

  // Observe existing cards
  const existingCards = Array.from(gridEl.querySelectorAll(".pl-card"));
  existingCards.forEach((card) => cardIo.observe(card));

  // Fail-open: reveal all cards after 3s
  window.setTimeout(() => {
    existingCards.forEach((card) => card.classList.add("is-revealed"));
  }, 3000);

  // ---------------------------------------------------------------
  // Desktop pagination: nothing to do — server-rendered links work.
  // ---------------------------------------------------------------

  // ---------------------------------------------------------------
  // Mobile infinite scroll
  // ---------------------------------------------------------------
  const isMobile = window.matchMedia("(max-width: 1024px)").matches;

  if (!isMobile || !sentinelEl || !loadMoreEl) return;

  const isLoading = () => loadMoreEl.classList.contains("is-loading");

  const setLoading = (loading) => {
    if (loading) {
      loadMoreEl.classList.add("is-loading");
    } else {
      loadMoreEl.classList.remove("is-loading");
    }
  };

  const getNextPage = () => {
    const next = sentinelEl.getAttribute("data-next-page");
    return next ? parseInt(next, 10) : null;
  };

  const getLastPage = () => {
    const last = sentinelEl.getAttribute("data-last-page");
    return last ? parseInt(last, 10) : null;
  };

  const getCategory = () => {
    const params = new URLSearchParams(window.location.search);
    return params.get("category") || "all";
  };

  const appendCards = (html) => {
    // Insert before sentinel so sentinel stays at the very bottom
    const wrapper = document.createElement("div");
    wrapper.innerHTML = html.trim();
    const cards = Array.from(wrapper.querySelectorAll(".pl-card"));
    sentinelEl.before(...cards);

    // Re-observe new cards for reveal animation
    if (!prefersReduced && "IntersectionObserver" in window) {
      cards.forEach((card) => cardIo.observe(card));
    }
  };

  const loadNextPage = async () => {
    if (isLoading()) return;

    const currentPage = getNextPage();
    const lastPage = getLastPage();

    if (!currentPage || !lastPage || currentPage > lastPage) {
      // No more pages — remove sentinel + loading UI
      sentinelEl.remove();
      loadMoreEl.remove();
      return;
    }

    setLoading(true);

    try {
      const params = new URLSearchParams(window.location.search);
      params.set("page", currentPage);
      const category = getCategory();
      if (category && category !== "all") {
        params.set("category", category);
      }

      const response = await fetch(`${window.location.pathname}?${params.toString()}`, {
        headers: {
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
      });

      if (!response.ok) throw new Error("Network response was not ok");

      const data = await response.json();

      if (data.html) {
        appendCards(data.html);

        // Update sentinel attributes for next load
        if (data.nextPage) {
          sentinelEl.setAttribute("data-next-page", data.nextPage);
        } else {
          sentinelEl.remove();
          loadMoreEl.remove();
        }
      }
    } catch (err) {
      console.error("Infinite scroll error:", err);
    } finally {
      setLoading(false);
    }
  };

  // Sentinel observer — triggers loadNextPage when scrolled into view
  const sentinelIo = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          loadNextPage();
        }
      });
    },
    { rootMargin: "0px 0px 15% 0px", threshold: 0.1 }
  );

  sentinelIo.observe(sentinelEl);

  // ---------------------------------------------------------------
  // Category filter: reset infinite scroll state on category change
  // ---------------------------------------------------------------
  if (filterEl) {
    const buttons = Array.from(filterEl.querySelectorAll(".pl-filter__btn"));

    buttons.forEach((btn) => {
      btn.addEventListener("click", () => {
        const category = btn.getAttribute("data-filter");
        const url = new URL(window.location.href);
        if (category === "all") {
          url.searchParams.delete("category");
        } else {
          url.searchParams.set("category", category);
        }
        url.searchParams.delete("page");
        window.location.href = url.toString();
      });
    });
  }
})();
