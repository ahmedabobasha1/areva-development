@php
  /** @var \App\Models\GlareLanding|\App\Models\Glare1Landing $glare */
  $heroImageUrl = $glare->heroImageUrl();
  $fontCss = 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Manrope:wght@400;500;600;700&display=swap';
@endphp
<link rel="preload" as="image" href="{{ $heroImageUrl }}" fetchpriority="high">
@if (app()->getLocale() !== 'ar')
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preload" as="style" href="{{ $fontCss }}" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="{{ $fontCss }}"></noscript>
@endif
