(function () {
  "use strict";

  // Testimonials data - flattened array with category badges
  const testimonialsData = window.testimonialsData || [];

  // DOM elements
  const track = document.getElementById("testimonialsTrack");
  const modal = document.getElementById("videoModal");
  const modalClose = document.getElementById("modalClose");
  const modalPlayer = document.getElementById("modalPlayer");
  const inlineDesktopQuery = window.matchMedia("(min-width: 1024px)");

  // Render all testimonials
  function renderAllTestimonials() {
    if (!track) return;

    let html = "";
    testimonialsData.forEach((item) => {
      html += `
        <article class="testimonials__card" data-video="${item.video}">
          <div class="testimonials__card-thumb">
            <div class="testimonials__card-poster">
              <img src="${item.thumbnail}" alt="${item.name}" loading="lazy" decoding="async" width="320" height="220" />
              <span class="testimonials__card-badge">${item.category}</span>
              <button class="testimonials__play" type="button" aria-label="Play video by ${item.name}">
                <svg viewBox="0 0 24 24" fill="currentColor">
                  <path d="M8 5v14l11-7z"/>
                </svg>
              </button>
            </div>
            <div class="testimonials__card-player"></div>
          </div>
          <div class="testimonials__card-info">
            <h4 class="testimonials__card-name">${item.name}</h4>
            <span class="testimonials__card-role">${item.role}</span>
          </div>
        </article>
      `;
    });

    track.innerHTML = html;

    track.querySelectorAll(".testimonials__card").forEach((card) => {
      const poster = card.querySelector(".testimonials__card-poster");
      if (poster) {
        card.dataset.posterHtml = poster.innerHTML;
      }
    });
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

  // Build a playable embed URL from any YouTube shape (watch, youtu.be,
  // shorts, live, /embed/, raw id) so the popup always plays, even if a
  // server-provided URL was not already in /embed/ form.
  function youtubeEmbedSrc(videoUrl) {
    const idMatch = videoUrl.match(
      /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts|live)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i
    );
    const id = idMatch ? idMatch[1] : (/^[a-zA-Z0-9_-]{11}$/.test(videoUrl) ? videoUrl : null);

    if (id) {
      return "https://www.youtube.com/embed/" + id + "?rel=0&autoplay=1";
    }

    const separator = videoUrl.includes("?") ? "&" : "?";

    return videoUrl + separator + "autoplay=1";
  }

  function isEmbedUrl(videoUrl) {
    return /youtube\.com|youtu\.be|vimeo\.com/i.test(videoUrl);
  }

  function playerMarkup(videoUrl) {
    if (isEmbedUrl(videoUrl)) {
      return `<iframe src="${youtubeEmbedSrc(videoUrl)}" title="Student video" allow="autoplay; encrypted-media" allowfullscreen></iframe>`;
    }

    return `<video src="${videoUrl}" controls autoplay playsinline></video>`;
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

    stopAllInlineExcept(card);
    player.innerHTML = playerMarkup(videoUrl);
    card.classList.add("is-playing");

    const video = player.querySelector("video");
    if (video) {
      video.addEventListener("ended", () => resetInlineCard(card));
    }
  }

  // Open modal with video
  function openModal(videoUrl) {
    if (!modal || !modalPlayer) return;

    // Detect YouTube vs local video
    let playerContent;
    if (videoUrl.includes("youtube.com") || videoUrl.includes("youtu.be")) {
      playerContent = `<iframe src="${youtubeEmbedSrc(videoUrl)}" allow="autoplay; encrypted-media" allowfullscreen></iframe>`;
    } else {
      playerContent = `<video src="${videoUrl}" controls autoplay></video>`;
    }

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
