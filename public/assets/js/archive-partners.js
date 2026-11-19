(function () {
  "use strict";

  var reducedMotionQuery = window.matchMedia("(prefers-reduced-motion: reduce)");

  function initPartnerWall(wall) {
    if (!wall || wall.dataset.mlpPartnerWall === "1") return;
    wall.dataset.mlpPartnerWall = "1";

    var viewport = wall.querySelector("[data-partner-viewport]");
    var track = wall.querySelector("[data-partner-track]");
    var list = wall.querySelector("[data-partner-list]");
    var controls = wall.querySelector("[data-partner-controls]");
    var toggle = wall.querySelector("[data-partner-toggle]");

    if (!viewport || !track || !list) return;

    var clone = null;
    var userPaused = false;
    var hovered = false;
    var focused = false;
    var isIntersecting = true;
    var updateScheduled = false;

    function syncPauseState() {
      var paused = userPaused || hovered || focused || !isIntersecting || reducedMotionQuery.matches;
      wall.classList.toggle("is-paused", paused);

      if (toggle) {
        toggle.setAttribute("aria-pressed", userPaused ? "true" : "false");
        toggle.textContent = userPaused ? "Play" : "Pause";
      }
    }

    function addVisualClone() {
      if (clone) return;

      clone = list.cloneNode(true);
      clone.classList.add("archive-partners__logo-list--clone");
      clone.setAttribute("aria-hidden", "true");
      clone.removeAttribute("aria-label");
      clone.querySelectorAll("[id]").forEach(function (element) {
        element.removeAttribute("id");
      });
      clone.querySelectorAll("img").forEach(function (image) {
        image.setAttribute("alt", "");
      });
      track.appendChild(clone);
    }

    function updateLayout() {
      var shouldAnimate = !reducedMotionQuery.matches
        && list.children.length > 1
        && viewport.clientWidth > 0
        && list.scrollWidth > viewport.clientWidth + 1;

      if (shouldAnimate) {
        addVisualClone();
        var durationSeconds = Math.max(28, list.scrollWidth / 40);
        track.style.setProperty("--partner-marquee-duration", durationSeconds + "s");
        track.classList.add("is-marquee");
        wall.classList.add("is-animating");
        if (controls) controls.hidden = false;
      } else {
        track.classList.remove("is-marquee");
        wall.classList.remove("is-animating");
        if (clone) {
          clone.remove();
          clone = null;
        }
        if (controls) controls.hidden = true;
      }

      syncPauseState();
    }

    function scheduleLayoutUpdate() {
      if (updateScheduled) return;
      updateScheduled = true;
      window.requestAnimationFrame(function () {
        updateScheduled = false;
        updateLayout();
      });
    }

    if (toggle) {
      toggle.addEventListener("click", function () {
        userPaused = !userPaused;
        syncPauseState();
      });
    }

    wall.addEventListener("mouseenter", function () {
      hovered = true;
      syncPauseState();
    });
    wall.addEventListener("mouseleave", function () {
      hovered = false;
      syncPauseState();
    });
    wall.addEventListener("focusin", function () {
      focused = true;
      syncPauseState();
    });
    wall.addEventListener("focusout", function (event) {
      if (!wall.contains(event.relatedTarget)) {
        focused = false;
        syncPauseState();
      }
    });

    if ("IntersectionObserver" in window) {
      wall.classList.add("is-offscreen");
      var wallObserver = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            isIntersecting = entry.isIntersecting;
            wall.classList.toggle("is-offscreen", !isIntersecting);
          });
          syncPauseState();
        },
        { threshold: 0 }
      );
      wallObserver.observe(wall);
    }

    if ("ResizeObserver" in window) {
      var resizeObserver = new ResizeObserver(scheduleLayoutUpdate);
      resizeObserver.observe(viewport);
    } else {
      window.addEventListener("resize", scheduleLayoutUpdate, { passive: true });
    }

    if (typeof reducedMotionQuery.addEventListener === "function") {
      reducedMotionQuery.addEventListener("change", scheduleLayoutUpdate);
    } else if (typeof reducedMotionQuery.addListener === "function") {
      reducedMotionQuery.addListener(scheduleLayoutUpdate);
    }

    scheduleLayoutUpdate();
  }

  document.querySelectorAll("[data-partner-wall]").forEach(initPartnerWall);
})();
