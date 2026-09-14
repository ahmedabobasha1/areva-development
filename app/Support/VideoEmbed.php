<?php

namespace App\Support;

final class VideoEmbed
{
    /**
     * @return array{type: 'iframe'|'video', src: string}|null
     */
    public static function fromUrl(?string $url): ?array
    {
        $url = trim((string) $url);

        if ($url === '' || ! filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        $parts = parse_url($url);

        if (! is_array($parts) || ! in_array(strtolower((string) ($parts['scheme'] ?? '')), ['http', 'https'], true)) {
            return null;
        }

        $host = strtolower((string) ($parts['host'] ?? ''));
        $path = (string) ($parts['path'] ?? '');

        if (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'music.youtube.com'], true)) {
            if (preg_match('#/(?:embed|shorts)/([A-Za-z0-9_-]{6,})#', $path, $matches) === 1) {
                return self::iframe('https://www.youtube-nocookie.com/embed/'.$matches[1]);
            }

            parse_str((string) ($parts['query'] ?? ''), $query);

            if (filled($query['v'] ?? null) && preg_match('/^[A-Za-z0-9_-]{6,}$/', (string) $query['v']) === 1) {
                return self::iframe('https://www.youtube-nocookie.com/embed/'.$query['v']);
            }

            return null;
        }

        if (in_array($host, ['youtu.be', 'www.youtu.be'], true) && preg_match('#^/([A-Za-z0-9_-]{6,})#', $path, $matches) === 1) {
            return self::iframe('https://www.youtube-nocookie.com/embed/'.$matches[1]);
        }

        if (in_array($host, ['vimeo.com', 'www.vimeo.com', 'player.vimeo.com'], true)) {
            if (preg_match('#/(?:video/)?(\d+)#', $path, $matches) === 1) {
                return self::iframe('https://player.vimeo.com/video/'.$matches[1]);
            }

            return null;
        }

        if (preg_match('/\.(mp4|webm|ogg)(?:$|\?)/i', $path) === 1) {
            return [
                'type' => 'video',
                'src' => $url,
            ];
        }

        return null;
    }

    /**
     * @return array{type: 'iframe', src: string}
     */
    private static function iframe(string $src): array
    {
        return [
            'type' => 'iframe',
            'src' => $src,
        ];
    }
}
