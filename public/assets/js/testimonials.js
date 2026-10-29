(function () {
  "use strict";

  // Testimonials data - flattened array with category badges
  const testimonialsData = Array.isArray(window.testimonialsData)
    ? window.testimonialsData
    : [];

  // DOM elements
  const track = document.getElementById("testimonialsTrack");
  const modal = document.getElementById("videoModal");
  const modalClose = document.getElementById("modalClose");
  const modalPlayer = document.getElementById("modalPlayer");
  const inlineDesktopQuery = window.matchMedia("(min-width: 1024px)");
  const HTML_ESCAPE_MAP = {
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': "&quot;",
    "'": "&#39;",
  };
  const YOUTUBE_HOSTS = new Set([
    "youtube.com",
    "m.youtube.com",
    "youtu.be",
    "youtube-nocookie.com",
  ]);
  const HTTP_PROTOCOLS = new Set(["http:", "https:"]);

  function escapeHtml(value) {
    return String(value ?? "").replace(
      /[&<>"']/g,
      (character) => HTML_ESCAPE_MAP[character],
    );
  }

  function rememberPosterMarkup() {
    if (!track) return;

    track.querySelectorAll(".testimonials__card").forEach((card) => {
      const poster = card.querySelector(".testimonials__card-poster");
      if (poster && !card.dataset.posterHtml) {
        card.dataset.posterHtml = poster.innerHTML;
      }
    });
  }

  // Keep server-rendered cards intact; use the JSON path only on legacy pages
  // whose testimonial tracks are still intentionally rendered client-side.
  function renderAllTestimonials() {
    if (!track) return;

    if (track.querySelector(".testimonials__card")) {
      rememberPosterMarkup();
      return;
    }

    let html = "";
    testimonialsData.forEach((item) => {
      if (!item || typeof item !== "object") return;

      const name = escapeHtml(item.name);
      const video = escapeHtml(item.video);
      const thumbnail = escapeHtml(item.thumbnail);
      const category = escapeHtml(item.category);
      const role = escapeHtml(item.role);

      html += `
        <article class="testimonials__card" data-video="${video}">
          <div class="testimonials__card-thumb">
            <div class="testimonials__card-poster">
              <img src="${thumbnail}" alt="${name}" loading="lazy" decoding="async" width="320" height="220" />
              <span class="testimonials__card-badge">${category}</span>
              <button class="testimonials__play" type="button" aria-label="Play video by ${name}">
                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                  <path d="M8 5v14l11-7z"/>
                </svg>
              </button>
            </div>
            <div class="testimonials__card-player"></div>
          </div>
          <div class="testimonials__card-info">
            <h4 class="testimonials__card-name">${name}</h4>
            <span class="testimonials__card-role">${role}</span>
          </div>
        </article>
      `;
    });

    track.innerHTML = html;
    rememberPosterMarkup();
  }

  function testimonialsSection() {
    return document.getElementById("video-testimonials");
  }

  function shouldPlayInline() {
    const section = testimonialsSection();

    return !!(
      section &&
      section.hasAttribute("data-testimonials-inline-desktop") &&
      inlineDesktopQuery.matches
    );
  }

  function parseVideoUrl(videoUrl) {
    const value = String(videoUrl ?? "").trim();
    if (!value) return null;

    try {
      return new URL(value, window.location.origin);
    } catch (_) {
      return null;
    }
  }

  function embedUrl(videoUrl) {
    const rawVideoId = String(videoUrl ?? "").trim();
    if (/^[a-zA-Z0-9_-]{11}$/.test(rawVideoId)) {
      return `https://www.youtube.com/embed/${rawVideoId}?rel=0&autoplay=1`;
    }

    const url = parseVideoUrl(rawVideoId);
    if (!url || !HTTP_PROTOCOLS.has(url.protocol)) return null;

    const hostname = url.hostname.toLowerCase().replace(/^www\./, "");
    if (YOUTUBE_HOSTS.has(hostname)) {
      let videoId = null;

      if (hostname === "youtu.be") {
        videoId = url.pathname.split("/").filter(Boolean)[0] || null;
      } else if (url.pathname === "/watch") {
        videoId = url.searchParams.get("v");
      } else {
        const match = url.pathname.match(/^\/(?:e|embed|v|shorts|live)\/([^/]+)/i);
        videoId = match ? match[1] : null;
      }

      if (videoId && /^[a-zA-Z0-9_-]{11}$/.test(videoId)) {
        return `https://www.youtube.com/embed/${videoId}?rel=0&autoplay=1`;
      }
    }

    if (hostname === "vimeo.com" || hostname === "player.vimeo.com") {
      const match = url.pathname.match(/\/(\d+)\/?$/);
      if (match) {
        return `https://player.vimeo.com/video/${match[1]}?autoplay=1`;
      }
    }

    return null;
  }

  function safeMediaUrl(videoUrl) {
    const url = parseVideoUrl(videoUrl);
    if (!url || !HTTP_PROTOCOLS.has(url.protocol)) return null;

    const isMixedContent =
      window.location.protocol === "https:" &&
      url.protocol === "http:" &&
      url.origin !== window.location.origin;

    return isMixedContent ? null : url.href;
  }

  function playerMarkup(videoUrl) {
    const embedSrc = embedUrl(videoUrl);
    if (embedSrc) {
      return (
        `<iframe src="${escapeHtml(embedSrc)}" title="Student video" ` +
        `allow="autoplay; encrypted-media" allowfullscreen></iframe>`
      );
    }

    const mediaSrc = safeMediaUrl(videoUrl);
    if (mediaSrc) {
      return `<video src="${escapeHtml(mediaSrc)}" controls autoplay playsinline></video>`;
    }

    return "";
  }

  function restorePoster(card) {
    const thumb = card.querySelector(".testimonials__card-thumb");
    if (!thumb || card.querySelector(".testimonials__card-poster") || !card.dataset.posterHtml) {
      return;
    }

    const poster = document.createElement("div");
    poster.className = "testimonials__card-poster";
    poster.innerHTML = card.dataset.posterHtml;
    thumb.prepend(poster);
  }

  function resetInlineCard(card) {
    if (!card) return;

    const player = card.querySelector(".testimonials__card-player");
    const video = player ? player.querySelector("video") : null;
    if (video) {
      video.pause();
      video.removeAttribute("src");
      video.load();
    }
    if (player) {
      player.innerHTML = "";
    }

    restorePoster(card);
    card.classList.remove("is-playing");
  }

  function stopAllInlineExcept(activeCard) {
    document.querySelectorAll(".testimonials__card.is-playing").forEach((card) => {
      if (card !== activeCard) {
        resetInlineCard(card);
      }
    });
  }

  function playInlineCard(card, videoUrl) {
    const player = card.querySelector(".testimonials__card-player");
    if (!player) {
      openModal(videoUrl);
      return;
    }

    const markup = playerMarkup(videoUrl);
    if (!markup) return;

    stopAllInlineExcept(card);
    player.innerHTML = markup;
    card.classList.add("is-playing");

    const video = player.querySelector("video");
    if (video) {
      video.addEventListener("ended", () => resetInlineCard(card));
    }
  }

  // Open modal with a validated YouTube/Vimeo embed or HTTP(S) media source.
  function openModal(videoUrl) {
    if (!modal || !modalPlayer) return;

    const playerContent = playerMarkup(videoUrl);
    if (!playerContent) return;

    modalPlayer.innerHTML = playerContent;
    modal.classList.add("testimonials__modal--active");
    document.body.classList.add("modal-open");
    if (window.lenisInstance && typeof window.lenisInstance.stop === "function") {
      window.lenisInstance.stop();
    }
  }

  // Close modal
  function closeModal() {
    if (!modal || !modalPlayer) return;

    modal.classList.remove("testimonials__modal--active");
    document.body.classList.remove("modal-open");
    if (window.lenisInstance && typeof window.lenisInstance.start === "function") {
      window.lenisInstance.start();
    }

    // Clear player content after transition
    setTimeout(() => {
      modalPlayer.innerHTML = "";
    }, 400);
  }

  // Setup card clicks (event delegation)
  // Capture on document so a drag-scroll row cannot cancel the click
  // before the card starts playing.
  function setupCardClicks() {
    if (!track) return;

    document.addEventListener("click", (e) => {
      if (!track.contains(e.target)) return;

      const card = e.target.closest(".testimonials__card");
      if (!card || card.classList.contains("is-playing")) return;

      const videoUrl = card.getAttribute("data-video");
      if (!videoUrl) return;

      if (shouldPlayInline()) {
        playInlineCard(card, videoUrl);
        return;
      }

      openModal(videoUrl);
    }, true);
  }

  // Setup modal close events
  function setupModalEvents() {
    if (!modal || !modalClose) return;

    // Close button
    modalClose.addEventListener("click", closeModal);

    // ESC key
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && modal.classList.contains("testimonials__modal--active")) {
        closeModal();
      }
    });

    // Click backdrop (but not modal content)
    modal.addEventListener("click", (e) => {
      if (e.target === modal) {
        closeModal();
      }
    });
  }

  // Initialize on DOM ready
  function init() {
    // Render all testimonials
    renderAllTestimonials();

    // Setup event listeners
    setupCardClicks();
    setupModalEvents();
  }

  // Run init when DOM is ready
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
