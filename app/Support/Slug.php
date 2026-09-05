<?php

namespace App\Support;

use Illuminate\Support\Str;

class Slug
{
    /**
     * Build a URL slug like Laravel Str::slug (spaces/special chars → hyphens),
     * while keeping non-Latin letters such as Arabic.
     */
    public static function from(string $value): string
    {
        $value = trim($value);

        if ($value === '') {
            return '';
        }

        // Pure ASCII/Latin titles: use Laravel Str::slug exactly.
        if (! preg_match('/[^\x00-\x7F]/', $value)) {
            return Str::slug($value);
        }

        // Unicode titles (e.g. Arabic): same formatting rules, keep letters.
        // 1) Turn any run of non-letter/non-number chars into a single hyphen
        //    (spaces, punctuation, symbols, underscores, dots, etc.)
        $slug = preg_replace('/[^\p{L}\p{N}]+/u', '-', $value) ?? '';

        // 2) Collapse duplicate hyphens and trim edges
        $slug = preg_replace('/-+/u', '-', $slug) ?? '';
        $slug = trim($slug, '-');

        // 3) Lowercase Latin characters; Arabic letters stay unchanged
        return mb_strtolower($slug, 'UTF-8');
    }
}
