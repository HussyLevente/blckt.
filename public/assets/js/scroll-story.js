document.addEventListener('DOMContentLoaded', function () {
    initScrollStory();
    initRevealOnScroll();
});

function clamp01(value) {
    return Math.min(1, Math.max(0, value));
}

function pinProgress(pinEl) {
    const rect = pinEl.getBoundingClientRect();
    const scrollRoom = pinEl.offsetHeight - window.innerHeight;
    if (scrollRoom <= 0) return 1;
    return clamp01(-rect.top / scrollRoom);
}

function initScrollStory() {
    const heroPin = document.querySelector('.hero-pin');
    const heroSection = document.querySelector('.hero-section');
    const manifestoPins = Array.from(document.querySelectorAll('.manifesto-pin'));

    if (!heroPin || !heroSection) return;

    const MAX_BLUR = 25;
    const FADE_IN_END = 0.35;
    const FADE_OUT_START = 0.65;

    let ticking = false;

    function render() {
        const heroProgress = pinProgress(heroPin);
        const heroBlur = heroProgress * MAX_BLUR;
        heroSection.style.filter = heroBlur > 0.1 ? `blur(${heroBlur}px)` : '';
        heroSection.style.opacity = String(1 - heroProgress);

        manifestoPins.forEach((pin) => {
            const content = pin.querySelector('.manifesto-content');
            if (!content) return;

            const progress = pinProgress(pin);
            let opacity = 1;
            let blur = 0;

            if (progress <= FADE_IN_END) {
                opacity = progress / FADE_IN_END;
            } else if (progress >= FADE_OUT_START) {
                const t = (progress - FADE_OUT_START) / (1 - FADE_OUT_START);
                opacity = 1 - t;
                blur = t * MAX_BLUR;
            }

            content.style.opacity = String(opacity);
            content.style.filter = blur > 0.1 ? `blur(${blur}px)` : '';
        });

        ticking = false;
    }

    function onScroll() {
        if (!ticking) {
            requestAnimationFrame(render);
            ticking = true;
        }
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
    render();
}

function initRevealOnScroll() {
    const revealEls = document.querySelectorAll('.reveal');
    if (revealEls.length === 0) return;

    if (!('IntersectionObserver' in window)) {
        revealEls.forEach((el) => el.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                entry.target.classList.toggle('is-visible', entry.isIntersecting);
            });
        },
        { threshold: 0.15, rootMargin: '0px 0px -80px 0px' }
    );

    revealEls.forEach((el) => observer.observe(el));
}
