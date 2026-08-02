/**
 * Gallery collage + lightbox animations
 * Stagger reveal for gallery items and full lightbox functionality
 */

import { respectsReducedMotion } from "../shared/utils.js";

let currentIndex = 0;
let galleryImages = [];

/**
 * Initialize gallery animations
 * @param {string|HTMLElement} selector - CSS selector or DOM element (should be #gallery)
 */
export function initGalleryCollage(selector) {
  const section = typeof selector === "string"
    ? document.querySelector(selector)
    : selector;

  if (!section) return;

  const grid = section.querySelector("[data-gallery-grid]");
  const items = section.querySelectorAll("[data-gallery-item]");
  const lightbox = document.querySelector("#os-lightbox");
  const lightboxImg = document.querySelector("#os-lightbox-img");
  const lightboxCaption = document.querySelector("#os-lightbox-caption");

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

  // Setup lightbox
  if (!lightbox || !lightboxImg) return;

  // Collect gallery images
  items.forEach((item, i) => {
    const img = item.querySelector(".os-gallery__img");
    const captionEl = item.querySelector(".os-gallery__caption-text");
    if (img) {
      galleryImages.push({
        src: img.src,
        alt: img.alt,
        caption: captionEl ? captionEl.textContent : "",
      });
      item.addEventListener("click", () => openLightbox(i));
    }
  });

  // Lightbox controls
  const closeBtn = lightbox.querySelector("[data-lightbox-close]");
  const prevBtn = lightbox.querySelector("[data-lightbox-prev]");
  const nextBtn = lightbox.querySelector("[data-lightbox-next]");

  if (closeBtn) closeBtn.addEventListener("click", closeLightbox);
  if (prevBtn) prevBtn.addEventListener("click", () => navigateLightbox(-1));
  if (nextBtn) nextBtn.addEventListener("click", () => navigateLightbox(1));

  // Click outside to close
  lightbox.addEventListener("click", (e) => {
    if (e.target === lightbox) closeLightbox();
  });

  // Keyboard navigation
  document.addEventListener("keydown", (e) => {
    if (!lightbox.classList.contains("is-open")) return;
    if (e.key === "Escape") closeLightbox();
    if (e.key === "ArrowLeft" && prevBtn) navigateLightbox(-1);
    if (e.key === "ArrowRight" && nextBtn) navigateLightbox(1);
  });
}

function openLightbox(index) {
  currentIndex = index;
  updateLightbox();
  const lightbox = document.querySelector("#os-lightbox");
  lightbox.classList.add("is-open");
  lightbox.setAttribute("aria-hidden", "false");
  document.body.style.overflow = "hidden";
}

function closeLightbox() {
  const lightbox = document.querySelector("#os-lightbox");
  lightbox.classList.remove("is-open");
  lightbox.setAttribute("aria-hidden", "true");
  document.body.style.overflow = "";
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

/**
 * Default export for lazy loading
 */
export default function init(element) {
  initGalleryCollage(element);
}
