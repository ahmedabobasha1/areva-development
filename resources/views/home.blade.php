@extends('layouts.app')

@section('content')
  <section class="hero" aria-roledescription="carousel" aria-label="Featured articles">
    <div class="hero-slider" data-hero-slider>
      @forelse($heroSlides as $index => $slide)
        <article class="hero-slide {{ $index === 0 ? 'is-active' : '' }}" data-slide="{{ $index }}" aria-hidden="{{ $index === 0 ? 'false' : 'true' }}">
          <div class="hero-content">
            <span class="hero-label">{{ app()->getLocale() === 'ar' ? 'مقال مميز' : 'Featured Article' }}</span>
            <h1>{{ $slide->getTranslation('title', app()->getLocale()) }}</h1>
            <p>{{ $slide->getTranslation('subtitle', app()->getLocale()) }}</p>
            @php
              $ctaHref = $slide->cta_url;
              if (! $ctaHref && $slide->article) {
                  $ctaHref = url('/'.app()->getLocale().'/blog/'.$slide->article->getTranslation('slug', app()->getLocale()));
              }
            @endphp
            @if($ctaHref)
              <a href="{{ $ctaHref }}" class="btn-hero">
                {{ $slide->getTranslation('cta_label', app()->getLocale()) ?: (app()->getLocale() === 'ar' ? 'اقرأ المقال' : 'Read the Article') }}
                <span class="btn-arrow" aria-hidden="true">→</span>
              </a>
            @endif
          </div>
          <div class="hero-media">
            <img src="{{ $slide->getFirstMediaUrl('image') ?: asset('assets/images/hero.jpg') }}" alt="{{ $slide->getTranslation('title', app()->getLocale()) }}" width="900" height="700" loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
            @if($slide->article)
              <span class="hero-read-time"><span class="clock" aria-hidden="true">◷</span> {{ $slide->article->read_time_minutes }} MIN READ</span>
            @endif
          </div>
        </article>
      @empty
        <article class="hero-slide is-active" data-slide="0" aria-hidden="false">
          <div class="hero-content">
            <span class="hero-label">Areva Development</span>
            <h1>{{ config('app.name') }}</h1>
            <p>{{ app()->getLocale() === 'ar' ? 'رؤى عقارية في مصر' : 'Real estate insights in Egypt' }}</p>
          </div>
          <div class="hero-media">
            <img src="{{ asset('assets/images/hero.jpg') }}" alt="{{ config('app.name') }}" width="900" height="700" loading="eager">
          </div>
        </article>
      @endforelse
    </div>
    @if($heroSlides->count() > 1)
      <div class="hero-nav" role="tablist" aria-label="Hero slides">
        @foreach($heroSlides as $index => $slide)
          <button type="button" class="hero-nav-btn {{ $index === 0 ? 'is-active' : '' }}" role="tab" aria-selected="{{ $index === 0 ? 'true' : 'false' }}" data-goto="{{ $index }}">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</button>
        @endforeach
      </div>
    @endif
  </section>

  <section class="latest-articles" aria-labelledby="latest-heading">
    <div class="container">
      <div class="latest-articles-header">
        <div>
          <span class="section-label section-label--after">{{ app()->getLocale() === 'ar' ? 'أحدث المقالات' : 'Latest Articles' }}</span>
          <h2 id="latest-heading" class="section-title">{!! app()->getLocale() === 'ar' ? 'رؤى جديدة<br>من السوق' : 'Fresh Insights<br>From the Market' !!}</h2>
        </div>
        @if($categories->isNotEmpty())
          <a href="{{ url('/'.app()->getLocale().'/categories/'.$categories->first()->getTranslation('slug', app()->getLocale())) }}" class="view-all">
            {{ app()->getLocale() === 'ar' ? 'عرض كل المقالات' : 'View All Articles' }}
            <span class="view-all-arrow" aria-hidden="true"></span>
          </a>
        @endif
      </div>
      <div class="articles-layout">
        @if($featuredArticle)
          <article class="article-featured">
            <a href="{{ url('/'.app()->getLocale().'/blog/'.$featuredArticle->getTranslation('slug', app()->getLocale())) }}" class="article-featured-link">
              <div class="article-featured-media">
                <img src="{{ $featuredArticle->getFirstMediaUrl('cover') ?: asset('assets/images/villa-pool.jpg') }}" alt="{{ $featuredArticle->getTranslation('title', app()->getLocale()) }}" width="520" height="340" loading="lazy">
                <span class="featured-badge">{{ $featuredArticle->category?->getTranslation('name', app()->getLocale()) }}</span>
              </div>
              <div class="article-featured-body">
                <h3 class="card-title">{{ $featuredArticle->getTranslation('title', app()->getLocale()) }}</h3>
                <p class="card-meta">{{ optional($featuredArticle->published_at)->format('M j, Y') }} &middot; {{ $featuredArticle->read_time_minutes }} min read</p>
                <span class="featured-arrow" aria-hidden="true"></span>
              </div>
            </a>
          </article>
        @endif

        <div class="articles-grid-mini">
          @foreach($latestArticles as $article)
            <article class="article-mini">
              <a href="{{ url('/'.app()->getLocale().'/blog/'.$article->getTranslation('slug', app()->getLocale())) }}">
                <img src="{{ $article->getFirstMediaUrl('cover') ?: asset('assets/images/thumb-hero.jpg') }}" alt="{{ $article->getTranslation('title', app()->getLocale()) }}" width="110" height="90" loading="lazy">
                <div>
                  <span class="card-category">{{ $article->category?->getTranslation('name', app()->getLocale()) }}</span>
                  <h3 class="card-title">{{ $article->getTranslation('title', app()->getLocale()) }}</h3>
                  <p class="card-meta">{{ optional($article->published_at)->format('M j, Y') }} &middot; {{ $article->read_time_minutes }} min read</p>
                </div>
              </a>
            </article>
          @endforeach
        </div>
      </div>
    </div>
  </section>

  <section class="categories" aria-labelledby="categories-heading">
    <div class="container">
      <div class="categories-header">
        <div>
          <span class="section-label section-label--after">{{ app()->getLocale() === 'ar' ? 'التصنيفات' : 'Categories' }}</span>
          <h2 id="categories-heading" class="section-title">{{ app()->getLocale() === 'ar' ? 'تصفح حسب التصنيف' : 'Browse by Category' }}</h2>
        </div>
      </div>
      <div class="categories-slider" data-cat-slider>
        <div class="categories-track">
          @foreach($categories as $category)
            <a href="{{ url('/'.app()->getLocale().'/categories/'.$category->getTranslation('slug', app()->getLocale())) }}" class="category-card">
              <img src="{{ $category->getFirstMediaUrl('hero') ?: asset('assets/images/city-skyline.jpg') }}" alt="{{ $category->getTranslation('name', app()->getLocale()) }}" width="320" height="420" loading="lazy">
              <div class="category-overlay">
                <h3>{{ $category->getTranslation('name', app()->getLocale()) }}</h3>
                <span class="explore-link">{{ app()->getLocale() === 'ar' ? 'استكشف' : 'Explore' }}</span>
              </div>
            </a>
          @endforeach
        </div>
      </div>
      <div class="categories-controls">
        <button type="button" data-cat-prev aria-label="Previous">‹</button>
        <button type="button" data-cat-next aria-label="Next">›</button>
      </div>
    </div>
  </section>

  <section class="popular-topics" aria-labelledby="topics-heading">
    <div class="container">
      <span class="section-label">{{ app()->getLocale() === 'ar' ? 'مواضيع شائعة' : 'Explore Popular Topics' }}</span>
      <h2 id="topics-heading" class="section-title">{{ app()->getLocale() === 'ar' ? 'استكشف المواضيع الشائعة' : 'Explore Popular Topics' }}</h2>
      <div class="topics-grid">
        @foreach($popularTopics as $topic)
          <article class="topic-card">
            <div class="topic-body">
              <h3>{{ $topic->getTranslation('title', app()->getLocale()) }}</h3>
              <p>{{ $topic->getTranslation('excerpt', app()->getLocale()) }}</p>
              @php
                $topicUrl = $topic->cta_url;
                if (! $topicUrl && $topic->category) {
                    $topicUrl = url('/'.app()->getLocale().'/categories/'.$topic->category->getTranslation('slug', app()->getLocale()));
                }
              @endphp
              @if($topicUrl)
                <a href="{{ $topicUrl }}" class="explore-link">{{ $topic->getTranslation('cta_label', app()->getLocale()) ?: 'Explore' }}</a>
              @endif
            </div>
            <img class="topic-img" src="{{ $topic->getFirstMediaUrl('image') ?: asset('assets/images/villa-modern.jpg') }}" alt="{{ $topic->getTranslation('title', app()->getLocale()) }}" width="380" height="240" loading="lazy">
          </article>
        @endforeach
      </div>
    </div>
  </section>

  <section id="contact" class="contact" aria-labelledby="contact-heading">
    <div class="container">
      <div class="contact-info">
        <span class="section-label section-label--after">{{ app()->getLocale() === 'ar' ? 'تواصل معنا' : 'Contact Us' }}</span>
        <h2 id="contact-heading">{{ app()->getLocale() === 'ar' ? 'هل تبحث عن العقار المناسب؟' : 'Looking for the Right Property?' }}</h2>
        <p>{{ app()->getLocale() === 'ar' ? 'شارك بياناتك وسيساعدك فريقنا في استكشاف الفرص المناسبة.' : 'Share your details and our team will help you explore opportunities tailored to your goals.' }}</p>
      </div>
      <form class="contact-form" action="{{ route('contact.store', ['locale' => app()->getLocale()]) }}" method="post">
        @csrf
        <div class="form-row">
          <label>
            <span class="sr-only">Full Name</span>
            <input type="text" name="name" placeholder="Full Name" required>
          </label>
          <label>
            <span class="sr-only">Email Address</span>
            <input type="email" name="email" placeholder="Email Address" required>
          </label>
        </div>
        <div class="form-row">
          <label>
            <span class="sr-only">Phone Number</span>
            <input type="tel" name="phone" placeholder="Phone Number">
          </label>
          <label>
            <span class="sr-only">Subject</span>
            <input type="text" name="subject" placeholder="I'm interested in...">
          </label>
        </div>
        <label>
          <span class="sr-only">Your Message</span>
          <textarea name="message" placeholder="Tell us what you're looking for" rows="4"></textarea>
        </label>
        <button type="submit" class="btn-submit">Send Message <span aria-hidden="true">→</span></button>
      </form>
    </div>
  </section>
@endsection
