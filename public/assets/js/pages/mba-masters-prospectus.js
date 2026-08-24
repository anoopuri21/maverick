/**
 * The Living Prospectus — Hero + admissions insert + evidence margin.
 *
 * This module is deliberately separate from the legacy MLP motion script:
 * it owns the new opening interaction without recreating the old hero/trust
 * visual grammar. Native <details> keeps the form usable without JavaScript.
 */
(function () {
  "use strict";

  var insert = document.querySelector("[data-prospectus-insert]");
  if (!insert) return;

  var reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
  var lastTrigger = null;
  var focusSelector = [
    "input:not([type=hidden])",
    "select",
    "textarea",
    "button",
    "a[href]",
  ].join(",");

  function isReduced() {
    return reducedMotion.matches;
  }

  function firstFocusable() {
    return insert.querySelector(focusSelector);
  }

  function syncInsertState() {
    insert.classList.toggle("is-open", insert.open);
  }

  function openInsert(trigger, event) {
    if (event) {
      event.preventDefault();
      event.stopPropagation();
    }

    lastTrigger = trigger || lastTrigger;
    insert.open = true;
    syncInsertState();

    window.requestAnimationFrame(function () {
      insert.scrollIntoView({
        behavior: isReduced() ? "auto" : "smooth",
        block: "start",
      });

      window.setTimeout(function () {
        var target = firstFocusable();
        if (target) target.focus({ preventScroll: true });
      }, isReduced() ? 0 : 260);
    });
  }

  function closeInsert(event) {
    if (event) event.preventDefault();

    insert.open = false;
    syncInsertState();

    if (lastTrigger && typeof lastTrigger.focus === "function") {
      window.setTimeout(function () {
        lastTrigger.focus({ preventScroll: true });
      }, 0);
    }
  }

  // All same-page enquiry links on this landing page open the insert.
  document.querySelectorAll('a[href="#mlp-enquire"], [data-prospectus-open]').forEach(function (trigger) {
    trigger.addEventListener("click", function (event) {
      openInsert(trigger, event);
    });
  });

  var closeButton = insert.querySelector("[data-prospectus-close]");
  if (closeButton) closeButton.addEventListener("click", closeInsert);

  insert.addEventListener("toggle", syncInsertState);

  document.addEventListener("keydown", function (event) {
    if (event.key === "Escape" && insert.open) {
      closeInsert(event);
    }
  });

  var records = document.querySelectorAll("[data-prospectus-evidence-record]");
  if (isReduced() || !("IntersectionObserver" in window)) {
    records.forEach(function (record) {
      record.classList.add("is-inview");
    });
    return;
  }

  var observer = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        entry.target.classList.add("is-inview");
        observer.unobserve(entry.target);
      });
    },
    { threshold: 0.25, rootMargin: "0px 0px -8% 0px" }
  );

  records.forEach(function (record) {
    observer.observe(record);
  });
})();
