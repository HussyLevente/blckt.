const galleryItems = [...document.querySelectorAll('.gallery-item')];
const filterButtons = [...document.querySelectorAll('.filter-button')];
const lightbox = document.querySelector('.lightbox');
const lightboxImage = lightbox.querySelector('img');
const lightboxTitle = lightbox.querySelector('.lightbox-title');
const lightboxLocation = lightbox.querySelector('.lightbox-location');
let currentIndex = 0;

const visibleItems = () => galleryItems.filter((item) => item.style.display !== 'none');

function showImage(item) {
  currentIndex = Number(item.dataset.index);
  lightboxImage.src = item.querySelector('img').src;
  lightboxImage.alt = item.querySelector('img').alt;
  lightboxTitle.textContent = item.dataset.title;
  lightboxLocation.textContent = item.dataset.location;
}

function openLightbox(item) {
  showImage(item);
  lightbox.setAttribute('aria-hidden', 'false');
  lightbox.classList.add('is-open');
  document.body.classList.add('no-scroll');
  lightbox.querySelector('.lightbox-close').focus();
}

function closeLightbox() {
  lightbox.classList.remove('is-open');
  lightbox.setAttribute('aria-hidden', 'true');
  document.body.classList.remove('no-scroll');
}

function moveLightbox(direction) {
  const items = visibleItems();
  const currentPosition = items.findIndex((item) => Number(item.dataset.index) === currentIndex);
  const nextPosition = (currentPosition + direction + items.length) % items.length;
  showImage(items[nextPosition]);
}

filterButtons.forEach((button) => {
  button.addEventListener('click', () => {
    const filter = button.dataset.filter;
    filterButtons.forEach((tab) => {
      const active = tab === button;
      tab.classList.toggle('is-active', active);
      tab.setAttribute('aria-selected', active);
    });
    galleryItems.forEach((item) => {
      const matches = filter === 'all' || item.dataset.category === filter;
      item.style.display = matches ? '' : 'none';
      if (matches) requestAnimationFrame(() => item.classList.add('is-visible'));
    });
  });
});

galleryItems.forEach((item) => item.addEventListener('click', () => openLightbox(item)));
lightbox.querySelector('.lightbox-close').addEventListener('click', closeLightbox);
lightbox.querySelector('.lightbox-prev').addEventListener('click', () => moveLightbox(-1));
lightbox.querySelector('.lightbox-next').addEventListener('click', () => moveLightbox(1));
lightbox.addEventListener('click', (event) => { if (event.target === lightbox) closeLightbox(); });
document.addEventListener('keydown', (event) => {
  if (!lightbox.classList.contains('is-open')) return;
  if (event.key === 'Escape') closeLightbox();
  if (event.key === 'ArrowLeft') moveLightbox(-1);
  if (event.key === 'ArrowRight') moveLightbox(1);
});

const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => { if (entry.isIntersecting) { entry.target.classList.add('is-visible'); observer.unobserve(entry.target); } });
}, { threshold: .12 });
document.querySelectorAll('.reveal').forEach((element) => observer.observe(element));

const parallaxImage = document.querySelector('[data-parallax] img');
function updateParallax() {
  if (!parallaxImage || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  const bounds = parallaxImage.closest('[data-parallax]').getBoundingClientRect();
  const offset = Math.max(-35, Math.min(35, (window.innerHeight / 2 - (bounds.top + bounds.height / 2)) * .055));
  parallaxImage.style.transform = `translateY(calc(-4% + ${offset}px))`;
}
window.addEventListener('scroll', updateParallax, { passive: true });
updateParallax();

const cursorRing = document.querySelector('.cursor-ring');
const cursorDot = document.querySelector('.cursor-dot');
window.addEventListener('mousemove', (event) => {
  cursorDot.style.left = `${event.clientX}px`;
  cursorDot.style.top = `${event.clientY}px`;
  cursorRing.animate({ left: `${event.clientX}px`, top: `${event.clientY}px` }, { duration: 450, fill: 'forwards', easing: 'cubic-bezier(.16, 1, .3, 1)' });
});
document.querySelectorAll('a, button, .magnetic').forEach((element) => {
  element.addEventListener('mouseenter', () => cursorRing.classList.add('is-hover'));
  element.addEventListener('mouseleave', () => cursorRing.classList.remove('is-hover'));
});
