<?php

namespace App\Support;

class WhatsAppLink
{
    public static function from(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        $value = trim($value);

        // Admin may paste any link — use it as-is (no scheme/host validation).
        if (preg_match('/^[a-z][a-z0-9+.-]*:/i', $value) || str_contains($value, '/') || str_contains($value, '.')) {
            return $value;
        }

        $digits = preg_replace('/\D+/', '', $value);

        if ($digits === null || $digits === '') {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            $digits = '20'.substr($digits, 1);
        }

        return 'https://wa.me/'.$digits;
    }
}
