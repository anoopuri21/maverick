/**
 * Image Slideshow + Lightbox for Our Story page
 * Handles slideshow carousel, category filters, and lightbox
 */

import { respectsReducedMotion } from "../shared/utils.js";

let currentIndex = 0;
let galleryImages = [];
let filteredImages = [];
let autoPlayTimer = null;

/**
 * Initialize slideshow
 * @param {string|HTMLElement} selector - CSS selector or DOM element
 */
export function initGalleryCollage(selector) {
  const section = typeof selector === "string"
    ? document.querySelector(selector)
    : selector;

  if (!section) return;

  const track = section.querySelector("[data-slideshow-track]");
  const slides = section.querySelectorAll(".os-slideshow__slide");
  const prevBtn = section.querySelector("[data-slideshow-prev]");
  const nextBtn = section.querySelector("[data-slideshow-next]");
  const dotsContainer = section.querySelector("[data-slideshow-dots]");
  const filterBtns = section.querySelectorAll(".os-slideshow__filter");
  const lightbox = document.querySelector("#os-lightbox");
  const lightboxImg = document.querySelector("#os-lightbox-img");
  const lightboxCaption = document.querySelector("#os-lightbox-caption");

  if (!track || slides.length === 0) return;

  // Collect images
  slides.forEach((slide, i) => {
    const img = slide.querySelector(".os-slideshow__img");
    const captionEl = slide.querySelector(".os-slideshow__caption-text");
    if (img) {
      galleryImages.push({
        src: img.src,
        alt: img.alt,
        caption: captionEl ? captionEl.textContent : "",
        category: slide.dataset.category || "all",
        element: slide
      });
    }
  });

  filteredImages = [...galleryImages];

  // Create dots
  if (dotsContainer) {
    filteredImages.forEach((_, i) => {
      const dot = document.createElement("span");
      dot.className = `os-slideshow__dot ${i === 0 ? 'os-slideshow__dot--active' : ''}`;
      dot.addEventListener("click", () => goToSlide(i));
      dotsContainer.appendChild(dot);
    });
  }

  // Navigation
  if (prevBtn) prevBtn.addEventListener("click", () => goToSlide(currentIndex - 1));
  if (nextBtn) nextBtn.addEventListener("click", () => goToSlide(currentIndex + 1));

  // Category filters
  filterBtns.forEach(btn => {
    btn.addEventListener("click", () => {
      const filter = btn.dataset.filter;

      // Update active state
      filterBtns.forEach(b => b.classList.remove("os-slideshow__filter--active"));
      btn.classList.add("os-slideshow__filter--active");

      // Filter slides
      if (filter === "all") {
        filteredImages = [...galleryImages];
      } else {
        filteredImages = galleryImages.filter(img => img.category === filter);
      }

      // Update visibility
      galleryImages.forEach(img => {
        if (filter === "all" || img.category === filter) {
          img.element.style.display = "";
        } else {
          img.element.style.display = "none";
        }
      });

      // Reset to first slide
      currentIndex = 0;
      updateSlideshow();
      updateDots();
    });
  });

  // Auto-play
  startAutoPlay();

  // Pause on hover
  section.addEventListener("mouseenter", stopAutoPlay);
  section.addEventListener("mouseleave", startAutoPlay);

  // Lightbox
  if (lightbox && lightboxImg) {
    slides.forEach((slide, i) => {
      slide.addEventListener("click", () => openLightbox(i));
    });

    const closeBtn = lightbox.querySelector("[data-lightbox-close]");
    const prevLightboxBtn = lightbox.querySelector("[data-lightbox-prev]");
    const nextLightboxBtn = lightbox.querySelector("[data-lightbox-next]");

    if (closeBtn) closeBtn.addEventListener("click", closeLightbox);
    if (prevLightboxBtn) prevLightboxBtn.addEventListener("click", () => navigateLightbox(-1));
    if (nextLightboxBtn) nextLightboxBtn.addEventListener("click", () => navigateLightbox(1));

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

function goToSlide(index) {
  const total = filteredImages.length;
  currentIndex = ((index % total) + total) % total;
  updateSlideshow();
  updateDots();
}

function updateSlideshow() {
  const track = document.querySelector("[data-slideshow-track]");
  if (!track) return;

  // Find the visible index
  const visibleSlides = galleryImages.filter(img =>
    img.element.style.display !== "none"
  );
  const visibleIndex = visibleSlides.indexOf(filteredImages[currentIndex]);

  if (visibleIndex >= 0) {
    track.style.transform = `translateX(-${visibleIndex * 100}%)`;
  }
}

function updateDots() {
  const dots = document.querySelectorAll(".os-slideshow__dot");
  dots.forEach((dot, i) => {
    dot.classList.toggle("os-slideshow__dot--active", i === currentIndex);
  });
}

function startAutoPlay() {
  stopAutoPlay();
  autoPlayTimer = setInterval(() => {
    goToSlide(currentIndex + 1);
  }, 5000);
}

function stopAutoPlay() {
  if (autoPlayTimer) {
    clearInterval(autoPlayTimer);
    autoPlayTimer = null;
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
    stopAutoPlay();
  }
}

function closeLightbox() {
  const lightbox = document.querySelector("#os-lightbox");
  if (lightbox) {
    lightbox.classList.remove("is-open");
    lightbox.setAttribute("aria-hidden", "true");
    document.body.style.overflow = "";
    startAutoPlay();
  }
}

function navigateLightbox(direction) {
  currentIndex = (currentIndex + direction + filteredImages.length) % filteredImages.length;
  updateLightbox();
}

function updateLightbox() {
  const lightboxImg = document.querySelector("#os-lightbox-img");
  const lightboxCaption = document.querySelector("#os-lightbox-caption");

  if (!filteredImages[currentIndex]) return;

  lightboxImg.src = filteredImages[currentIndex].src;
  lightboxImg.alt = filteredImages[currentIndex].alt;
  if (lightboxCaption) lightboxCaption.textContent = filteredImages[currentIndex].caption;
}

/**
 * Default export for lazy loading
 */
export default function init(element) {
  initGalleryCollage(element);
}
