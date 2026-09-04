<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Support\LocaleUrl;
use App\Support\SeoBuilder;
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
            'seo' => SeoBuilder::forArticle($article, $locale),
        ]);
    }
}
