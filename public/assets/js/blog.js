/**
 * Maverick Business Academy
 * Blog Dedicated JS (Animations & Interactive Features)
 */
(function() {
    "use strict";

    document.addEventListener("DOMContentLoaded", () => {
        initCopyLink();
        initMobileToc();
        initReadingProgressBar();
        initParallaxHero();
        initScrollAnimations();
    });

    /**
     * 1. Clipboard copy tool for sticky share bar
     */
    function initCopyLink() {
        const copyBtns = document.querySelectorAll(".blog-share-bar__copy-btn");
        copyBtns.forEach(btn => {
            btn.addEventListener("click", () => {
                const url = btn.getAttribute("data-copy-url");
                if (url) {
                    navigator.clipboard.writeText(url).then(() => {
                        btn.classList.add("copied");
                        setTimeout(() => {
                            btn.classList.remove("copied");
                        }, 2000);
                    }).catch(err => {
                        console.error("Failed to copy link: ", err);
                    });
                }
            });
        });
    }

    /**
     * 2. Mobile ToC collapsible dropdown behavior
     */
    function initMobileToc() {
        const tocToggle = document.querySelector(".blog-toc__toggle-btn");
        const toc = document.querySelector(".blog-toc");
        if (!tocToggle || !toc) return;

        tocToggle.addEventListener("click", () => {
            const isExpanded = tocToggle.getAttribute("aria-expanded") === "true";
            tocToggle.setAttribute("aria-expanded", !isExpanded);

            const toggleText = tocToggle.querySelector(".blog-toc__toggle-text");
            if (toggleText) {
                toggleText.textContent = isExpanded ? "Show" : "Hide";
            }

            toc.classList.toggle("blog-toc--expanded");
        });
    }

    /**
     * 3. Reading progress indicator calculation
     */
    function initReadingProgressBar() {
        const fill = document.getElementById("blog-progress-fill");
        const content = document.getElementById("blog-article-content");
        if (!fill || !content) return;

        window.addEventListener("scroll", () => {
            const rect = content.getBoundingClientRect();
            const contentHeight = content.offsetHeight;
            const scrollTop = window.scrollY || document.documentElement.scrollTop;

            // Calculate progress relative to article start and end
            const articleTop = rect.top + scrollTop - (window.innerHeight / 2);
            const totalScrollableDistance = contentHeight - (window.innerHeight / 3);

            let progress = 0;
            if (scrollTop > articleTop) {
                progress = ((scrollTop - articleTop) / totalScrollableDistance) * 100;
            }

            // Clamp between 0% and 100%
            progress = Math.min(Math.max(progress, 0), 100);
            fill.style.width = `${progress}%`;
        }, { passive: true });
    }

    /**
     * 4. Cinematic parallax effect on detail hero
     */
    function initParallaxHero() {
        const hero = document.getElementById("blog-detail-hero");
        const img = document.getElementById("blog-detail-hero-img");
        if (!hero || !img || typeof gsap === "undefined") return;

        gsap.to(img, {
            y: "30%",
            ease: "none",
            scrollTrigger: {
                trigger: hero,
                start: "top top",
                end: "bottom top",
                scrub: true
            }
        });
    }

    /**
     * 5. Smooth scroll entrance animation for cards/sections
     */
    function initScrollAnimations() {
        if (typeof gsap === "undefined" || typeof ScrollTrigger === "undefined") return;

        // Animate cards staggered as they enter viewport
        const grid = document.querySelector(".blog-grid");
        const cards = document.querySelectorAll(".blog-card");
        if (grid && cards.length > 0) {
            gsap.fromTo(cards,
                {
                    opacity: 0,
                    y: 40
                },
                {
                    opacity: 1,
                    y: 0,
                    duration: 0.8,
                    stagger: 0.15,
                    ease: "power2.out",
                    scrollTrigger: {
                        trigger: grid,
                        start: "top 80%",
                        toggleActions: "play none none none"
                    }
                }
            );
        }

        // Animate related posts section
        const relatedGrid = document.querySelector(".blog-grid--related");
        const relatedCards = document.querySelectorAll(".blog-grid--related .blog-card");
        if (relatedGrid && relatedCards.length > 0) {
            gsap.fromTo(relatedCards,
                {
                    opacity: 0,
                    y: 30
                },
                {
                    opacity: 1,
                    y: 0,
                    duration: 0.7,
                    stagger: 0.12,
                    ease: "power2.out",
                    scrollTrigger: {
                        trigger: relatedGrid,
                        start: "top 85%",
                        toggleActions: "play none none none"
                    }
                }
            );
        }

        // Animate newsletter/cta section
        const newsletter = document.querySelector(".blog-newsletter");
        if (newsletter) {
            gsap.fromTo(newsletter.querySelectorAll(".fade-up, .blog-newsletter__inner > *"),
                {
                    opacity: 0,
                    y: 30
                },
                {
                    opacity: 1,
                    y: 0,
                    duration: 0.8,
                    stagger: 0.15,
                    ease: "power2.out",
                    scrollTrigger: {
                        trigger: newsletter,
                        start: "top 80%",
                        toggleActions: "play none none none"
                    }
                }
            );
        }
    }

})();
