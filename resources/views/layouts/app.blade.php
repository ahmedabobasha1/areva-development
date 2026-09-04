<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @include('partials.seo')
  <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
  @stack('head')
</head>
<body>
  @include('partials.header')

  <main>
    @yield('content')
  </main>

  @include('partials.footer')

  <script src="{{ asset('assets/js/main.js') }}" defer></script>
  @stack('scripts')
</body>
</html>
