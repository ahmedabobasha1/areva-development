@php
  $title = filled($title ?? null) ? (string) $title : 'Video';
@endphp
<figure class="article-video-embed">
  @if(($embed['type'] ?? null) === 'iframe')
    <iframe
      src="{{ $embed['src'] }}"
      title="{{ $title }}"
      loading="lazy"
      allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
      allowfullscreen
      referrerpolicy="strict-origin-when-cross-origin"
    ></iframe>
  @elseif(($embed['type'] ?? null) === 'video')
    <video controls playsinline preload="metadata" title="{{ $title }}">
      <source src="{{ $embed['src'] }}">
    </video>
  @endif
</figure>
