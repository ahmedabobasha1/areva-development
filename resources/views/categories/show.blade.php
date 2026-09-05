@extends('layouts.app')

@section('content')
  <section class="category-hero" aria-labelledby="cat-page-heading">
    <div class="category-hero-bg" aria-hidden="true">
      <img src="{{ $category->getFirstMediaUrl('hero') ?: asset('assets/images/city-skyline.jpg') }}" alt="" width="1600" height="900" loading="eager">
    </div>
    <div class="container">
      <nav class="breadcrumb" aria-label="Breadcrumb">
        <ol>
          <li><a href="{{ url('/'.app()->getLocale()) }}">{{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Home' }}</a></li>
          @if($category->parent)
            <li>
              <a href="{{ \App\Support\LocaleUrl::category($category->parent->getTranslation('slug', app()->getLocale())) }}">
                {{ $category->parent->getTranslation('name', app()->getLocale()) }}
              </a>
            </li>
          @endif
          <li><span aria-current="page">{{ $category->getTranslation('name', app()->getLocale()) }}</span></li>
        </ol>
      </nav>
      <div class="category-hero-inner">
        <div class="category-hero-content">
          <span class="section-label">{{ app()->getLocale() === 'ar' ? 'تصنيف' : 'Category' }}</span>
          <h1 id="cat-page-heading">{{ $category->getTranslation('name', app()->getLocale()) }}</h1>
          <p>{{ $category->getTranslation('description', app()->getLocale()) }}</p>
          <div class="category-hero-meta">
            <span>{{ $articles->count() }} {{ app()->getLocale() === 'ar' ? 'مقالات' : 'Articles' }}</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  @if($children->isNotEmpty())
    <section class="categories" aria-labelledby="subcategories-heading">
      <div class="container">
        <div class="categories-carousel" data-cat-slider>
          <div class="categories-header">
            <div>
              <span class="section-label section-label--after">{{ app()->getLocale() === 'ar' ? 'تصنيفات فرعية' : 'Subcategories' }}</span>
              <h2 id="subcategories-heading" class="section-title">{{ app()->getLocale() === 'ar' ? 'تصفح التصنيفات الفرعية' : 'Browse Subcategories' }}</h2>
            </div>
            <div class="categories-controls" data-cat-controls hidden>
              <button type="button" class="cat-slider-btn" data-cat-prev aria-label="{{ app()->getLocale() === 'ar' ? 'السابق' : 'Previous' }}">
                <span aria-hidden="true">‹</span>
              </button>
              <button type="button" class="cat-slider-btn" data-cat-next aria-label="{{ app()->getLocale() === 'ar' ? 'التالي' : 'Next' }}">
                <span aria-hidden="true">›</span>
              </button>
            </div>
          </div>
          <div class="categories-slider">
            <div class="categories-track">
              @php
                $categoryFallbacks = [
                    'assets/images/city-skyline.jpg',
                    'assets/images/villa-modern.jpg',
                    'assets/images/villa-pool.jpg',
                    'assets/images/villa-glass.jpg',
                    'assets/images/villa-resort.jpg',
                    'assets/images/hero.jpg',
                ];
              @endphp
              @foreach($children as $child)
                @php
                  $heroUrl = $child->getFirstMediaUrl('hero');
                  if (! $heroUrl) {
                      $heroUrl = asset($categoryFallbacks[$loop->index % count($categoryFallbacks)]);
                  }
                @endphp
                <a href="{{ \App\Support\LocaleUrl::category($child->getTranslation('slug', app()->getLocale())) }}" class="category-card">
                  <img src="{{ $heroUrl }}" alt="{{ $child->getTranslation('name', app()->getLocale()) }}" width="320" height="420" loading="lazy">
                  <div class="category-overlay">
                    <h3>{{ $child->getTranslation('name', app()->getLocale()) }}</h3>
                    <span class="explore-link">{{ app()->getLocale() === 'ar' ? 'استكشف' : 'Explore' }}</span>
                  </div>
                </a>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif

  <section class="category-articles" aria-label="Category articles">
    <div class="container">
      <div class="cat-articles-grid">
        @forelse($articles as $article)
          <a href="{{ url('/'.app()->getLocale().'/blog/'.$article->getTranslation('slug', app()->getLocale())) }}" class="cat-article-card">
            <img src="{{ $article->getFirstMediaUrl('cover') ?: asset('assets/images/villa-pool.jpg') }}" alt="{{ $article->getTranslation('title', app()->getLocale()) }}" width="380" height="220" loading="lazy">
            <div class="cat-article-body">
              <span class="card-category">{{ $article->category?->getTranslation('name', app()->getLocale()) ?? $category->getTranslation('name', app()->getLocale()) }}</span>
              <h2 class="card-title">{{ $article->getTranslation('title', app()->getLocale()) }}</h2>
              <p class="card-meta">{{ optional($article->published_at)->format('M j, Y') }} &middot; {{ $article->read_time_minutes }} min read</p>
              <p class="card-excerpt">{{ $article->getTranslation('excerpt', app()->getLocale()) }}</p>
            </div>
          </a>
        @empty
          <p>{{ app()->getLocale() === 'ar' ? 'لا توجد مقالات بعد.' : 'No articles yet.' }}</p>
        @endforelse
      </div>
    </div>
  </section>

  <section class="article-cta" aria-labelledby="category-form-heading">
    <div class="container">
      <div class="article-cta-grid">
        <div class="article-cta-info">
          <span class="section-label section-label--after">{{ app()->getLocale() === 'ar' ? 'استشارة متخصصة' : 'Get Expert Advice' }}</span>
          <h2 id="category-form-heading">{{ app()->getLocale() === 'ar' ? 'هل تبحث عن العقار المناسب؟' : 'Looking for the Right Property?' }}</h2>
          <p>{{ app()->getLocale() === 'ar' ? 'شارك بياناتك وسيتواصل معك فريقنا.' : 'Share your details and our team will help you explore opportunities.' }}</p>
        </div>
        <form class="contact-form" action="{{ route('contact.store', ['locale' => app()->getLocale()]) }}" method="post">
          @csrf
          <input type="hidden" name="source_page" value="category:{{ $category->getTranslation('slug', app()->getLocale()) }}">
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
@endsection
