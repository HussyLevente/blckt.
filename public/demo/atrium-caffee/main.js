// Shared motion, accordion, and mock contact interactions for Atrium Coffee.
document.addEventListener('DOMContentLoaded', () => {
  const revealObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      entry.target.classList.add('is-visible');
      observer.unobserve(entry.target);
    });
  }, { threshold: 0.12 });

  document.querySelectorAll('.reveal-group, .reveal-line, .image-frame').forEach((element) => revealObserver.observe(element));

  const contactForm = document.querySelector('[data-contact-form]');
  if (contactForm) {
    const submitButton = contactForm.querySelector('.submit-button');
    const status = contactForm.querySelector('.form-status');

    contactForm.addEventListener('submit', (event) => {
      event.preventDefault();
      submitButton.disabled = true;
      submitButton.querySelector('span:first-child').textContent = 'Brewing your message...';
      status.textContent = '';

      setTimeout(() => {
        // [BACKEND INTEGRATION POINT]
        contactForm.innerHTML = '<p class="success-state">Your note is<br><i>on its way.</i><br><small>We will be in touch soon.</small></p>';
      }, 1500);
    });
  }
});
