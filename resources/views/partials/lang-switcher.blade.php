@php
  $locale = app()->getLocale();
  $other = $locale === 'ar' ? 'en' : 'ar';
  $switchUrl = $langSwitchUrls[$other] ?? url('/'.$other);
@endphp
<div class="lang-switcher" role="group" aria-label="Language switcher">
  <a href="{{ $langSwitchUrls['en'] ?? url('/en') }}" class="lang-btn {{ $locale === 'en' ? 'is-active' : '' }}" hreflang="en" @if($locale === 'en') aria-current="true" @endif>EN</a>
  <span class="lang-divider" aria-hidden="true"></span>
  <a href="{{ $langSwitchUrls['ar'] ?? url('/ar') }}" class="lang-btn {{ $locale === 'ar' ? 'is-active' : '' }}" hreflang="ar" @if($locale === 'ar') aria-current="true" @endif>عربي</a>
</div>
