<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function show(string $locale, string $slug): View
    {
        app()->setLocale($locale);

        $article = Article::query()
            ->published()
            ->with('category')
            ->get()
            ->first(fn (Article $article) => $article->getTranslation('slug', $locale) === $slug
                || $article->getTranslation('slug', 'en') === $slug
                || $article->getTranslation('slug', 'ar') === $slug);

        abort_if($article === null, 404);

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
            'navCategories' => Category::query()->active()->orderBy('sort')->get(),
            'langSwitchUrls' => [
                'en' => url('/en/blog/'.$article->getTranslation('slug', 'en')),
                'ar' => url('/ar/blog/'.$article->getTranslation('slug', 'ar')),
            ],
            'seo' => [
                'title' => $article->getTranslation('meta_title', $locale) ?: $article->getTranslation('title', $locale),
                'description' => $article->getTranslation('meta_description', $locale) ?: $article->getTranslation('excerpt', $locale),
                'canonical' => url('/'.$locale.'/blog/'.$article->getTranslation('slug', $locale)),
                'og_type' => 'article',
                'og_image' => $article->getFirstMediaUrl('seo') ?: ($article->getFirstMediaUrl('cover') ?: asset('assets/images/hero.jpg')),
                'hreflang' => [
                    'en' => url('/en/blog/'.$article->getTranslation('slug', 'en')),
                    'ar' => url('/ar/blog/'.$article->getTranslation('slug', 'ar')),
                    'x-default' => url('/en/blog/'.$article->getTranslation('slug', 'en')),
                ],
            ],
        ]);
    }
}
