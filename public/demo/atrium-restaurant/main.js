document.documentElement.classList.add('js');

document.addEventListener('DOMContentLoaded', () => {
  const revealItems = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries, currentObserver) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          currentObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });
    revealItems.forEach((item) => observer.observe(item));
  } else {
    revealItems.forEach((item) => item.classList.add('is-visible'));
  }

  const bookingForm = document.querySelector('#booking-form');
  const successState = document.querySelector('#booking-success');
  if (bookingForm && successState) {
    bookingForm.addEventListener('submit', (event) => {
      event.preventDefault();
      const submitButton = bookingForm.querySelector('button[type="submit"]');
      submitButton.disabled = true;
      submitButton.innerHTML = 'Confirming table...';
      // [BACKEND INTEGRATION POINT - FETCH API HERE]
      window.setTimeout(() => {
        bookingForm.hidden = true;
        successState.hidden = false;
        successState.classList.add('is-visible');
      }, 1500);
    });
  }
});