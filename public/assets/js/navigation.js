/* =====================================================================
   Navigation Controller
   Handles: scroll behavior, active link, mega menu, dropdowns,
   mobile menu, mobile accordions (top-level + nested categories).
   ===================================================================== */

(function () {
  "use strict";

  const navbar = document.getElementById("navbar");
  if (!navbar) {
    console.warn("Navigation: #navbar element not found.");
    return;
  }

  // ---------------------------------------------------------------------
  // Config
  // ---------------------------------------------------------------------
  const CONFIG = {
    DESKTOP_BREAKPOINT: 1024,
    SCROLLED_THRESHOLD: 80,
    HIDE_THRESHOLD: 200,
    CLOSE_DELAY: 150,
  };

  // ---------------------------------------------------------------------
  // Cached DOM references
  // ---------------------------------------------------------------------
  const dom = {
    navLinks: navbar.querySelectorAll(".navbar__link[href]"),

    megaItem: navbar.querySelector(".navbar__item--has-mega"),
    megaTrigger: navbar.querySelector('[data-menu="programs"]'),
    megaPanel: navbar.querySelector('.navbar__mega[data-mega="programs"]'),

    dropdownItems: navbar.querySelectorAll(".navbar__item--has-dropdown"),

    hamburger: navbar.querySelector(".navbar__hamburger"),
    mobileMenu: navbar.querySelector(".navbar__mobile"),
    mobileTriggers: navbar.querySelectorAll(".navbar__mobile-trigger"),
  };

  dom.megaBackdrop = dom.megaPanel
    ? dom.megaPanel.querySelector(".mega__backdrop")
    : null;

  // ---------------------------------------------------------------------
  // Shared state
  // ---------------------------------------------------------------------
  const state = {
    lastScrollY: window.scrollY || 0,
    ticking: false,
    isMobileMenuOpen: false,
    megaCloseTimeout: null,
    dropdownCloseTimeouts: new Map(),
  };

  // ---------------------------------------------------------------------
  // Utilities
  // ---------------------------------------------------------------------
  const isDesktop = () => window.innerWidth >= CONFIG.DESKTOP_BREAKPOINT;

  function rafThrottle(fn) {
    let queued = false;
    return function (...args) {
      if (queued) return;
      queued = true;
      requestAnimationFrame(() => {
        fn.apply(this, args);
        queued = false;
      });
    };
  }

  // =====================================================================
  // 1. SCROLL BEHAVIOR — is-scrolled + hide/show on direction
  // =====================================================================

  const ScrollController = (() => {
    function isAnyMenuOpen() {
      return !!navbar.querySelector(
        ".navbar__item--has-mega.is-active, .navbar__item--has-dropdown.is-active",
      );
    }

    function update() {
      const currentScrollY = window.scrollY || 0;

      navbar.classList.toggle(
        "is-scrolled",
        currentScrollY > CONFIG.SCROLLED_THRESHOLD,
      );

      const menuOpen = isAnyMenuOpen();
      const shouldHide =
        currentScrollY > CONFIG.HIDE_THRESHOLD &&
        !menuOpen &&
        !state.isMobileMenuOpen;

      if (shouldHide) {
        navbar.classList.toggle(
          "is-hidden",
          currentScrollY > state.lastScrollY,
        );
      } else {
        navbar.classList.remove("is-hidden");
      }

      state.lastScrollY = currentScrollY <= 0 ? 0 : currentScrollY;
    }

    const onScroll = rafThrottle(update);

    function init() {
      update();
      window.addEventListener("scroll", onScroll, { passive: true });
    }

    return { init, update };
  })();

  // =====================================================================
  // 2. ACTIVE NAV LINK
  // =====================================================================

  const ActiveLinkController = (() => {
    function init() {
      if (!dom.navLinks.length) return;

      const currentPath = window.location.pathname.replace(/\/$/, "") || "/";
      let matched = false;

      dom.navLinks.forEach((link) => {
        link.classList.remove("is-current");
        try {
          const linkUrl = new URL(
            link.getAttribute("href"),
            window.location.origin,
          );
          const linkPath = linkUrl.pathname.replace(/\/$/, "") || "/";
          const isMatch =
            linkPath === currentPath ||
            (linkPath !== "/" && currentPath.startsWith(linkPath + "/"));

          if (isMatch) {
            link.classList.add("is-current");
            matched = true;
          }
        } catch (e) {
          /* invalid URL — ignore */
        }
      });

      if (
        !matched &&
        (currentPath === "/" || currentPath.endsWith("index.html"))
      ) {
        const firstTopLink = navbar.querySelector(
          ".navbar__menu > .navbar__item > a.navbar__link",
        );
        if (firstTopLink) firstTopLink.classList.add("is-current");
      }
    }

    return { init };
  })();

  // =====================================================================
  // 3. MEGA MENU (Programs) — open/close + GSAP entrance
  // =====================================================================

  const MegaMenuController = (() => {
    const { megaItem, megaTrigger, megaPanel, megaBackdrop } = dom;

    function animateIn() {
      if (typeof gsap === "undefined" || !megaPanel) return;

      const categories = megaPanel.querySelectorAll(".mega__category-item");
      const activeRows = megaPanel.querySelectorAll(
        ".mega__panel-list.is-active .mega__program-row",
      );

      gsap.killTweensOf([categories, activeRows]);

      if (categories.length) {
        gsap.fromTo(
          categories,
          { opacity: 0, x: -8 },
          {
            opacity: 1,
            x: 0,
            duration: 0.4,
            stagger: 0.04,
            ease: "power2.out",
            overwrite: true,
          },
        );
      }

      if (activeRows.length) {
        gsap.fromTo(
          activeRows,
          { opacity: 0, y: 10 },
          {
            opacity: 1,
            y: 0,
            duration: 0.35,
            stagger: 0.03,
            delay: 0.1,
            ease: "power2.out",
            overwrite: true,
          },
        );
      }
    }

    function open() {
      if (!megaItem || !megaPanel) return;

      clearTimeout(state.megaCloseTimeout);
      state.megaCloseTimeout = null;

      DropdownController.closeAll(true);

      megaItem.classList.add("is-active");
      megaPanel.classList.add("is-open");
      megaPanel.setAttribute("aria-hidden", "false");
      if (megaTrigger) megaTrigger.setAttribute("aria-expanded", "true");

      animateIn();
    }

    function close(immediate = false) {
      if (!megaItem || !megaPanel) return;

      const doClose = () => {
        megaItem.classList.remove("is-active");
        megaPanel.classList.remove("is-open");
        megaPanel.setAttribute("aria-hidden", "true");
        if (megaTrigger) megaTrigger.setAttribute("aria-expanded", "false");
        state.megaCloseTimeout = null;
      };

      clearTimeout(state.megaCloseTimeout);

      if (immediate) {
        doClose();
      } else {
        state.megaCloseTimeout = setTimeout(doClose, CONFIG.CLOSE_DELAY);
      }
    }

    function isOpen() {
      return !!megaPanel && megaPanel.classList.contains("is-open");
    }

    function init() {
      if (!megaItem || !megaPanel || !megaTrigger) return;

      megaItem.addEventListener("mouseenter", () => {
        if (isDesktop()) open();
      });

      megaItem.addEventListener("mouseleave", () => {
        if (isDesktop()) close(false);
      });

      megaTrigger.addEventListener("click", (e) => {
        e.preventDefault();
        e.stopPropagation();
        isOpen() ? close(true) : open();
      });

      if (megaBackdrop) {
        megaBackdrop.addEventListener("click", () => close(true));
      }
    }

    return { init, open, close, isOpen };
  })();

  // =====================================================================
  // 4. DROPDOWN MENUS (About Us, Global Pathways, Insights)
  // =====================================================================

  const DropdownController = (() => {
    const { dropdownItems } = dom;

    function syncAccessibility(item) {
      const trigger = item.querySelector(".navbar__link--trigger");
      const dropdown = item.querySelector(".navbar__dropdown");
      if (!trigger || !dropdown) return;
      const isExpanded = trigger.getAttribute("aria-expanded") === "true";
      dropdown.setAttribute("aria-hidden", isExpanded ? "false" : "true");
    }

    function open(item) {
      const trigger = item.querySelector(".navbar__link--trigger");
      const dropdown = item.querySelector(".navbar__dropdown");

      const existing = state.dropdownCloseTimeouts.get(item);
      if (existing) {
        clearTimeout(existing);
        state.dropdownCloseTimeouts.delete(item);
      }

      MegaMenuController.close(true);

      dropdownItems.forEach((other) => {
        if (other !== item) close(other, true);
      });

      item.classList.add("is-active");
      if (trigger) trigger.setAttribute("aria-expanded", "true");
      if (dropdown) dropdown.setAttribute("aria-hidden", "false");
      syncAccessibility(item);
    }

    function close(item, immediate = false) {
      const trigger = item.querySelector(".navbar__link--trigger");
      const dropdown = item.querySelector(".navbar__dropdown");

      const doClose = () => {
        item.classList.remove("is-active");
        if (trigger) trigger.setAttribute("aria-expanded", "false");
        if (dropdown) dropdown.setAttribute("aria-hidden", "true");
        state.dropdownCloseTimeouts.delete(item);
        syncAccessibility(item);
      };

      const existing = state.dropdownCloseTimeouts.get(item);
      if (existing) clearTimeout(existing);

      if (immediate) {
        doClose();
      } else {
        state.dropdownCloseTimeouts.set(
          item,
          setTimeout(doClose, CONFIG.CLOSE_DELAY),
        );
      }
    }

    function closeAll(immediate = false) {
      dropdownItems.forEach((item) => close(item, immediate));
    }

    function init() {
      dropdownItems.forEach((item) => {
        const trigger = item.querySelector(".navbar__link--trigger");
        if (!trigger) return;

        item.addEventListener("mouseenter", () => {
          if (isDesktop()) open(item);
        });

        item.addEventListener("mouseleave", () => {
          if (isDesktop()) close(item, false);
        });

        trigger.addEventListener("click", (e) => {
          e.preventDefault();
          e.stopPropagation();
          item.classList.contains("is-active") ? close(item, true) : open(item);
        });
      });
    }

    return { init, open, close, closeAll };
  })();

  // =====================================================================
  // 5. GLOBAL MENU CLOSE — outside click / Escape key
  // =====================================================================

  const GlobalCloseController = (() => {
    function closeAllMenus(immediate = true) {
      MegaMenuController.close(immediate);
      DropdownController.closeAll(immediate);
    }

    function init() {
      document.addEventListener("click", (e) => {
        if (navbar.contains(e.target)) return;
        closeAllMenus(true);
      });

      document.addEventListener("keydown", (e) => {
        if (e.key === "Escape" || e.key === "Esc") {
          if (state.isMobileMenuOpen) {
            MobileMenuController.close();
          } else {
            closeAllMenus(true);
          }
        }
      });
    }

    return { init, closeAllMenus };
  })();

  // =====================================================================
  // 6. MOBILE MENU (hamburger overlay)
  // =====================================================================

  const MobileMenuController = (() => {
    const { hamburger, mobileMenu } = dom;

    function toggleLenis(action) {
      const lenis = window.lenisInstance;
      if (lenis && typeof lenis[action] === "function") {
        lenis[action]();
      }
    }

    function open() {
      if (!hamburger || !mobileMenu) return;
      state.isMobileMenuOpen = true;

      hamburger.classList.add("is-active");
      hamburger.setAttribute("aria-expanded", "true");
      hamburger.setAttribute("aria-label", "Close menu");

      mobileMenu.classList.add("is-open");
      mobileMenu.setAttribute("aria-hidden", "false");

      navbar.classList.add("is-menu-open");
      navbar.classList.remove("is-hidden");
      document.body.classList.add("no-scroll");

      toggleLenis("stop");
    }

    function close() {
      if (!hamburger || !mobileMenu) return;
      state.isMobileMenuOpen = false;

      hamburger.classList.remove("is-active");
      hamburger.setAttribute("aria-expanded", "false");
      hamburger.setAttribute("aria-label", "Toggle mobile menu");

      mobileMenu.classList.remove("is-open");
      mobileMenu.setAttribute("aria-hidden", "true");

      navbar.classList.remove("is-menu-open");
      document.body.classList.remove("no-scroll");

      MobileAccordionController.closeAllTopLevel();
      MobileProgramsAccordionController.closeAll();

      toggleLenis("start");
    }

    function toggle() {
      state.isMobileMenuOpen ? close() : open();
    }

    function init() {
      if (!hamburger || !mobileMenu) return;

      hamburger.addEventListener("click", (e) => {
        e.stopPropagation();
        toggle();
      });

      // Any real navigation link inside the mobile overlay should close it.
      const closingLinkSelector = [
        "a.navbar__mobile-link",
        "a.navbar__mobile-sublink",
        "a.navbar__mobile-category-sublink",
      ].join(", ");

      mobileMenu.querySelectorAll(closingLinkSelector).forEach((link) => {
        link.addEventListener("click", () => {
          setTimeout(close, CONFIG.CLOSE_DELAY);
        });
      });
    }

    return { init, open, close, toggle };
  })();

  // =====================================================================
  // 7. MOBILE ACCORDION — top-level menus (Programs / About / Pathways...)
  // =====================================================================

  const MobileAccordionController = (() => {
    const { mobileTriggers } = dom;

    function getSubmenu(trigger) {
      const key = trigger.getAttribute("data-mobile-menu");
      if (!key) return null;
      return navbar.querySelector(
        `.navbar__mobile-submenu[data-mobile-submenu="${key}"]`,
      );
    }

    function closeAllTopLevel(exceptTrigger = null) {
      mobileTriggers.forEach((trigger) => {
        if (trigger === exceptTrigger) return;
        const submenu = getSubmenu(trigger);
        if (!submenu) return;

        trigger.classList.remove("is-active");
        submenu.classList.remove("is-open");
        submenu.style.maxHeight = "0px";
      });
    }

    function toggle(trigger) {
      const submenu = getSubmenu(trigger);
      if (!submenu) return;

      const isOpen = submenu.classList.contains("is-open");

      if (isOpen) {
        trigger.classList.remove("is-active");
        submenu.classList.remove("is-open");
        submenu.style.maxHeight = "0px";
      } else {
        closeAllTopLevel(trigger);
        trigger.classList.add("is-active");
        submenu.classList.add("is-open");
        submenu.style.maxHeight = `${submenu.scrollHeight}px`;
      }
    }

    function recalculateOpenHeights() {
      navbar
        .querySelectorAll(".navbar__mobile-submenu.is-open")
        .forEach((submenu) => {
          submenu.style.maxHeight = `${submenu.scrollHeight}px`;
        });
    }

    function init() {
      mobileTriggers.forEach((trigger) => {
        trigger.addEventListener("click", (e) => {
          e.preventDefault();
          e.stopPropagation();
          toggle(trigger);
        });
      });
    }

    return { init, closeAllTopLevel, recalculateOpenHeights, getSubmenu };
  })();

  // =====================================================================
  // 8. MOBILE — nested Programs category accordion
  //    (Diplomas / Bachelor's / Master's ... inside Programs submenu)
  // =====================================================================

  const MobileProgramsAccordionController = (() => {
    const submenu = navbar.querySelector('[data-mobile-submenu="programs"]');
    const categories = submenu
      ? submenu.querySelectorAll(":scope > .navbar__mobile-category")
      : [];

    function syncParentHeight() {
      if (!submenu || !submenu.classList.contains("is-open")) return;
      submenu.style.maxHeight = `${submenu.scrollHeight}px`;
    }

    function closeAll() {
      categories.forEach((category) => {
        const trigger = category.querySelector(
          ".navbar__mobile-category-trigger",
        );
        const panel = category.querySelector(".navbar__mobile-category-panel");

        category.classList.remove("is-open");
        if (trigger) trigger.setAttribute("aria-expanded", "false");
        if (panel) {
          panel.style.maxHeight = null;
          panel.setAttribute("aria-hidden", "true");
        }
      });
      syncParentHeight();
    }

    function open(category) {
      const trigger = category.querySelector(
        ".navbar__mobile-category-trigger",
      );
      const panel = category.querySelector(".navbar__mobile-category-panel");
      if (!panel) return;

      category.classList.add("is-open");
      if (trigger) trigger.setAttribute("aria-expanded", "true");
      panel.style.maxHeight = `${panel.scrollHeight}px`;
      panel.setAttribute("aria-hidden", "false");

      // Parent (top-level Programs submenu) height must grow too.
      requestAnimationFrame(syncParentHeight);
    }

    function recalculateOpenHeights() {
      categories.forEach((category) => {
        if (!category.classList.contains("is-open")) return;
        const panel = category.querySelector(".navbar__mobile-category-panel");
        if (panel) panel.style.maxHeight = `${panel.scrollHeight}px`;
      });
      syncParentHeight();
    }

    function init() {
      if (!submenu || !categories.length) return;

      // Event delegation — single listener instead of one per button.
      submenu.addEventListener("click", (e) => {
        const trigger = e.target.closest(".navbar__mobile-category-trigger");
        if (!trigger) return;

        e.stopPropagation();
        const category = trigger.closest(".navbar__mobile-category");
        if (!category) return;

        const wasOpen = category.classList.contains("is-open");
        closeAll();
        if (!wasOpen) open(category);
      });
    }

    return { init, closeAll, recalculateOpenHeights };
  })();

  // =====================================================================
  // 9. DESKTOP MEGA MENU — Programs category ↔ panel switcher
  // =====================================================================

  const ProgramsCategorySwitcher = (() => {
    const { megaPanel } = dom;
    if (!megaPanel) return { init() {} };

    const categoryItems = megaPanel.querySelectorAll(".mega__category-item");
    const panelLists = megaPanel.querySelectorAll(".mega__panel-list");
    const panelTitle = megaPanel.querySelector("[data-panel-title]");
    const panelLink = megaPanel.querySelector("[data-panel-link]");
    const scrollWrap = megaPanel.querySelector(".mega__panel-scroll");
    const canHover = window.matchMedia("(hover: hover)").matches;

    function activate(key) {
      categoryItems.forEach((item) => {
        const isActive = item.dataset.category === key;
        item.classList.toggle("is-active", isActive);
        item.setAttribute("aria-selected", String(isActive));
      });

      panelLists.forEach((panel) => {
        panel.classList.toggle("is-active", panel.dataset.panel === key);
      });

      const activeItem = megaPanel.querySelector(
        `.mega__category-item[data-category="${key}"]`,
      );

      if (activeItem) {
        if (panelTitle) panelTitle.textContent = activeItem.dataset.title || "";
        if (panelLink)
          panelLink.setAttribute("href", activeItem.dataset.href || "#");
      }

      if (scrollWrap) scrollWrap.scrollTop = 0;
    }

    function resetToFirst() {
      if (categoryItems.length) activate(categoryItems[0].dataset.category);
    }

    function init() {
      if (!categoryItems.length) return;

      categoryItems.forEach((item) => {
        item.addEventListener("click", () => activate(item.dataset.category));

        if (canHover) {
          item.addEventListener("mouseenter", () =>
            activate(item.dataset.category),
          );
        }
      });

      // Reset to first category every time the mega menu opens.
      const observer = new MutationObserver(() => {
        if (megaPanel.classList.contains("is-open")) resetToFirst();
      });
      observer.observe(megaPanel, {
        attributes: true,
        attributeFilter: ["class"],
      });
    }

    return { init, activate, resetToFirst };
  })();

  // =====================================================================
  // 10. KEYBOARD ACCESSIBILITY
  // =====================================================================

  const KeyboardNavController = (() => {
    const { dropdownItems } = dom;

    function init() {
      dropdownItems.forEach((item) => {
        const dropdown = item.querySelector(".navbar__dropdown");
        if (!dropdown) return;

        const links = Array.from(
          dropdown.querySelectorAll(".navbar__dropdown-link"),
        );
        if (!links.length) return;

        // Tabbing forward past the last link closes the dropdown.
        links[links.length - 1].addEventListener("keydown", (e) => {
          if (e.key === "Tab" && !e.shiftKey) {
            DropdownController.close(item, true);
          }
        });

        // Arrow key navigation between links.
        links.forEach((link, i) => {
          link.addEventListener("keydown", (e) => {
            if (e.key === "ArrowDown") {
              e.preventDefault();
              (links[i + 1] || links[0]).focus();
            } else if (e.key === "ArrowUp") {
              e.preventDefault();
              (links[i - 1] || links[links.length - 1]).focus();
            } else if (e.key === "Escape") {
              DropdownController.close(item, true);
              const trigger = item.querySelector(".navbar__link--trigger");
              if (trigger) trigger.focus();
            }
          });
        });
      });
    }

    return { init };
  })();

  // =====================================================================
  // 11. RESPONSIVE HANDLING
  // =====================================================================

  const ResizeController = (() => {
    function handle() {
      if (isDesktop() && state.isMobileMenuOpen) {
        MobileMenuController.close();
      }

      if (!isDesktop()) {
        GlobalCloseController.closeAllMenus(true);
      }

      if (state.isMobileMenuOpen) {
        MobileAccordionController.recalculateOpenHeights();
        MobileProgramsAccordionController.recalculateOpenHeights();
      }
    }

    function init() {
      window.addEventListener("resize", rafThrottle(handle), { passive: true });
    }

    return { init };
  })();

  // =====================================================================
  // INIT
  // =====================================================================

  function init() {
    ScrollController.init();
    ActiveLinkController.init();

    MegaMenuController.init();
    DropdownController.init();
    GlobalCloseController.init();

    MobileMenuController.init();
    MobileAccordionController.init();
    MobileProgramsAccordionController.init();
    ProgramsCategorySwitcher.init();

    KeyboardNavController.init();
    ResizeController.init();
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
