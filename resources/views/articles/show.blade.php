@extends('layouts.app')

@section('content')
  <section class="article-hero-section">
    <div class="article-hero-bg" aria-hidden="true">
      <img src="{{ $article->getFirstMediaUrl('cover') ?: asset('assets/images/hero.jpg') }}" alt="" width="1600" height="900" loading="eager">
    </div>
    <div class="container">
      <nav class="breadcrumb" aria-label="Breadcrumb">
        <ol>
          <li><a href="{{ url('/'.app()->getLocale()) }}">Home</a></li>
          @if($article->category)
            <li><a href="{{ url('/'.app()->getLocale().'/categories/'.$article->category->getTranslation('slug', app()->getLocale())) }}">{{ $article->category->getTranslation('name', app()->getLocale()) }}</a></li>
          @endif
          <li><span aria-current="page">Article</span></li>
        </ol>
      </nav>
      <div class="article-hero-content">
        <span class="section-label">{{ $article->category?->getTranslation('name', app()->getLocale()) }}</span>
        <h1>{{ $article->getTranslation('title', app()->getLocale()) }}</h1>
        <div class="article-hero-meta">
          <time datetime="{{ optional($article->published_at)->toDateString() }}">{{ optional($article->published_at)->format('M j, Y') }}</time>
          <span>{{ $article->read_time_minutes }} min read</span>
          <span>{{ config('app.name') }}</span>
        </div>
      </div>
    </div>
  </section>

  <article class="article-detail">
    <div class="container">
      <div class="article-content-card">
        <figure class="article-cover">
          <img src="{{ $article->getFirstMediaUrl('cover') ?: asset('assets/images/villa-modern.jpg') }}" alt="{{ $article->getTranslation('title', app()->getLocale()) }}" width="1100" height="560" loading="lazy">
        </figure>
        <div class="article-body">
          @if($article->getTranslation('excerpt', app()->getLocale()))
            <p class="article-lead">{{ $article->getTranslation('excerpt', app()->getLocale()) }}</p>
          @endif
          {!! $article->getTranslation('body', app()->getLocale()) !!}
        </div>
      </div>
    </div>
  </article>

  <section class="article-cta" aria-labelledby="article-form-heading">
    <div class="container">
      <div class="article-cta-grid">
        <div class="article-cta-info">
          <span class="section-label section-label--after">{{ app()->getLocale() === 'ar' ? 'استشارة متخصصة' : 'Get Expert Advice' }}</span>
          <h2 id="article-form-heading">{{ app()->getLocale() === 'ar' ? 'مهتم بعقارات القاهرة الجديدة؟' : 'Interested in this topic?' }}</h2>
          <p>{{ app()->getLocale() === 'ar' ? 'اترك بياناتك وسيتواصل فريقنا معك.' : 'Share your details and our team will help you find the right opportunity.' }}</p>
        </div>
        <form class="contact-form" action="{{ route('contact.store', ['locale' => app()->getLocale()]) }}" method="post">
          @csrf
          <input type="hidden" name="source_page" value="article:{{ $article->getTranslation('slug', app()->getLocale()) }}">
          <div class="form-row">
            <label><span class="sr-only">Full Name</span><input type="text" name="name" placeholder="Full Name" required></label>
            <label><span class="sr-only">Email</span><input type="email" name="email" placeholder="Email Address" required></label>
          </div>
          <div class="form-row">
            <label><span class="sr-only">Phone</span><input type="tel" name="phone" placeholder="Phone Number"></label>
            <label><span class="sr-only">Subject</span><input type="text" name="subject" placeholder="I'm interested in..."></label>
          </div>
          <label><span class="sr-only">Message</span><textarea name="message" rows="4" placeholder="Tell us what you're looking for"></textarea></label>
          <button type="submit" class="btn-submit">Send Message <span aria-hidden="true">→</span></button>
        </form>
      </div>
    </div>
  </section>

  @if($related->isNotEmpty())
    <section class="related-articles" aria-labelledby="related-heading">
      <div class="container">
        <div class="related-header">
          <span class="section-label section-label--after">{{ app()->getLocale() === 'ar' ? 'استمر في القراءة' : 'Keep Reading' }}</span>
          <h2 id="related-heading" class="section-title">{{ app()->getLocale() === 'ar' ? 'مقالات ذات صلة' : 'Related Articles' }}</h2>
        </div>
        <div class="related-grid">
          @foreach($related as $item)
            <a href="{{ url('/'.app()->getLocale().'/blog/'.$item->getTranslation('slug', app()->getLocale())) }}" class="related-card">
              <img src="{{ $item->getFirstMediaUrl('cover') ?: asset('assets/images/villa-glass.jpg') }}" alt="{{ $item->getTranslation('title', app()->getLocale()) }}" width="380" height="220" loading="lazy">
              <div class="related-card-body">
                <span class="card-category">{{ $item->category?->getTranslation('name', app()->getLocale()) }}</span>
                <h3 class="card-title">{{ $item->getTranslation('title', app()->getLocale()) }}</h3>
                <p class="card-meta">{{ optional($item->published_at)->format('M j, Y') }} · {{ $item->read_time_minutes }} min read</p>
              </div>
            </a>
          @endforeach
        </div>
      </div>
    </section>
  @endif
@endsection
