<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=AW-10985435576"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'AW-10985435576');
  </script>
  <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/favicon-32x32.png') }}">
  <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
  <meta name="theme-color" content="#0c2340">
  @include('partials.seo')
  <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
  @stack('head')
</head>
<body @class([($bodyClass ?? '') => filled($bodyClass ?? null)])>
  @unless($hideSiteHeader ?? false)
    @include('partials.header')
  @endunless

  <main>
    @yield('content')
  </main>

  @if (($bodyClass ?? null) === 'lp-home')
    @include('partials.quick-contact', [
      'whatsappOverride' => isset($glare) ? $glare->whatsappUrl() : null,
    ])
  @else
    @include('partials.footer')
  @endif

  @unless(($bodyClass ?? null) === 'lp-home')
    <script src="{{ asset('assets/js/main.js') }}" defer></script>
  @endunless
  @stack('scripts')
</body>
</html>
