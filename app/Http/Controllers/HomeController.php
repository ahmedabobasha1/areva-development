<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\HeroSlide;
use App\Models\PopularTopic;
use App\Support\LocaleUrl;
use App\Support\SeoBuilder;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(string $locale): View
    {
        return view('home', [
            'heroSlides' => HeroSlide::query()->active()->with('article')->orderBy('sort')->get(),
            'categories' => Category::query()->active()->orderBy('sort')->get(),
            'featuredArticle' => Article::query()->published()->featured()->with('category')->latest('published_at')->first()
                ?? Article::query()->published()->with('category')->latest('published_at')->first(),
            'latestArticles' => Article::query()->published()->with('category')->latest('published_at')->take(6)->get(),
            'popularTopics' => PopularTopic::query()->active()->with('category')->orderBy('sort')->get(),
            'langSwitchUrls' => [
                'en' => LocaleUrl::home('en'),
                'ar' => LocaleUrl::home('ar'),
            ],
            'seo' => SeoBuilder::forHome($locale),
        ]);
    }
}
