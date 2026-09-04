<?php

namespace App\Support;

use Illuminate\Support\Str;

class Slug
{
    /**
     * Build a URL slug while keeping letters from any language (including Arabic).
     */
    public static function from(string $value): string
    {
        $value = trim($value);

        if ($value === '') {
            return '';
        }

        // Keep letters/numbers from all scripts; turn spaces/underscores into hyphens.
        $slug = preg_replace('/[^\p{L}\p{N}\s-]+/u', '', $value) ?? '';
        $slug = preg_replace('/[\s_]+/u', '-', trim($slug)) ?? '';
        $slug = preg_replace('/-+/u', '-', $slug) ?? '';
        $slug = trim($slug, '-');

        // Lowercase only Latin parts; Arabic letters stay as-is.
        $slug = Str::lower($slug);

        return $slug;
    }
}
