@extends('layout')

@section('title', __('Contact | blckt. — Get a Quote for Your Website'))
@section('meta_description', __('Tell me about your project and I’ll reply within 24 hours. Email hello@blckt.hu, call +36 30 255 2432, or use the form. No bots, no runaround.'))

@push('styles')
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/work.css') }}">
    <link rel="stylesheet" href="{{ \App\Support\Asset::url('assets/css/editorial.css') }}">
@endpush

@push('schema')
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'ContactPage',
        'url' => url('/contact'),
        'isPartOf' => ['@id' => url('/').'#website'],
        'mainEntity' => ['@id' => url('/').'#studio'],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('content')
    <div class="shell" style="padding-top: calc(72px + var(--space-10))">
        @include('partials.breadcrumbs', ['trail' => [['label' => __('Contact')]]])
    </div>

    <section class="page-head shell" aria-labelledby="contact-title">
        <span class="t8 page-head-eyebrow ink-faint">{{ __('Contact') }}</span>
        <h1 class="t1 page-head-title optical-left" id="contact-title">
            <span class="split-line" data-split="words">{{ __('Got a project') }}</span>
            <span class="split-line" data-split="words">{{ __('in mind?') }}</span>
        </h1>
    </section>

    <section class="shell" style="padding-bottom: var(--space-30)">
        <div class="contact-grid">
            <div data-reveal>
                <p class="t5 contact-lede" style="margin-top: 0">{{ __('Whether it’s a t-shirt drop or a full site build, tell me what you’re after and I’ll take it from there. No bots, no runaround — just me reading your message and getting back to you.') }}</p>

                <dl class="contact-channels">
                    <div class="contact-channel">
                        <dt class="contact-channel-label">{{ __('Email') }}</dt>
                        <dd><a href="mailto:hello@blckt.hu" class="contact-channel-value link-underline">hello@blckt.hu</a></dd>
                    </div>
                    <div class="contact-channel">
                        <dt class="contact-channel-label">{{ __('Phone') }}</dt>
                        <dd><a href="tel:+36302552432" class="contact-channel-value link-underline">+36 30 255 2432</a></dd>
                    </div>
                    <div class="contact-channel">
                        <dt class="contact-channel-label">{{ __('Based in') }}</dt>
                        <dd class="contact-channel-value">{{ __('Budapest, Hungary') }}</dd>
                    </div>
                    <div class="contact-channel">
                        <dt class="contact-channel-label">{{ __('Response time') }}</dt>
                        <dd class="contact-channel-value">{{ __('Within 24 hours') }}</dd>
                    </div>
                </dl>

                @include('partials.social-links', ['variant' => 'inline'])
            </div>

            <div class="contact-card" data-reveal style="--reveal-index: 1">
                @include('partials.contact-form')
            </div>
        </div>
    </section>
@endsection
