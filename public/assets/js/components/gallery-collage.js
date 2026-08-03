/**
 * Gallery Section — Zig-Zag Auto-Sliding Carousel
 * Uses CSS animation for auto-sliding, JS only for lightbox
 */

import { respectsReducedMotion } from "../shared/utils.js";

let galleryImages = [];
let currentIndex = 0;

/**
 * Initialize gallery
 * @param {string|HTMLElement} selector
 */
export function initGalleryCollage(selector) {
  const section = typeof selector === "string"
    ? document.querySelector(selector)
    : selector;

  if (!section) return;

  const cards = section.querySelectorAll(".os-gallery__card");
  const lightbox = document.querySelector("#os-lightbox");
  const lightboxImg = document.querySelector("#os-lightbox-img");
  const lightboxCaption = document.querySelector("#os-lightbox-caption");

  if (!cards.length) return;

  // Collect unique images (not duplicates)
  const seen = new Set();
  cards.forEach((card, i) => {
    const img = card.querySelector(".os-gallery__img");
    const captionEl = card.querySelector(".os-gallery__caption-text");
    if (img && !seen.has(img.src)) {
      seen.add(img.src);
      galleryImages.push({
        src: img.src,
        alt: img.alt,
        caption: captionEl ? captionEl.textContent : "",
      });
      card.addEventListener("click", () => openLightbox(galleryImages.length - 1));
    }
  });

  // Setup lightbox
  if (lightbox && lightboxImg) {
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
