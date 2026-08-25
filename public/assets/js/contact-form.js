(function () {
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.contact-form').forEach(initContactForm);
    });

    function initContactForm(form) {
        var submitBtn = form.querySelector('.form-submit');
        var statusEl = form.querySelector('[data-form-status]');
        var defaultLabel = (submitBtn && submitBtn.dataset.labelDefault) || (submitBtn ? submitBtn.textContent : '');
        var sendingLabel = (submitBtn && submitBtn.dataset.labelSending) || defaultLabel;
        var sentLabel = (submitBtn && submitBtn.dataset.labelSent) || defaultLabel;
        var networkError = (statusEl && statusEl.dataset.networkError) || 'Something went wrong. Please try again.';

        attachBudgetFormatter(form);

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            if (!submitBtn || submitBtn.disabled) {
                return;
            }

            clearErrors(form);
            setStatus(statusEl, '', null);
            submitBtn.disabled = true;
            submitBtn.textContent = sendingLabel;

            fetch(form.getAttribute('action'), {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: new FormData(form),
            })
                .then(function (response) {
                    return response
                        .json()
                        .catch(function () {
                            return {};
                        })
                        .then(function (data) {
                            return { ok: response.ok, status: response.status, data: data };
                        });
                })
                .then(function (result) {
                    if (result.ok) {
                        form.reset();
                        setStatus(statusEl, result.data.message, 'success');
                        submitBtn.textContent = sentLabel;
                        window.setTimeout(function () {
                            submitBtn.disabled = false;
                            submitBtn.textContent = defaultLabel;
                        }, 2500);
                        return;
                    }

                    if (result.status === 422 && result.data.errors) {
                        showErrors(form, result.data.errors);
                    } else if (result.data.message) {
                        setStatus(statusEl, result.data.message, 'error');
                    } else {
                        setStatus(statusEl, networkError, 'error');
                    }

                    submitBtn.disabled = false;
                    submitBtn.textContent = defaultLabel;
                })
                .catch(function () {
                    setStatus(statusEl, networkError, 'error');
                    submitBtn.disabled = false;
                    submitBtn.textContent = defaultLabel;
                });
        });
    }

    function formatThousands(value) {
        var digits = value.replace(/\D/g, '');
        if (!digits) {
            return '';
        }
        return digits.replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
    }

    function attachBudgetFormatter(form) {
        var input = form.querySelector('input[name="budget"]');
        if (!input) {
            return;
        }

        if (input.value) {
            input.value = formatThousands(input.value);
        }

        input.addEventListener('input', function () {
            var cursor = input.selectionStart;
            var digitsBeforeCursor = input.value.slice(0, cursor).replace(/\D/g, '').length;

            input.value = formatThousands(input.value);

            var pos = 0;
            var seen = 0;
            while (pos < input.value.length && seen < digitsBeforeCursor) {
                if (/\d/.test(input.value[pos])) {
                    seen++;
                }
                pos++;
            }
            input.setSelectionRange(pos, pos);
        });
    }

    function clearErrors(form) {
        form.querySelectorAll('.field-error').forEach(function (el) {
            el.textContent = '';
        });
        form.querySelectorAll('.field-invalid').forEach(function (el) {
            el.classList.remove('field-invalid');
        });
    }

    function showErrors(form, errors) {
        Object.keys(errors).forEach(function (field) {
            var el = form.querySelector('[data-error-for="' + field + '"]');
            if (!el) {
                return;
            }
            el.textContent = errors[field][0];
            var wrap = el.closest('.form-field');
            if (wrap) {
                wrap.classList.add('field-invalid');
            }
        });
    }

    function setStatus(el, message, type) {
        if (!el) {
            return;
        }
        el.textContent = message || '';
        el.classList.remove('is-success', 'is-error');
        if (type === 'success') {
            el.classList.add('is-success');
        } else if (type === 'error') {
            el.classList.add('is-error');
        }
    }
})();
