<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Support\LocaleUrl;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function show(string $locale, string $slug): View
    {
        $article = Article::query()
            ->published()
            ->with('category')
            ->whereSlug($slug, $locale)
            ->firstOrFail();

        $related = Article::query()
            ->published()
            ->with('category')
            ->where('id', '!=', $article->id)
            ->when($article->category_id, fn ($q) => $q->where('category_id', $article->category_id))
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('articles.show', [
            'article' => $article,
            'related' => $related,
            'langSwitchUrls' => [
                'en' => LocaleUrl::article($article->getTranslation('slug', 'en'), 'en'),
                'ar' => LocaleUrl::article($article->getTranslation('slug', 'ar'), 'ar'),
            ],
            'seo' => [
                'title' => $article->getTranslation('meta_title', $locale) ?: $article->getTranslation('title', $locale),
                'description' => $article->getTranslation('meta_description', $locale) ?: $article->getTranslation('excerpt', $locale),
                'canonical' => LocaleUrl::article($article->getTranslation('slug', $locale), $locale),
                'og_type' => 'article',
                'og_image' => $article->getFirstMediaUrl('seo') ?: ($article->getFirstMediaUrl('cover') ?: asset('assets/images/hero.jpg')),
                'hreflang' => [
                    'en' => LocaleUrl::article($article->getTranslation('slug', 'en'), 'en'),
                    'ar' => LocaleUrl::article($article->getTranslation('slug', 'ar'), 'ar'),
                    'x-default' => LocaleUrl::article($article->getTranslation('slug', 'en'), 'en'),
                ],
            ],
        ]);
    }
}
