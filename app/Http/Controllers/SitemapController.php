<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Support\LocaleUrl;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $locales = config('areva.locales', ['en', 'ar']);
        $urls = [];

        foreach ($locales as $locale) {
            $urls[] = [
                'loc' => LocaleUrl::home($locale),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ];
        }

        $categories = Category::query()->active()->where('robots_index', true)->orderBy('sort')->get();
        foreach ($categories as $category) {
            foreach ($locales as $locale) {
                $slug = $category->getTranslation('slug', $locale);
                if (! filled($slug)) {
                    continue;
                }

                $urls[] = [
                    'loc' => LocaleUrl::category($slug, $locale),
                    'lastmod' => optional($category->updated_at)?->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                ];
            }
        }

        $articles = Article::query()->published()->where('robots_index', true)->latest('published_at')->get();
        foreach ($articles as $article) {
            foreach ($locales as $locale) {
                $slug = $article->getTranslation('slug', $locale);
                if (! filled($slug)) {
                    continue;
                }

                $urls[] = [
                    'loc' => LocaleUrl::article($slug, $locale),
                    'lastmod' => optional($article->updated_at)?->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                ];
            }
        }

        $xml = view('seo.sitemap', ['urls' => $urls])->render();

        return response($xml, 200)->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
