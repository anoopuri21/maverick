/* ================================================================
   MAVERICK BUSINESS ACADEMY — ACCREDITATIONS ANIMATIONS
   GSAP + ScrollTrigger + IntersectionObserver
   ================================================================ */

document.addEventListener("DOMContentLoaded", () => {
  if (typeof gsap === "undefined") return;
  if (typeof ScrollTrigger !== "undefined") gsap.registerPlugin(ScrollTrigger);

  /* ============================================================
     PAGE BANNER — Living animated hero
     ============================================================ */
  const banner = document.querySelector(".page-banner");
  if (banner) {
    setTimeout(() => banner.classList.add("is-loaded"), 100);

    // Stagger content reveal
    const bContent = banner.querySelector(".page-banner__content");
    if (bContent) {
      gsap.from(bContent.querySelector(".page-banner__breadcrumb"), {
        y: 20,
        opacity: 0,
        duration: 0.8,
        delay: 0.3,
        ease: "power3.out",
      });
      gsap.from(bContent.querySelector(".page-banner__title"), {
        y: 40,
        opacity: 0,
        duration: 1,
        delay: 0.5,
        ease: "power3.out",
      });
      gsap.from(bContent.querySelector(".page-banner__desc"), {
        y: 30,
        opacity: 0,
        duration: 0.9,
        delay: 0.8,
        ease: "power3.out",
      });
    }

    // Corner brackets stagger
    banner.querySelectorAll(".page-banner__corner").forEach((c, i) => {
      gsap.from(c, {
        opacity: 0,
        scale: 0.5,
        duration: 0.6,
        delay: 1 + i * 0.1,
        ease: "power3.out",
      });
    });

    // Parallax bg on scroll
    gsap.to(".page-banner__bg-img", {
      y: 60,
      ease: "none",
      scrollTrigger: {
        trigger: banner,
        start: "top top",
        end: "bottom top",
        scrub: true,
      },
    });
    gsap.to(".page-banner__orb--1", {
      y: -50,
      x: -30,
      ease: "none",
      scrollTrigger: {
        trigger: banner,
        start: "top top",
        end: "bottom top",
        scrub: true,
      },
    });
    gsap.to(".page-banner__orb--2", {
      y: -40,
      x: 20,
      ease: "none",
      scrollTrigger: {
        trigger: banner,
        start: "top top",
        end: "bottom top",
        scrub: true,
      },
    });
  }

  /* ============================================================
     ACCREDITATIONS HERO
     ============================================================ */
  const hero = document.querySelector(".accreditations-hero");
  if (hero) {
    const heroContent = hero.querySelector(".accreditations-hero__content");
    const heroBadges = hero.querySelectorAll(".accreditations-hero__badge");
    const heroStats = hero.querySelectorAll(".accreditations-hero__stat");

    if (heroContent) {
      gsap.from(heroContent.querySelector(".accreditations-hero__num"), {
        y: 60,
        opacity: 0,
        duration: 1.2,
        delay: 0.2,
        ease: "power3.out",
        scrollTrigger: { trigger: hero, start: "top 80%", once: true },
      });
      gsap.from(heroContent.querySelector(".accreditations-hero__title"), {
        y: 50,
        opacity: 0,
        duration: 1,
        delay: 0.4,
        ease: "power3.out",
        scrollTrigger: { trigger: hero, start: "top 80%", once: true },
      });
      gsap.from(heroContent.querySelector(".accreditations-hero__desc"), {
        y: 40,
        opacity: 0,
        duration: 1,
        delay: 0.6,
        ease: "power3.out",
        scrollTrigger: { trigger: hero, start: "top 80%", once: true },
      });
    }

    heroStats.forEach((stat, i) => {
      ScrollTrigger.create({
        trigger: stat,
        start: "top 90%",
        once: true,
        onEnter: () => {
          gsap.from(stat, {
            y: 30,
            opacity: 0,
            duration: 0.8,
            delay: i * 0.15,
            ease: "power3.out",
          });
          const numEl = stat.querySelector(".accreditations-hero__stat-num");
          if (numEl) {
            const text = numEl.textContent;
            const match = text.match(/(\d+)/);
            if (match) {
              const target = parseInt(match[0], 10);
              const prefix = text.substring(0, text.indexOf(match[0]));
              const suffix = text.substring(
                text.indexOf(match[0]) + match[0].length,
              );
              const counter = { val: 0 };
              gsap.to(counter, {
                val: target,
                duration: 2,
                delay: 0.3 + i * 0.15,
                ease: "power2.out",
                onUpdate: () => {
                  numEl.innerHTML = prefix + Math.floor(counter.val) + suffix;
                },
              });
            }
          }
        },
      });
    });

    heroBadges.forEach((badge, i) => {
      ScrollTrigger.create({
        trigger: badge,
        start: "top 92%",
        once: true,
        onEnter: () =>
          gsap.from(badge, {
            y: 50,
            opacity: 0,
            scale: 0.9,
            duration: 0.8,
            delay: i * 0.12,
            ease: "power3.out",
          }),
      });
    });

    gsap.to(".accreditations-hero__bg-shape", {
      y: -80,
      ease: "none",
      scrollTrigger: {
        trigger: hero,
        start: "top top",
        end: "bottom top",
        scrub: true,
      },
    });
  }

  /* ============================================================
     SECTION 1: ACCREDITATIONS
     ============================================================ */
  const accrSection = document.querySelector(".accreditations-section");
  if (accrSection) {
    const accrHeader = accrSection.querySelector(
      ".accreditations-section__header",
    );
    ScrollTrigger.create({
      trigger: accrHeader,
      start: "top 80%",
      once: true,
      onEnter: () => accrHeader.classList.add("is-visible"),
    });
    const catBtns = accrSection.querySelectorAll(
      ".accreditations-section__cat-btn",
    );
    catBtns.forEach((btn) => {
      btn.addEventListener("click", () => {
        catBtns.forEach((b) => b.classList.remove("active"));
        btn.classList.add("active");
      });
    });
  }

  /* ============================================================
     SECTION 2: AWARDS — Auto-slide carousel
     ============================================================ */
  const awardsSection = document.querySelector(".awards-section");
  if (awardsSection) {
    const awardsHeader = awardsSection.querySelector(".awards-section__header");
    ScrollTrigger.create({
      trigger: awardsHeader,
      start: "top 80%",
      once: true,
      onEnter: () => awardsHeader.classList.add("is-visible"),
    });

    const track = awardsSection.querySelector(".awards-carousel__track");
    const cards = awardsSection.querySelectorAll(".awards-card");
    const prevBtn = awardsSection.querySelector(".awards-nav--prev");
    const nextBtn = awardsSection.querySelector(".awards-nav--next");
    const dotsWrap = awardsSection.querySelector(".awards-carousel__dots");

    if (track && cards.length) {
      let currentIndex = 0;
      let autoSlideTimer;
      const gap = 24; // 1.5rem

      function getVisibleCount() {
        const w = window.innerWidth;
        if (w >= 1280) return 4;
        if (w >= 1024) return 3;
        if (w >= 640) return 2;
        return 1;
      }

      function getMaxIndex() {
        return Math.max(0, cards.length - getVisibleCount());
      }

      function slideTo(idx) {
        const max = getMaxIndex();
        currentIndex = Math.max(0, Math.min(idx, max));
        const card = cards[currentIndex];
        if (card) {
          const offset = currentIndex * (card.offsetWidth + gap);
          track.style.transform = `translateX(-${offset}px)`;
        }
        updateDots();
      }

      function updateDots() {
        if (!dotsWrap) return;
        dotsWrap.querySelectorAll(".awards-carousel__dot").forEach((dot, i) => {
          dot.classList.toggle("active", i === currentIndex);
        });
      }

      function buildDots() {
        if (!dotsWrap) return;
        dotsWrap.innerHTML = "";
        const max = getMaxIndex();
        for (let i = 0; i <= max; i++) {
          const dot = document.createElement("button");
          dot.className = "awards-carousel__dot" + (i === 0 ? " active" : "");
          dot.addEventListener("click", () => {
            slideTo(i);
            resetAutoSlide();
          });
          dotsWrap.appendChild(dot);
        }
      }

      function autoSlide() {
        const max = getMaxIndex();
        currentIndex = currentIndex >= max ? 0 : currentIndex + 1;
        slideTo(currentIndex);
      }

      function resetAutoSlide() {
        clearInterval(autoSlideTimer);
        autoSlideTimer = setInterval(autoSlide, 4000);
      }

      if (prevBtn)
        prevBtn.addEventListener("click", () => {
          slideTo(currentIndex - 1);
          resetAutoSlide();
        });
      if (nextBtn)
        nextBtn.addEventListener("click", () => {
          slideTo(currentIndex + 1);
          resetAutoSlide();
        });

      // Touch/swipe support
      let touchStartX = 0,
        touchEndX = 0;
      track.addEventListener(
        "touchstart",
        (e) => {
          touchStartX = e.changedTouches[0].screenX;
        },
        { passive: true },
      );
      track.addEventListener("touchend", (e) => {
        touchEndX = e.changedTouches[0].screenX;
        const diff = touchStartX - touchEndX;
        if (Math.abs(diff) > 50) {
          slideTo(diff > 0 ? currentIndex + 1 : currentIndex - 1);
          resetAutoSlide();
        }
      });

      // 3D tilt on hover
      cards.forEach((card) => {
        card.addEventListener("mousemove", (e) => {
          const rect = card.getBoundingClientRect();
          const x = (e.clientX - rect.left) / rect.width - 0.5;
          const y = (e.clientY - rect.top) / rect.height - 0.5;
          gsap.to(card, {
            rotateY: x * 10,
            rotateX: -y * 6,
            duration: 0.4,
            ease: "power2.out",
            transformPerspective: 800,
          });
        });
        card.addEventListener("mouseleave", () => {
          gsap.to(card, {
            rotateY: 0,
            rotateX: 0,
            duration: 0.6,
            ease: "power3.out",
          });
        });
      });

      buildDots();
      resetAutoSlide();
      window.addEventListener("resize", () => {
        buildDots();
        slideTo(Math.min(currentIndex, getMaxIndex()));
      });

      // Pause auto-slide on hover
      const carousel = awardsSection.querySelector(".awards-carousel");
      if (carousel) {
        carousel.addEventListener("mouseenter", () =>
          clearInterval(autoSlideTimer),
        );
        carousel.addEventListener("mouseleave", resetAutoSlide);
      }
    }
  }

  /* ============================================================
     SECTION 3: RECOGNITION — Drag scroll + Reveals
     ============================================================ */
  const recogSection = document.querySelector(".recognition-section");
  if (recogSection) {
    const recogHeader = recogSection.querySelector(
      ".recognition-section__header",
    );
    const recogScroll = recogSection.querySelector(
      ".recognition-section__scroll",
    );
    const recogCards = recogSection.querySelectorAll(".recognition-card");

    ScrollTrigger.create({
      trigger: recogHeader,
      start: "top 80%",
      once: true,
      onEnter: () => recogHeader.classList.add("is-visible"),
    });

    ScrollTrigger.create({
      trigger: recogScroll,
      start: "top 85%",
      once: true,
      onEnter: () => {
        recogCards.forEach((card, i) => {
          gsap.from(card, {
            x: 80,
            opacity: 0,
            scale: 0.92,
            duration: 0.8,
            delay: i * 0.1,
            ease: "power3.out",
          });
        });
      },
    });

    if (recogScroll) {
      let isDown = false,
        startX,
        scrollLeft;
      recogScroll.addEventListener("mousedown", (e) => {
        isDown = true;
        recogScroll.style.cursor = "grabbing";
        startX = e.pageX - recogScroll.offsetLeft;
        scrollLeft = recogScroll.scrollLeft;
      });
      recogScroll.addEventListener("mouseleave", () => {
        isDown = false;
        recogScroll.style.cursor = "grab";
      });
      recogScroll.addEventListener("mouseup", () => {
        isDown = false;
        recogScroll.style.cursor = "grab";
      });
      recogScroll.addEventListener("mousemove", (e) => {
        if (!isDown) return;
        e.preventDefault();
        recogScroll.scrollLeft =
          scrollLeft - (e.pageX - recogScroll.offsetLeft - startX) * 1.5;
      });
      recogScroll.style.cursor = "grab";
    }
  }

  /* ============================================================
     GENERIC js-reveal OBSERVER
     ============================================================ */
  const reveals = document.querySelectorAll(".js-reveal");
  if (reveals.length) {
    const revObs = new IntersectionObserver(
      (entries) => {
        entries.forEach((e) => {
          if (e.isIntersecting) {
            e.target.classList.add("is-visible");
            revObs.unobserve(e.target);
          }
        });
      },
      { threshold: 0.15 },
    );
    reveals.forEach((el) => revObs.observe(el));
  }
});
