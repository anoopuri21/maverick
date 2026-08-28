/**
 * Inline YouTube film plate for the MBA & Master's landing page.
 * Keeps the initial page light and never opens a modal or popup.
 */
(function () {
  "use strict";

  var players = document.querySelectorAll("[data-inline-youtube]");
  if (!players.length) return;

  players.forEach(function (player) {
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
})();
