<?php

namespace Tests\Unit;

use App\Filament\Forms\Components\RichEditor\RichContentCustomBlocks\VideoEmbedBlock;
use App\Support\VideoEmbed;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class VideoEmbedTest extends TestCase
{
    #[DataProvider('supportedUrls')]
    public function test_it_builds_safe_embed_sources(string $url, string $type, string $src): void
    {
        $this->assertSame([
            'type' => $type,
            'src' => $src,
        ], VideoEmbed::fromUrl($url));
    }

    public static function supportedUrls(): array
    {
        return [
            'youtube watch' => [
                'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'iframe',
                'https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ',
            ],
            'youtu.be' => [
                'https://youtu.be/dQw4w9WgXcQ',
                'iframe',
                'https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ',
            ],
            'vimeo' => [
                'https://vimeo.com/123456789',
                'iframe',
                'https://player.vimeo.com/video/123456789',
            ],
            'mp4' => [
                'https://cdn.example.com/videos/tour.mp4',
                'video',
                'https://cdn.example.com/videos/tour.mp4',
            ],
        ];
    }

    public function test_it_rejects_unsupported_urls(): void
    {
        $this->assertNull(VideoEmbed::fromUrl('https://example.com/not-a-video'));
        $this->assertNull(VideoEmbed::fromUrl('javascript:alert(1)'));
        $this->assertNull(VideoEmbed::fromUrl(''));
    }

    public function test_rich_content_renderer_keeps_video_iframe(): void
    {
        $html = '<p>Intro</p><div data-type="customBlock" data-id="video_embed" data-config="'.e(json_encode([
            'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'title' => 'Tour video',
        ])).'"></div>';

        $rendered = RichContentRenderer::make($html)
            ->customBlocks([
                VideoEmbedBlock::class,
            ])
            ->toHtml();

        $this->assertStringContainsString('article-video-embed', $rendered);
        $this->assertStringContainsString('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ', $rendered);
        $this->assertStringContainsString('<iframe', $rendered);
        $this->assertStringContainsString('Tour video', $rendered);
    }
}
