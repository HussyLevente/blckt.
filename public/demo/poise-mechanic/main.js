document.addEventListener('DOMContentLoaded', () => {
  const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        revealObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.15 });
  document.querySelectorAll('.reveal').forEach((element) => revealObserver.observe(element));
  document.querySelectorAll('.image-frame').forEach((element) => revealObserver.observe(element));

  document.querySelectorAll('.service-trigger').forEach((trigger) => {
    trigger.addEventListener('click', () => {
      const item = trigger.closest('.service-item');
      const isOpen = item.classList.toggle('is-open');
      trigger.setAttribute('aria-expanded', isOpen);
    });
  });

  const form = document.querySelector('#booking-form');
  if (form) {
    form.addEventListener('submit', (event) => {
      event.preventDefault();
      const submitButton = form.querySelector('button[type="submit"]');
      submitButton.disabled = true;
      submitButton.textContent = 'Transmitting Request...';

      // [BACKEND INTEGRATION POINT - FETCH API HERE]
      // Replace this simulation with a POST request to the booking endpoint.
      setTimeout(() => {
        form.hidden = true;
        document.querySelector('#success-state').classList.add('is-visible');
      }, 1500);
    });
  }
});
