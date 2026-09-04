<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\HeroSlide;
use App\Models\PopularTopic;
use App\Models\Setting;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(string $locale): View
    {
        app()->setLocale($locale);

        $seoDefaults = Setting::getValue('seo_defaults', []);

        return view('home', [
            'heroSlides' => HeroSlide::query()->active()->with('article')->orderBy('sort')->get(),
            'categories' => Category::query()->active()->orderBy('sort')->get(),
            'featuredArticle' => Article::query()->published()->featured()->with('category')->latest('published_at')->first()
                ?? Article::query()->published()->with('category')->latest('published_at')->first(),
            'latestArticles' => Article::query()->published()->with('category')->latest('published_at')->take(6)->get(),
            'popularTopics' => PopularTopic::query()->active()->with('category')->orderBy('sort')->get(),
            'navCategories' => Category::query()->active()->orderBy('sort')->get(),
            'langSwitchUrls' => [
                'en' => url('/en'),
                'ar' => url('/ar'),
            ],
            'seo' => [
                'title' => $seoDefaults['meta_title'][$locale] ?? config('app.name'),
                'description' => $seoDefaults['meta_description'][$locale] ?? '',
                'canonical' => url('/'.$locale),
                'hreflang' => [
                    'en' => url('/en'),
                    'ar' => url('/ar'),
                    'x-default' => url('/en'),
                ],
            ],
        ]);
    }
}
