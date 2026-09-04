<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Support\LocaleUrl;
use App\Support\SeoBuilder;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function show(string $locale, string $slug): View
    {
        $category = Category::query()
            ->active()
            ->whereSlug($slug, $locale)
            ->firstOrFail();

        $articles = Article::query()
            ->published()
            ->where('category_id', $category->id)
            ->latest('published_at')
            ->get();

        return view('categories.show', [
            'category' => $category,
            'articles' => $articles,
            'langSwitchUrls' => [
                'en' => LocaleUrl::category($category->getTranslation('slug', 'en'), 'en'),
                'ar' => LocaleUrl::category($category->getTranslation('slug', 'ar'), 'ar'),
            ],
            'seo' => SeoBuilder::forCategory($category, $locale),
        ]);
    }
}
