/**
 * Class Profile Topic Desk — accessible topic switching with in-flow fallback.
 */
(function () {
  "use strict";

  var desk = document.querySelector("[data-topic-desk]");
  if (!desk) return;

  var tabs = Array.prototype.slice.call(desk.querySelectorAll("[data-topic-tab]"));
  var panels = Array.prototype.slice.call(desk.querySelectorAll("[data-topic-panel]"));
  var reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
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

  // Honour the native first-panel fallback and make state explicit when JS is ready.
  activate(0, false);
  if (reduced) desk.classList.add("is-reduced-motion");
})();
