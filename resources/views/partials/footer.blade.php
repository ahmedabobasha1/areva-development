@php
  $locale = app()->getLocale();
  $site = \App\Models\Setting::getValue('site', []);
  $social = \App\Models\Setting::getValue('social', []);
  $categories = $navCategories ?? \App\Models\Category::query()->active()->orderBy('sort')->get();
  $homeUrl = url('/'.$locale);
  $footerBlurb = $site['footer_blurb'][$locale] ?? ($site['footer_blurb']['en'] ?? '');
  $email = $site['email'] ?? 'marketing@arevadevelopment.com';
  $phone = $site['phone'] ?? '19030';
  $localize = function (array $translations) use ($locale): string {
      return $translations[$locale] ?? ($translations['en'] ?? '');
  };
  $offices = is_array($site['offices'] ?? null) ? $site['offices'] : [];
  $defaultHeadOffice = [
      'en' => 'Sheraton Heliopolis, Building 10, Al Moltaqa Al Araby st.',
      'ar' => '10 شارع الملتقي العربي - شيراتون - مصر الجديدة - القاهرة',
  ];
  $headOfficeLabel = $localize(\Illuminate\Support\Arr::get($offices, 'head_office.label', ['en' => 'Head Office', 'ar' => 'الفرع الرئيسي']));
  $headOfficeAddress = $localize(
      is_array(\Illuminate\Support\Arr::get($offices, 'head_office.address'))
          ? \Illuminate\Support\Arr::get($offices, 'head_office.address')
          : (is_array($site['address'] ?? null) ? $site['address'] : $defaultHeadOffice)
  );
  $salesLabel = $localize(\Illuminate\Support\Arr::get($offices, 'sales.label', ['en' => 'Sales Offices', 'ar' => 'مكاتب المبيعات']));
  $salesLocations = \Illuminate\Support\Arr::get($offices, 'sales.locations', [
      [
          'label' => ['en' => 'Sheraton', 'ar' => 'شيراتون'],
          'address' => [
              'en' => 'Sheraton Heliopolis, Building 10, Al Moltaqa Al Araby st.',
              'ar' => '10 شارع الملتقي العربي - شيراتون - مصر الجديدة - القاهرة',
          ],
      ],
      [
          'label' => ['en' => 'New Cairo', 'ar' => 'التجمع الخامس'],
          'address' => [
              'en' => 'Plot no 158, 90th North, New Cairo, Egypt',
              'ar' => 'القطعة رقم 158، شارع التسعين الشمالي، القاهرة الجديدة، مصر',
          ],
      ],
  ]);
  $socialLinks = [
      ['url' => $social['instagram'] ?? null, 'label' => 'Instagram', 'icon' => 'instagram'],
      ['url' => $social['facebook'] ?? null, 'label' => 'Facebook', 'icon' => 'facebook'],
      ['url' => $social['youtube'] ?? null, 'label' => 'YouTube', 'icon' => 'youtube'],
      ['url' => $social['twitter'] ?? null, 'label' => 'Twitter', 'icon' => 'twitter'],
      ['url' => $social['linkedin'] ?? null, 'label' => 'LinkedIn', 'icon' => 'linkedin'],
  ];
@endphp
<footer class="site-footer" role="contentinfo">
  <div class="container">
    <div class="footer-top">
      <div class="footer-brand">
        <a href="{{ $homeUrl }}" class="logo" aria-label="{{ config('app.name') }} {{ $locale === 'ar' ? 'الرئيسية' : 'Home' }}">
          <img src="{{ asset('assets/images/logo-white.png') }}" alt="{{ config('app.name') }}" width="180" height="56" loading="lazy">
        </a>
        <p>{{ $footerBlurb }}</p>
        <div class="footer-social">
          @foreach($socialLinks as $link)
            @if(filled($link['url']) && $link['url'] !== '#')
              <a href="{{ $link['url'] }}" aria-label="{{ $link['label'] }}" target="_blank" rel="noopener noreferrer">
                <svg class="footer-social-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                  @switch($link['icon'])
                    @case('instagram')
                      <path d="M7.75 2h8.5A5.75 5.75 0 0 1 22 7.75v8.5A5.75 5.75 0 0 1 16.25 22h-8.5A5.75 5.75 0 0 1 2 16.25v-8.5A5.75 5.75 0 0 1 7.75 2Zm0 1.5A4.25 4.25 0 0 0 3.5 7.75v8.5A4.25 4.25 0 0 0 7.75 20.5h8.5A4.25 4.25 0 0 0 20.5 16.25v-8.5A4.25 4.25 0 0 0 16.25 3.5h-8.5ZM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 1.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7ZM17.5 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2Z"/>
                      @break
                    @case('facebook')
                      <path d="M14.5 8.16V6.4c0-.83.17-1.16 1.34-1.16H17V2.14A18.4 18.4 0 0 0 14.4 2c-2.5 0-4.22 1.53-4.22 4.33v1.83H7.5v3.18h2.68V22h3.32V11.34h2.8l.42-3.18h-3.22Z"/>
                      @break
                    @case('youtube')
                      <path d="M23.5 6.19a3.02 3.02 0 0 0-2.12-2.14C19.54 3.5 12 3.5 12 3.5s-7.54 0-9.38.55A3.02 3.02 0 0 0 .5 6.19 31.6 31.6 0 0 0 0 12a31.6 31.6 0 0 0 .5 5.81 3.02 3.02 0 0 0 2.12 2.14c1.84.55 9.38.55 9.38.55s7.54 0 9.38-.55a3.02 3.02 0 0 0 2.12-2.14A31.6 31.6 0 0 0 24 12a31.6 31.6 0 0 0-.5-5.81ZM9.75 15.57V8.43L15.84 12l-6.09 3.57Z"/>
                      @break
                    @case('twitter')
                      <path d="M18.24 2.25h3.31l-7.23 8.26 8.5 11.24h-6.67l-5.22-6.82-5.98 6.82H1.25l7.73-8.84L.75 2.25h6.84l4.72 6.23 5.93-6.23Zm-1.16 17.52h1.83L7.08 4.13H5.12l11.96 15.64Z"/>
                      @break
                    @case('linkedin')
                      <path d="M6.54 8.75H3.56V20.5h2.98V8.75ZM5.05 3.5a1.73 1.73 0 1 0 .01 3.46 1.73 1.73 0 0 0-.01-3.46ZM20.5 20.5h-2.97v-5.71c0-1.36-.02-3.11-1.9-3.11-1.9 0-2.19 1.48-2.19 3.01V20.5h-2.97V8.75h2.85v1.6h.04c.4-.75 1.37-1.54 2.82-1.54 3.02 0 3.58 1.99 3.58 4.57V20.5Z"/>
                      @break
                  @endswitch
                </svg>
              </a>
            @endif
          @endforeach
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
            <strong>{{ $locale === 'ar' ? 'الهوت لاين' : 'Hotline' }}</strong>
            {{ $phone }}
          </span>
        </a>
        <div class="footer-contact-card footer-contact-card--offices">
          <span class="footer-contact-icon" aria-hidden="true">⌖</span>
          <div class="footer-offices">
            <div>
              <strong>{{ $headOfficeLabel }}</strong>
              {{ $headOfficeAddress }}
            </div>
            <div>
              <strong>{{ $salesLabel }}</strong>
              @foreach($salesLocations as $office)
                <span class="footer-office-line">
                  <span class="footer-office-name">{{ $localize($office['label'] ?? []) }}:</span>
                  {{ $localize($office['address'] ?? []) }}
                </span>
              @endforeach
            </div>
          </div>
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
