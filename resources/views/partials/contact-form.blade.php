<form class="contact-form" method="POST" action="{{ route('contact.submit') }}" novalidate>
    @csrf

    {{-- Robotcsapda: a valodi latogato sosem latja, a botok kitoltik. --}}
    <label class="field field-honeypot" aria-hidden="true" tabindex="-1">
        <span class="field-label">Website</span>
        <input type="text" name="website" tabindex="-1" autocomplete="off">
    </label>

    <label class="field">
        <span class="field-label">{{ __('Full name') }}</span>
        <input type="text" name="full_name" placeholder="{{ __('John Doe') }}" value="{{ old('full_name') }}" autocomplete="name" required>
        <span class="field-error" data-error-for="full_name">{{ $errors->first('full_name') }}</span>
    </label>

    <label class="field">
        <span class="field-label">{{ __('Email address') }}</span>
        <input type="email" name="email" placeholder="{{ __('john@example.com') }}" value="{{ old('email') }}" autocomplete="email" required>
        <span class="field-error" data-error-for="email">{{ $errors->first('email') }}</span>
    </label>

    <label class="field">
        <span class="field-label">{{ __('Budget (Ft)') }}</span>
        <input type="text" inputmode="numeric" name="budget" placeholder="500 000" value="{{ old('budget') }}" autocomplete="off">
        <span class="field-error" data-error-for="budget">{{ $errors->first('budget') }}</span>
    </label>

    <label class="field">
        <span class="field-label">{{ __('Tell me more') }}</span>
        <textarea name="message" placeholder="{{ __('Tell me about your project...') }}" rows="5" required>{{ old('message') }}</textarea>
        <span class="field-error" data-error-for="message">{{ $errors->first('message') }}</span>
    </label>

    <p
        class="form-status @if (session('contact_success')) is-success @elseif (session('contact_error')) is-error @endif"
        data-form-status
        data-network-error="{{ __('Something went wrong. Please try again or email hello@blckt.hu directly.') }}"
        role="status"
        aria-live="polite"
    >{{ session('contact_success') ?? session('contact_error') }}</p>

    <button
        type="submit"
        class="btn btn-solid form-submit"
        data-label-default="{{ __('Send message') }}"
        data-label-sending="{{ __('Sending...') }}"
        data-label-sent="{{ __('Sent') }}"
    >{{ __('Send message') }}</button>
</form>

@push('scripts')
    <script src="{{ \App\Support\Asset::url('assets/js/contact-form.js') }}" defer></script>
@endpush
