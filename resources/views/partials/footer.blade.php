@php
  $locale = app()->getLocale();
  $site = \App\Models\Setting::getValue('site', []);
  $social = \App\Models\Setting::getValue('social', []);
  $categories = $navCategories ?? \App\Models\Category::query()->active()->orderBy('sort')->get();
  $homeUrl = url('/'.$locale);
  $footerBlurb = $site['footer_blurb'][$locale] ?? ($site['footer_blurb']['en'] ?? '');
  $email = $site['email'] ?? 'info@areva.com.eg';
  $phone = $site['phone'] ?? '+20 100 323 4567';
  $address = $site['address'][$locale] ?? ($site['address']['en'] ?? 'New Cairo, Cairo, Egypt');
@endphp
<footer class="site-footer" role="contentinfo">
  <div class="container">
    <div class="footer-top">
      <div class="footer-brand">
        <a href="{{ $homeUrl }}" class="logo" aria-label="{{ config('app.name') }} Home">
          <img src="{{ asset('assets/images/logo-white.png') }}" alt="{{ config('app.name') }}" width="180" height="56" loading="lazy">
        </a>
        <p>{{ $footerBlurb }}</p>
        <div class="footer-social">
          <a href="{{ $social['instagram'] ?? '#' }}" aria-label="Instagram">IG</a>
          <a href="{{ $social['facebook'] ?? '#' }}" aria-label="Facebook">FB</a>
          <a href="{{ $social['twitter'] ?? '#' }}" aria-label="Twitter">X</a>
          <a href="{{ $social['linkedin'] ?? '#' }}" aria-label="LinkedIn">in</a>
        </div>
      </div>

      <div class="footer-links">
        <div class="footer-col">
          <h4>{{ $locale === 'ar' ? 'التصنيفات' : 'Categories' }}</h4>
          @foreach($categories as $category)
            <a href="{{ url('/'.$locale.'/categories/'.$category->getTranslation('slug', $locale)) }}">
              {{ $category->getTranslation('name', $locale) }}
            </a>
          @endforeach
        </div>
        <div class="footer-col">
          <h4>{{ $locale === 'ar' ? 'مواضيع شائعة' : 'Popular Topics' }}</h4>
          @foreach($categories->take(5) as $category)
            <a href="{{ url('/'.$locale.'/categories/'.$category->getTranslation('slug', $locale)) }}">
              {{ $category->getTranslation('name', $locale) }}
            </a>
          @endforeach
        </div>
      </div>

      <div class="footer-contact">
        <h4>{{ $locale === 'ar' ? 'تواصل' : 'Contact' }}</h4>
        <a class="footer-contact-card" href="mailto:{{ $email }}">
          <span class="footer-contact-icon" aria-hidden="true">✉</span>
          <span>
            <strong>Email</strong>
            {{ $email }}
          </span>
        </a>
        <a class="footer-contact-card" href="tel:{{ preg_replace('/\s+/', '', $phone) }}">
          <span class="footer-contact-icon" aria-hidden="true">☎</span>
          <span>
            <strong>Phone</strong>
            {{ $phone }}
          </span>
        </a>
        <div class="footer-contact-card">
          <span class="footer-contact-icon" aria-hidden="true">⌖</span>
          <span>
            <strong>Location</strong>
            {{ $address }}
          </span>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      <span>&copy; {{ date('Y') }} {{ config('app.name') }}. All Rights Reserved.</span>
      <nav class="footer-legal" aria-label="Legal">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Use</a>
        <a href="{{ url('/sitemap.xml') }}">Sitemap</a>
      </nav>
      <a href="#" class="back-to-top" aria-label="Back to top">↑ Top</a>
    </div>
  </div>
</footer>
