(function () {
    "use strict";

    /*
     * HOME SCROLL EFFECTS
     * - Mỗi khối bay vào từ một hướng khác nhau.
     * - Cuộn xuống: khối bay vào.
     * - Cuộn lên: khối bay ngược ra.
     * - Chữ được reveal từ trái sang phải theo progress, giống đánh máy.
     *
     * QUAN TRỌNG:
     * Không tách chữ thành từng <span>.
     * Cách tách từng ký tự làm dấu tiếng Việt (ầ, ế, ỗ, ệ...) có thể bị
     * render lệch ở một số font/trình duyệt. Clip-path trên cả cụm chữ
     * giữ nguyên glyph và dấu tiếng Việt, nên áp dụng an toàn cho toàn site.
     */

    const reduceMotion = window.matchMedia &&
        window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    const selectors = [
        ".home .small-title",
        ".home h1",
        ".home .description",
        ".home-buttons",
        ".home .info",
        ".home-image",
        ".gioi-thieu .section-content",
        ".gioi-thieu .number-box",
        ".section-title-center",
        ".feature-card:nth-child(1)",
        ".feature-card:nth-child(2)",
        ".feature-card:nth-child(3)",
        ".mau-cv .section-content",
        ".mau-container",
        ".mau:nth-child(1)",
        ".mau:nth-child(2)",
        ".mau:nth-child(3)",
        ".cta > div"
    ];

    const directions = [
        "home-scroll-left",
        "home-scroll-right",
        "home-scroll-top",
        "home-scroll-bottom",
        "home-scroll-diagonal-left",
        "home-scroll-diagonal-right"
    ];

    const typewriterSelectors = [
        ".home .small-title",
        ".home h1",
        ".section-title-center"
    ];

    const items = [];

    function addAnimationClass(element, index) {
        if (!element || element.dataset.homeAnimated === "1") return;

        element.dataset.homeAnimated = "1";
        element.classList.add("home-scroll-item");
        element.classList.add(directions[index % directions.length]);

        element.style.setProperty("--scroll-x", "var(--start-x)");
        element.style.setProperty("--scroll-y", "var(--start-y)");
        element.style.setProperty("--scroll-scale", "0.96");
        element.style.setProperty("--scroll-opacity", "0");
        element.style.setProperty("--type-progress", "0");

        const typewriter = typewriterSelectors.some(function (selector) {
            return element.matches(selector);
        });

        if (typewriter) {
            element.classList.add("home-typewriter");
            element.setAttribute("data-typewriter", "true");
        }

        items.push({
            element: element,
            typewriter: typewriter
        });
    }

    selectors.forEach(function (selector, index) {
        document.querySelectorAll(selector).forEach(function (element, childIndex) {
            addAnimationClass(element, index + childIndex);
        });
    });

    function clamp(value, min, max) {
        return Math.max(min, Math.min(max, value));
    }

    function ease(value) {
        return value * value * (3 - 2 * value);
    }

    function updateItem(item) {
        const element = item.element;
        const rect = element.getBoundingClientRect();
        const viewportHeight = window.innerHeight || document.documentElement.clientHeight;

        const start = viewportHeight * 0.92;
        const end = viewportHeight * 0.38;

        let progress = (start - rect.top) / (start - end);
        progress = clamp(progress, 0, 1);
        progress = ease(progress);

        const startX = getComputedStyle(element).getPropertyValue("--start-x").trim() || "0px";
        const startY = getComputedStyle(element).getPropertyValue("--start-y").trim() || "0px";

        const x = "calc(" + startX + " * " + (1 - progress) + ")";
        const y = "calc(" + startY + " * " + (1 - progress) + ")";
        const scale = 0.96 + (0.04 * progress);

        element.style.setProperty("--scroll-x", x);
        element.style.setProperty("--scroll-y", y);
        element.style.setProperty("--scroll-scale", scale.toFixed(3));
        element.style.setProperty("--scroll-opacity", progress.toFixed(3));

        if (item.typewriter) {
            /*
             * Reveal sau khi khối đã bay vào khoảng 50%.
             * Khi cuộn ngược, --type-progress giảm về 0 => chữ biến mất
             * theo đúng chiều ngược lại.
             */
            const typingProgress = clamp((progress - 0.50) / 0.50, 0, 1);
            element.style.setProperty("--type-progress", typingProgress.toFixed(3));
        }
    }

    let ticking = false;

    function updateAll() {
        ticking = false;
        items.forEach(updateItem);
    }

    function requestUpdate() {
        if (ticking) return;
        ticking = true;
        window.requestAnimationFrame(updateAll);
    }

    if (!reduceMotion) {
        window.addEventListener("scroll", requestUpdate, { passive: true });
        window.addEventListener("resize", requestUpdate);
    }

    /* Nếu người dùng bật giảm chuyển động: hiện đầy đủ nội dung, không transform. */
    if (reduceMotion) {
        items.forEach(function (item) {
            item.element.style.setProperty("--scroll-x", "0px");
            item.element.style.setProperty("--scroll-y", "0px");
            item.element.style.setProperty("--scroll-scale", "1");
            item.element.style.setProperty("--scroll-opacity", "1");
            item.element.style.setProperty("--type-progress", "1");
        });
        return;
    }

    updateAll();
})();
