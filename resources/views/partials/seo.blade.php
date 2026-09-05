@php
  $seo = $seo ?? [];
  $title = $seo['title'] ?? config('app.name');
  $description = $seo['description'] ?? '';
  $canonical = $seo['canonical'] ?? url()->current();
  $robots = $seo['robots'] ?? 'index,follow';
  $ogType = $seo['og_type'] ?? 'website';
  $ogImage = $seo['og_image'] ?? asset('assets/images/hero.jpg');
  $siteName = $seo['site_name'] ?? config('app.name');
@endphp
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<link rel="canonical" href="{{ $canonical }}">
<meta name="robots" content="{{ $robots }}">

<meta property="og:type" content="{{ $ogType }}">
<meta property="og:title" content="{{ $seo['og_title'] ?? $title }}">
<meta property="og:description" content="{{ $seo['og_description'] ?? $description }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:site_name" content="{{ $siteName }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seo['og_title'] ?? $title }}">
<meta name="twitter:description" content="{{ $seo['og_description'] ?? $description }}">
<meta name="twitter:image" content="{{ $ogImage }}">

@foreach(($seo['hreflang'] ?? []) as $locale => $url)
  <link rel="alternate" hreflang="{{ $locale }}" href="{{ $url }}">
@endforeach

@foreach(($seo['json_ld'] ?? []) as $block)
  <script type="application/ld+json">{!! json_encode($block, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}</script>
@endforeach
