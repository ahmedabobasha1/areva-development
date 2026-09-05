<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Support\LocaleUrl;
use App\Support\SeoBuilder;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function show(string $locale, Category $category): View
    {
        $articles = Article::query()
            ->published()
            ->whereIn('category_id', $category->selfAndDescendantIds())
            ->with('category')
            ->latest('published_at')
            ->get();

        return view('categories.show', [
            'category' => $category,
            'children' => $category->children,
            'articles' => $articles,
            'langSwitchUrls' => [
                'en' => LocaleUrl::category($category->getTranslation('slug', 'en'), 'en'),
                'ar' => LocaleUrl::category($category->getTranslation('slug', 'ar'), 'ar'),
            ],
            'seo' => SeoBuilder::forCategory($category, $locale),
        ]);
    }
}
