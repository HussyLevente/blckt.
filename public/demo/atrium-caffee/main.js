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