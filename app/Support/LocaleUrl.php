<?php

namespace App\Support;

class LocaleUrl
{
    public static function home(?string $locale = null): string
    {
        return route('home', ['locale' => $locale ?? app()->getLocale()]);
    }

    public static function category(string $slug, ?string $locale = null): string
    {
        return route('categories.show', [
            'locale' => $locale ?? app()->getLocale(),
            'category' => $slug,
        ]);
    }

    public static function article(string $slug, ?string $locale = null): string
    {
        return route('articles.show', [
            'locale' => $locale ?? app()->getLocale(),
            'article' => $slug,
        ]);
    }

    public static function contact(?string $locale = null): string
    {
        return self::home($locale).'#contact';
    }
}
