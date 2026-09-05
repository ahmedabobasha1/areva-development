<?php

namespace App\Support;

use App\Models\Article;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Support\Str;

class SeoBuilder
{
    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    public static function forHome(string $locale, array $overrides = []): array
    {
        $defaults = Setting::getValue('seo_defaults', []);
        $organization = Setting::getValue('organization', []);

        $title = $defaults['meta_title'][$locale] ?? config('app.name');
        $description = $defaults['meta_description'][$locale] ?? '';

        return array_replace_recursive([
            'title' => $title,
            'description' => $description,
            'canonical' => LocaleUrl::home($locale),
            'robots' => self::robots(true, true),
            'og_type' => 'website',
            'og_title' => $defaults['og_title'][$locale] ?? $title,
            'og_description' => $defaults['og_description'][$locale] ?? $description,
            'og_image' => asset('assets/images/hero.jpg'),
            'site_name' => config('app.name'),
            'hreflang' => [
                'en' => LocaleUrl::home('en'),
                'ar' => LocaleUrl::home('ar'),
                'x-default' => LocaleUrl::home(config('areva.default_locale', 'en')),
            ],
            'json_ld' => array_values(array_filter([
                self::organizationJsonLd($organization),
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'WebSite',
                    'name' => config('app.name'),
                    'url' => LocaleUrl::home($locale),
                    'inLanguage' => $locale,
                ],
            ])),
        ], $overrides);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    public static function forCategory(Category $category, string $locale, array $overrides = []): array
    {
        $title = $category->getTranslation('meta_title', $locale) ?: $category->getTranslation('name', $locale);
        $description = $category->getTranslation('meta_description', $locale) ?: $category->getTranslation('description', $locale);
        $slug = $category->getTranslation('slug', $locale);

        return array_replace_recursive([
            'title' => $title,
            'description' => Str::limit(strip_tags((string) $description), 160, ''),
            'canonical' => LocaleUrl::category($slug, $locale),
            'robots' => self::robots((bool) $category->robots_index, (bool) $category->robots_follow),
            'og_type' => 'website',
            'og_title' => $category->getTranslation('og_title', $locale) ?: $title,
            'og_description' => $category->getTranslation('og_description', $locale) ?: $description,
            'og_image' => $category->getFirstMediaUrl('seo') ?: ($category->getFirstMediaUrl('hero') ?: asset('assets/images/hero.jpg')),
            'site_name' => config('app.name'),
            'hreflang' => [
                'en' => LocaleUrl::category($category->getTranslation('slug', 'en'), 'en'),
                'ar' => LocaleUrl::category($category->getTranslation('slug', 'ar'), 'ar'),
                'x-default' => LocaleUrl::category($category->getTranslation('slug', 'en'), 'en'),
            ],
            'json_ld' => [[
                '@context' => 'https://schema.org',
                '@type' => 'CollectionPage',
                'name' => $title,
                'description' => Str::limit(strip_tags((string) $description), 160, ''),
                'url' => LocaleUrl::category($slug, $locale),
                'inLanguage' => $locale,
            ]],
        ], $overrides);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    public static function forArticle(Article $article, string $locale, array $overrides = []): array
    {
        $title = $article->getTranslation('meta_title', $locale) ?: $article->getTranslation('title', $locale);
        $description = $article->getTranslation('meta_description', $locale) ?: $article->getTranslation('excerpt', $locale);
        $slug = $article->getTranslation('slug', $locale);
        $image = $article->getFirstMediaUrl('seo') ?: ($article->getFirstMediaUrl('cover') ?: asset('assets/images/hero.jpg'));

        return array_replace_recursive([
            'title' => $title,
            'description' => Str::limit(strip_tags((string) $description), 160, ''),
            'canonical' => LocaleUrl::article($slug, $locale),
            'robots' => self::robots((bool) $article->robots_index, (bool) $article->robots_follow),
            'og_type' => 'article',
            'og_title' => $article->getTranslation('og_title', $locale) ?: $title,
            'og_description' => $article->getTranslation('og_description', $locale) ?: $description,
            'og_image' => $image,
            'site_name' => config('app.name'),
            'hreflang' => [
                'en' => LocaleUrl::article($article->getTranslation('slug', 'en'), 'en'),
                'ar' => LocaleUrl::article($article->getTranslation('slug', 'ar'), 'ar'),
                'x-default' => LocaleUrl::article($article->getTranslation('slug', 'en'), 'en'),
            ],
            'json_ld' => [[
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => $article->getTranslation('title', $locale),
                'description' => Str::limit(strip_tags((string) $description), 160, ''),
                'datePublished' => optional($article->published_at)?->toAtomString(),
                'dateModified' => optional($article->updated_at)?->toAtomString(),
                'image' => [$image],
                'mainEntityOfPage' => LocaleUrl::article($slug, $locale),
                'inLanguage' => $locale,
                'author' => [
                    '@type' => 'Organization',
                    'name' => config('app.name'),
                ],
            ]],
        ], $overrides);
    }

    public static function robots(bool $index, bool $follow): string
    {
        return ($index ? 'index' : 'noindex').','.($follow ? 'follow' : 'nofollow');
    }

    /**
     * @param  array<string, mixed>  $organization
     * @return array<string, mixed>|null
     */
    protected static function organizationJsonLd(array $organization): ?array
    {
        if ($organization === []) {
            return null;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $organization['name'] ?? config('app.name'),
            'url' => $organization['url'] ?? url('/'),
            'logo' => $organization['logo'] ?? asset('assets/images/logo.png'),
            'sameAs' => array_values(array_filter($organization['sameAs'] ?? [])),
        ];
    }
}
