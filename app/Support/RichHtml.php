<?php

namespace App\Support;

final class RichHtml
{
    /**
     * TipTap stores logical alignments (start/end). The Filament admin is LTR, so
     * "align right" becomes text-align:end. On Arabic pages (dir=rtl) that flips
     * to the left — convert to physical left/right so the front end matches admin.
     */
    public static function normalizeTextAlign(string $html): string
    {
        if ($html === '') {
            return $html;
        }

        return (string) preg_replace_callback(
            '/\btext-align\s*:\s*(start|end)\b/i',
            static function (array $matches): string {
                return 'text-align: '.(strtolower($matches[1]) === 'end' ? 'right' : 'left');
            },
            $html,
        );
    }
}
