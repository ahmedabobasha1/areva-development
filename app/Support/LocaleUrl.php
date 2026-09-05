<?php

namespace App\Support;

class LocaleUrl
{
    public static function home(?string $locale = null): string
    {
        return url('/'.($locale ?? app()->getLocale()));
    }

    public static function category(string $slug, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return url('/'.$locale.'/categories/'.$slug);
    }

    public static function article(string $slug, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return url('/'.$locale.'/blog/'.$slug);
    }

    public static function contact(?string $locale = null): string
    {
        return self::home($locale).'#contact';
    }
}
