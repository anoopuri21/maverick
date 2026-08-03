/**
 * Gallery Section — Draggable Zig-Zag Carousel
 * Supports drag to scroll, touch devices, and lightbox
 */

import { respectsReducedMotion } from "../shared/utils.js";

let galleryImages = [];
let currentIndex = 0;

/**
 * Initialize gallery with drag functionality
 * @param {string|HTMLElement} selector
 */
export function initGalleryCollage(selector) {
  const section = typeof selector === "string"
    ? document.querySelector(selector)
    : selector;

  if (!section) return;

  const carousel = section.querySelector("[data-gallery-carousel]");
  const track = section.querySelector("[data-gallery-track]");
  const cards = section.querySelectorAll("[data-gallery-card]");
  const lightbox = document.querySelector("#os-lightbox");

  if (respectsReducedMotion()) return;

  // Stagger reveal for gallery items
  if (items.length) {
    gsap.fromTo(
      items,
      { opacity: 0, y: 30 },
      {
        opacity: 1,
        y: 0,
        duration: 0.6,
        stagger: 0.08,
        ease: "power2.out",
        scrollTrigger: {
          trigger: grid,
          start: "top 80%",
          once: true,
        },
      },
    );
  }

  function startAutoSlide() {
    if (!isAutoSliding) {
      isAutoSliding = true;
      autoSlide();
    }
  }

  function stopAutoSlide() {
    isAutoSliding = false;
    if (animationId) {
      cancelAnimationFrame(animationId);
      animationId = null;
    }
  }

  // Mouse events
  carousel.addEventListener("mousedown", (e) => {
    isDragging = true;
    carousel.classList.add("is-dragging");
    startX = e.pageX;
    prevTranslate = currentTranslate;
    stopAutoSlide();
  });

  carousel.addEventListener("mousemove", (e) => {
    if (!isDragging) return;
    e.preventDefault();
    const x = e.pageX;
    const walk = (x - startX) * 1.5; // Multiply for faster drag
    currentTranslate = prevTranslate + walk;
    track.style.transform = `translateX(${currentTranslate}px)`;
  });

  carousel.addEventListener("mouseup", () => {
    isDragging = false;
    carousel.classList.remove("is-dragging");
    startAutoSlide();
  });

  carousel.addEventListener("mouseleave", () => {
    if (isDragging) {
      isDragging = false;
      carousel.classList.remove("is-dragging");
      startAutoSlide();
    }
  });

  // Touch events for mobile
  carousel.addEventListener("touchstart", (e) => {
    isDragging = true;
    startX = e.touches[0].pageX;
    prevTranslate = currentTranslate;
    stopAutoSlide();
  }, { passive: true });

  carousel.addEventListener("touchmove", (e) => {
    if (!isDragging) return;
    const x = e.touches[0].pageX;
    const walk = (x - startX) * 1.5;
    currentTranslate = prevTranslate + walk;
    track.style.transform = `translateX(${currentTranslate}px)`;
  }, { passive: true });

  carousel.addEventListener("touchend", () => {
    isDragging = false;
    startAutoSlide();
  });

  // Pause auto-slide on hover
  carousel.addEventListener("mouseenter", stopAutoSlide);
  carousel.addEventListener("mouseleave", () => {
    if (!isDragging) startAutoSlide();
  });

  // Start auto-slide
  autoSlide();

  // ── 2. Lightbox ────────────────────────────────────────────
  const seen = new Set();
  cards.forEach((card) => {
    const img = card.querySelector(".os-gallery__img");
    const captionEl = card.querySelector(".os-gallery__caption-text");
    if (img && !seen.has(img.src)) {
      seen.add(img.src);
      galleryImages.push({
        src: img.src,
        alt: img.alt,
        caption: captionEl ? captionEl.textContent : "",
      });
      card.addEventListener("click", (e) => {
        if (!isDragging) {
          openLightbox(galleryImages.length - 1);
        }
      });
    }
  });

  if (lightbox) {
    const closeBtn = lightbox.querySelector("[data-lightbox-close]");
    const prevBtn = lightbox.querySelector("[data-lightbox-prev]");
    const nextBtn = lightbox.querySelector("[data-lightbox-next]");

    if (closeBtn) closeBtn.addEventListener("click", closeLightbox);
    if (prevBtn) prevBtn.addEventListener("click", () => navigateLightbox(-1));
    if (nextBtn) nextBtn.addEventListener("click", () => navigateLightbox(1));

    lightbox.addEventListener("click", (e) => {
      if (e.target === lightbox) closeLightbox();
    });

    document.addEventListener("keydown", (e) => {
      if (!lightbox.classList.contains("is-open")) return;
      if (e.key === "Escape") closeLightbox();
      if (e.key === "ArrowLeft") navigateLightbox(-1);
      if (e.key === "ArrowRight") navigateLightbox(1);
    });
  }
}

function openLightbox(index) {
  currentIndex = index;
  updateLightbox();
  const lightbox = document.querySelector("#os-lightbox");
  if (lightbox) {
    lightbox.classList.add("is-open");
    lightbox.setAttribute("aria-hidden", "false");
    document.body.style.overflow = "hidden";
  }
}

function closeLightbox() {
  const lightbox = document.querySelector("#os-lightbox");
  if (lightbox) {
    lightbox.classList.remove("is-open");
    lightbox.setAttribute("aria-hidden", "true");
    document.body.style.overflow = "";
  }
}

function navigateLightbox(direction) {
  currentIndex = (currentIndex + direction + galleryImages.length) % galleryImages.length;
  updateLightbox();
}

function updateLightbox() {
  if (!galleryImages[currentIndex]) return;
  const lightboxImg = document.querySelector("#os-lightbox-img");
  const lightboxCaption = document.querySelector("#os-lightbox-caption");
  lightboxImg.src = galleryImages[currentIndex].src;
  lightboxImg.alt = galleryImages[currentIndex].alt;
  if (lightboxCaption) lightboxCaption.textContent = galleryImages[currentIndex].caption;
}

export default function init(element) {
  initGalleryCollage(element);
}
