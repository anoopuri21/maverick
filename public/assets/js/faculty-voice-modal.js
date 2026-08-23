(function () {
  "use strict";

  const modal = document.getElementById("facultyVoiceModal");
  if (!modal) return;

  const backdrop = modal.querySelector(".fv-modal__backdrop");
  const dialog = modal.querySelector(".fv-modal__dialog");
  const closeBtn = modal.querySelector(".fv-modal__close");
  const avatarEl = modal.querySelector(".fv-modal__avatar");
  const badgeEl = modal.querySelector(".fv-modal__badge");
  const facultyNameEl = modal.querySelector(".fv-modal__faculty-name");
  const facultyRoleEl = modal.querySelector(".fv-modal__faculty-role");
  const titleEl = modal.querySelector(".fv-modal__title");
  const quoteEl = modal.querySelector(".fv-modal__quote");
  const bodyEl = modal.querySelector(".fv-modal__body");
  const heroWrap = modal.querySelector(".fv-modal__hero");
  const heroImg = modal.querySelector(".fv-modal__hero-img");

  let lastFocused = null;
  const focusableSelector =
    'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])';

  function detectTruncation() {
    document.querySelectorAll("[data-fv-card]").forEach((card) => {
      const excerpt = card.querySelector(".insights__card-excerpt");
      const btn = card.querySelector("[data-fv-open]");
      const template = card.querySelector("[data-fv-content]");
      if (!btn) return;

      if (!excerpt) {
        const templateText = template?.content?.textContent?.trim() || "";
        btn.hidden = templateText.length === 0;
        return;
      }

      const previewText = excerpt.textContent.trim();
      const templateText = template?.content?.textContent?.trim() || "";
      const isTruncated = excerpt.scrollHeight > excerpt.clientHeight + 2;
      const hasMoreContent = templateText.length > previewText.length + 20;

      btn.hidden = !(isTruncated || hasMoreContent);
    });
  }

  function lockScroll() {
    document.body.classList.add("modal-open");
    if (window.lenisInstance && typeof window.lenisInstance.stop === "function") {
      window.lenisInstance.stop();
    }
  }

  function unlockScroll() {
    document.body.classList.remove("modal-open");
    if (window.lenisInstance && typeof window.lenisInstance.start === "function") {
      window.lenisInstance.start();
    }
  }

  function getFocusableElements() {
    return Array.from(dialog.querySelectorAll(focusableSelector)).filter(
      (el) => !el.hasAttribute("disabled") && el.offsetParent !== null
    );
  }

  function trapFocus(e) {
    if (e.key !== "Tab" || modal.hidden) return;

    const focusable = getFocusableElements();
    if (!focusable.length) return;

    const first = focusable[0];
    const last = focusable[focusable.length - 1];

    if (e.shiftKey && document.activeElement === first) {
      e.preventDefault();
      last.focus();
    } else if (!e.shiftKey && document.activeElement === last) {
      e.preventDefault();
      first.focus();
    }
  }

  function openModal(card) {
    const template = card.querySelector("[data-fv-content]");
    if (!template) return;

    lastFocused = document.activeElement;

    const title = card.dataset.fvTitle || "";
    const badge = card.dataset.fvBadge || "";
    const facultyName = card.dataset.fvFacultyName || "";
    const facultyRole = card.dataset.fvFacultyRole || "";
    const featured =
      card.dataset.fvImage ||
      card.querySelector(".insights__card-image img")?.src ||
      "";
    const avatar =
      card.dataset.fvAvatar ||
      card.querySelector(".insights__card-avatar img")?.src ||
      "";

    titleEl.textContent = title;

    if (featured && heroWrap && heroImg) {
      heroImg.src = featured;
      heroImg.alt = title;
      heroWrap.hidden = false;
    } else if (heroWrap && heroImg) {
      heroWrap.hidden = true;
      heroImg.removeAttribute("src");
    }

    if (badge) {
      badgeEl.textContent = badge;
      badgeEl.hidden = false;
    } else {
      badgeEl.hidden = true;
    }

    facultyNameEl.textContent = facultyName;
    facultyRoleEl.textContent = facultyRole;

    if (avatar) {
      avatarEl.src = avatar;
      avatarEl.alt = facultyName;
      avatarEl.hidden = false;
    } else {
      avatarEl.hidden = true;
      avatarEl.removeAttribute("src");
    }

    bodyEl.innerHTML = "";
    const clone = template.content.cloneNode(true);
    const quoteSource = clone.querySelector(".fv-modal__quote-source");
    if (quoteSource) {
      quoteEl.textContent = quoteSource.textContent.trim();
      quoteEl.hidden = false;
      quoteSource.remove();
    } else {
      quoteEl.hidden = true;
      quoteEl.textContent = "";
    }
    bodyEl.appendChild(clone);

    modal.hidden = false;
    requestAnimationFrame(() => {
      modal.classList.add("is-open");
    });

    lockScroll();
    closeBtn.focus();
  }

  function closeModal() {
    modal.classList.remove("is-open");

    const onEnd = () => {
      modal.hidden = true;
      bodyEl.innerHTML = "";
      unlockScroll();
      if (lastFocused && typeof lastFocused.focus === "function") {
        lastFocused.focus();
      }
      lastFocused = null;
    };

    const prefersReduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    if (prefersReduced) {
      onEnd();
      return;
    }

    const target = dialog;
    const handler = (e) => {
      if (e.target === target && e.propertyName === "transform") {
        target.removeEventListener("transitionend", handler);
        onEnd();
      }
    };
    target.addEventListener("transitionend", handler);
    setTimeout(onEnd, 400);
  }

  function init() {
    detectTruncation();

    document.addEventListener("click", (e) => {
      const openBtn = e.target.closest("[data-fv-open]");
      if (openBtn) {
        e.preventDefault();
        e.stopPropagation();
        const card = openBtn.closest("[data-fv-card]");
        if (card) openModal(card);
        return;
      }

      if (e.target.closest("[data-fv-close]")) {
        e.preventDefault();
        closeModal();
      }
    });

    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && !modal.hidden) {
        closeModal();
      }
      trapFocus(e);
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }

  window.addEventListener("resize", detectTruncation, { passive: true });
})();
