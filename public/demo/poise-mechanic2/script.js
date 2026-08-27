/* =========================================
   BLACKLINE MOTORWORKS / Interaction layer
   Static-only behavior: the booking form is mocked.
   ========================================= */

document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('#booking-form');
  if (!form) return;

  const button = form.querySelector('button[type="submit"]');
  const status = form.querySelector('.status');

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    button.disabled = true;
    button.textContent = 'TRANSMITTING...';
    status.textContent = 'DIAGNOSTIC PACKET QUEUED // PLEASE HOLD';

    setTimeout(() => {
      // [BACKEND FETCH API HERE]
      button.disabled = false;
      button.textContent = 'REQUEST SLOT';
      status.textContent = 'REQUEST RECEIVED // A TECHNICIAN WILL REPLY SHORTLY';
      form.reset();
    }, 1500);
  });
});
