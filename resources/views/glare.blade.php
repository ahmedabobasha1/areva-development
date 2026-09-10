@extends('layouts.app')

@section('content')
  @php
    $locale = app()->getLocale();
    $isAr = $locale === 'ar';
    /** @var \App\Models\GlareLanding $glare */
    $projectTypes = $glare->projectTypeOptions($locale);
    $heroTitle = nl2br(e($glare->localized('hero_title')));
  @endphp

  <section class="lp-hero" aria-label="{{ $isAr ? 'صفحة جليير' : 'GLARE landing' }}">
    <div class="lp-hero-media" aria-hidden="true">
      <img
        src="{{ $glare->heroImageUrl() }}"
        alt=""
        width="1920"
        height="1080"
        loading="eager"
        fetchpriority="high"
      >
    </div>
    <div class="lp-hero-shade" aria-hidden="true"></div>

    <div class="lp-hero-top">
      <a href="{{ route('glare', ['locale' => $locale]) }}" class="lp-logo" aria-label="GLARE">
        <img
          src="{{ $glare->logoUrl() }}"
          alt="GLARE — The Power of Business"
          width="180"
          height="102"
          loading="eager"
        >
      </a>
      <div class="lp-hero-actions">
        @include('partials.lang-switcher')
      </div>
    </div>

    <div class="lp-hero-grid">
      <div class="lp-hero-copy">
        <h1 class="lp-hero-title">{!! $heroTitle !!}</h1>
        <p class="lp-hero-lead">{{ $glare->localized('hero_lead') }}</p>
      </div>

      <div id="contact" class="lp-hero-form" aria-labelledby="lp-contact-heading">
        <header class="lp-contact-header">
          <span class="lp-eyebrow">{{ $glare->localized('contact_eyebrow') }}</span>
          <h2 id="lp-contact-heading" class="lp-contact-title">{{ $glare->localized('contact_title') }}</h2>
          <p class="lp-contact-lead">{{ $glare->localized('contact_lead') }}</p>
        </header>

        @if (session('status'))
          <p class="lp-form-status" role="status">{{ session('status') }}</p>
        @endif

        <form
          class="lp-form"
          action="{{ route('contact.store', ['locale' => $locale]) }}"
          method="post"
        >
          @csrf
          <input type="hidden" name="source_page" value="glare-landing">

          <div class="lp-form-row">
            <label class="lp-field">
              <span class="lp-field-label">{{ $isAr ? 'الاسم الكامل' : 'Full Name' }} *</span>
              <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                autocomplete="name"
                placeholder="{{ $isAr ? 'اكتب اسمك الكامل' : 'Enter your full name' }}"
              >
              @error('name') <span class="lp-field-error">{{ $message }}</span> @enderror
            </label>
            <label class="lp-field">
              <span class="lp-field-label">{{ $isAr ? 'رقم الهاتف' : 'Phone Number' }} *</span>
              <input
                type="tel"
                name="phone"
                value="{{ old('phone') }}"
                required
                autocomplete="tel"
                placeholder="{{ $isAr ? '01xxxxxxxxx' : '01xxxxxxxxx' }}"
              >
              @error('phone') <span class="lp-field-error">{{ $message }}</span> @enderror
            </label>
          </div>

          <div class="lp-form-row">
            <label class="lp-field">
              <span class="lp-field-label">{{ $isAr ? 'البريد الإلكتروني' : 'Email Address' }} *</span>
              <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="email"
                placeholder="{{ $isAr ? 'name@email.com' : 'name@email.com' }}"
              >
              @error('email') <span class="lp-field-error">{{ $message }}</span> @enderror
            </label>
            <label class="lp-field">
              <span class="lp-field-label">{{ $isAr ? 'نوع المشروع' : 'Project Type' }} *</span>
              <select name="subject" required>
                <option value="" disabled {{ old('subject') ? '' : 'selected' }}>
                  {{ $isAr ? 'اختر نوع المشروع' : 'Select project type' }}
                </option>
                @foreach ($projectTypes as $value => $label)
                  <option value="{{ $value }}" @selected(old('subject') === $value)>{{ $label }}</option>
                @endforeach
              </select>
              @error('subject') <span class="lp-field-error">{{ $message }}</span> @enderror
            </label>
          </div>

          <label class="lp-field">
            <span class="lp-field-label">{{ $isAr ? 'رسالتك' : 'Your Message' }} *</span>
            <textarea
              name="message"
              rows="3"
              required
              placeholder="{{ $isAr ? 'أخبرنا باختصار عن مشروعك' : 'Tell us briefly about your project' }}"
            >{{ old('message') }}</textarea>
            @error('message') <span class="lp-field-error">{{ $message }}</span> @enderror
          </label>

          <button type="submit" class="lp-submit">
            {{ $glare->localized('submit_label') }}
            <span aria-hidden="true">→</span>
          </button>

          <p class="lp-trust">
            <svg class="lp-trust-icon" viewBox="0 0 24 24" width="16" height="16" aria-hidden="true" focusable="false">
              <path fill="currentColor" d="M12 1 3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
            </svg>
            {{ $glare->localized('trust_line') }}
          </p>
        </form>
      </div>
    </div>
  </section>
@endsection

@push('head')
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
@endpush
