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
/* Nyitva most? A nyitvatartas a jelolesben all (data-days / data-hours),
   igy egy uj nyitvatartashoz nem kell a szkriptet atirni. */
document.addEventListener('DOMContentLoaded', function () {
  var toMinutes = function (t) { var p = t.split(':'); return (+p[0]) * 60 + (+p[1]); };

  document.querySelectorAll('[data-open-now]').forEach(function (el) {
    var days = el.dataset.days.split(',').map(Number);
    var range = el.dataset.hours.split('-');
    var now = new Date();
    var minutes = now.getHours() * 60 + now.getMinutes();
    var open = days.indexOf(now.getDay()) !== -1
      && minutes >= toMinutes(range[0])
      && minutes < toMinutes(range[1]);

    el.textContent = open ? 'Open now' : 'Closed now';
    el.classList.add(open ? 'is-open' : 'is-closed');
    el.title = 'Opening hours ' + range[0] + '–' + range[1];
  });
});