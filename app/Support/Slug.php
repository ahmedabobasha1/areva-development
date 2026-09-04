<?php

namespace App\Support;

use Illuminate\Support\Str;

class Slug
{
    public static function from(string $value): string
    {
        $value = trim($value);

        if ($value === '') {
            return '';
        }

        $slug = Str::slug($value, '-', 'en');

        if ($slug !== '') {
            return $slug;
        }

        // Fallback for non-Latin titles (e.g. Arabic) when ASCII slug is empty.
        $slug = preg_replace('/[^\p{L}\p{N}\s-]+/u', '', $value) ?? '';
        $slug = preg_replace('/[\s_]+/u', '-', trim($slug)) ?? '';
        $slug = mb_strtolower(trim($slug, '-'));

        return $slug;
    }
}
