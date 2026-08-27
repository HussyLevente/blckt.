document.addEventListener('DOMContentLoaded', () => {
  const revealItems = document.querySelectorAll('.reveal');
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12 });
  revealItems.forEach((item) => observer.observe(item));

  const form = document.querySelector('#booking-form');
  if (form) {
    const success = document.querySelector('#form-success');
    const button = form.querySelector('.submit-button');
    form.addEventListener('submit', (event) => {
      event.preventDefault();
      button.disabled = true;
      button.querySelector('span').textContent = 'Processing request...';
      // [BACKEND INTEGRATION POINT - FETCH API HERE]
      setTimeout(() => {
        form.hidden = true;
        success.hidden = false;
        success.classList.add('is-visible');
      }, 1500);
    });
  }
});