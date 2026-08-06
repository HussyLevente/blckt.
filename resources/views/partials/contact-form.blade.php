<form class="contact-form">
    <label class="form-field">
        <span class="form-label">{{ __('Full name') }}</span>
        <input type="text" name="full_name" placeholder="{{ __('John Doe') }}">
    </label>
    <label class="form-field">
        <span class="form-label">{{ __('Email address') }}</span>
        <input type="email" name="email" placeholder="{{ __('john@example.com') }}">
    </label>
    <label class="form-field">
        <span class="form-label">{{ __('Budget') }}</span>
        <div class="input-suffix-wrap">
            <input type="number" name="budget" placeholder="500 000">
            <span class="input-suffix">Ft</span>
        </div>
    </label>
    <label class="form-field">
        <span class="form-label">{{ __('Tell us more') }}</span>
        <textarea name="message" placeholder="{{ __('Tell us about your project...') }}" rows="5"></textarea>
    </label>
    <button type="submit" class="btn-pill form-submit">{{ __('send') }}</button>
</form>
