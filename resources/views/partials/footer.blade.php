@php
  $locale = app()->getLocale();
  $site = \App\Models\Setting::getValue('site', []);
  $social = \App\Models\Setting::getValue('social', []);
  $categories = $navCategories ?? \App\Models\Category::query()->active()->orderBy('sort')->get();
  $homeUrl = url('/'.$locale);
  $footerBlurb = $site['footer_blurb'][$locale] ?? ($site['footer_blurb']['en'] ?? '');
  $email = $site['email'] ?? 'marketing@arevadevelopment.com';
  $phone = $site['phone'] ?? '19030';
  $phoneHref = preg_replace('/\s+/', '', (string) $phone);
  $whatsappDigits = preg_replace('/\D+/', '', (string) ($site['whatsapp'] ?? $social['whatsapp'] ?? $phone));
  if (str_starts_with((string) $whatsappDigits, '0')) {
      $whatsappDigits = '20'.substr((string) $whatsappDigits, 1);
  }
  $whatsappUrl = filled($whatsappDigits) ? 'https://wa.me/'.$whatsappDigits : null;
  $callLabel = $locale === 'ar' ? 'اتصال' : 'Call';
  $whatsappLabel = $locale === 'ar' ? 'واتساب' : 'WhatsApp';
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
        <a class="footer-contact-card" href="tel:{{ $phoneHref }}">
          <span class="footer-contact-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
              <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
            </svg>
          </span>
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
<nav class="quick-contact" aria-label="{{ $locale === 'ar' ? 'تواصل سريع' : 'Quick contact' }}">
  @if(filled($phoneHref))
    <a class="quick-contact-call" href="tel:{{ $phoneHref }}" aria-label="{{ $callLabel }}">
      <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
        <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
      </svg>
    </a>
  @endif
  @if(filled($whatsappUrl))
    <a class="quick-contact-whatsapp" href="{{ $whatsappUrl }}" aria-label="{{ $whatsappLabel }}" target="_blank" rel="noopener noreferrer">
      <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/>
      </svg>
    </a>
  @endif
</nav>
