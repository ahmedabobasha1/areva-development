@php
  $locale = app()->getLocale();
  $categories = $navCategories ?? \App\Models\Category::query()->active()->orderBy('sort')->get();
  $homeUrl = url('/'.$locale);
  $glareUrl = \App\Support\LocaleUrl::glare($locale);
  $homeLabel = $locale === 'ar' ? 'الرئيسية' : 'Home';
  $glareLabel = 'GLARE';
@endphp
<header class="site-header" role="banner">
  <div class="container">
    <a href="{{ $homeUrl }}" class="logo" aria-label="{{ config('app.name') }} {{ $homeLabel }}">
      <img src="{{ asset('assets/images/logo-white.png') }}" alt="{{ config('app.name') }}" width="180" height="56" loading="eager">
    </a>
    <button class="nav-toggle" type="button" aria-label="Toggle navigation" aria-expanded="false">&#9776;</button>
    <nav class="primary-nav" aria-label="Main navigation">
      <a href="{{ $homeUrl }}" @if(request()->routeIs('home')) aria-current="page" @endif>{{ $homeLabel }}</a>
      <a href="{{ $glareUrl }}" @if(request()->routeIs('glare')) aria-current="page" @endif>{{ $glareLabel }}</a>
      @foreach($categories as $category)
        <a href="{{ url('/'.$locale.'/categories/'.$category->getTranslation('slug', $locale)) }}">
          {{ $category->getTranslation('name', $locale) }}
        </a>
      @endforeach
    </nav>
    @include('partials.lang-switcher')
  </div>
</header>
