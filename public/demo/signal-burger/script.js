/* Small, dependency-free motion layer for the landing page. */
const revealItems = document.querySelectorAll('.reveal');
const heroImage = document.querySelector('.hero-image');

if ('IntersectionObserver' in window) {
  const revealObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      entry.target.classList.add('is-visible');
      observer.unobserve(entry.target);
    });
  }, { threshold: 0.12, rootMargin: '0px 0px -40px' });

  revealItems.forEach((item) => revealObserver.observe(item));
} else {
  revealItems.forEach((item) => item.classList.add('is-visible'));
}

let ticking = false;
const updateParallax = () => {
  const offset = Math.min(window.scrollY * 0.12, 72);
  heroImage.style.setProperty('--parallax', `${offset}px`);
  ticking = false;
};

window.addEventListener('scroll', () => {
  if (!ticking) {
    window.requestAnimationFrame(updateParallax);
    ticking = true;
  }
}, { passive: true });
