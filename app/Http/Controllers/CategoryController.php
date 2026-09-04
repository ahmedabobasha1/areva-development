<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function show(string $locale, string $slug): View
    {
        app()->setLocale($locale);

        $category = Category::query()
            ->active()
            ->get()
            ->first(fn (Category $category) => $category->getTranslation('slug', $locale) === $slug
                || $category->getTranslation('slug', 'en') === $slug
                || $category->getTranslation('slug', 'ar') === $slug);

        abort_if($category === null, 404);

        $articles = Article::query()
            ->published()
            ->where('category_id', $category->id)
            ->latest('published_at')
            ->get();

        return view('categories.show', [
            'category' => $category,
            'articles' => $articles,
            'navCategories' => Category::query()->active()->orderBy('sort')->get(),
            'langSwitchUrls' => [
                'en' => url('/en/categories/'.$category->getTranslation('slug', 'en')),
                'ar' => url('/ar/categories/'.$category->getTranslation('slug', 'ar')),
            ],
            'seo' => [
                'title' => $category->getTranslation('meta_title', $locale) ?: $category->getTranslation('name', $locale),
                'description' => $category->getTranslation('meta_description', $locale) ?: $category->getTranslation('description', $locale),
                'canonical' => url('/'.$locale.'/categories/'.$category->getTranslation('slug', $locale)),
                'hreflang' => [
                    'en' => url('/en/categories/'.$category->getTranslation('slug', 'en')),
                    'ar' => url('/ar/categories/'.$category->getTranslation('slug', 'ar')),
                    'x-default' => url('/en/categories/'.$category->getTranslation('slug', 'en')),
                ],
            ],
        ]);
    }
}
