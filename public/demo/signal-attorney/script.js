const revealItems = document.querySelectorAll('.reveal');

const revealObserver = new IntersectionObserver(
  (entries, observer) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;

      const delay = Number(entry.target.dataset.delay || 0);
      const visibleItem = () => {
        entry.target.classList.add('is-visible');
      };

      if (delay > 0) {
        window.setTimeout(visibleItem, delay);
      } else {
        visibleItem();
      }

      observer.unobserve(entry.target);
    });
  },
  {
    threshold: 0.18,
    rootMargin: '0px 0px -8% 0px',
  }
);

revealItems.forEach((item) => revealObserver.observe(item));

const heroBackdrop = document.querySelector('.hero-backdrop');

if (heroBackdrop) {
  const handleParallax = () => {
    const offset = window.scrollY * 0.22;
    heroBackdrop.style.transform = `translate3d(0, ${offset}px, 0) scale(1.12)`;
  };

  window.addEventListener('scroll', handleParallax, { passive: true });
  handleParallax();
}
