<?php

namespace App\Filament\Forms\Components\RichEditor\RichContentCustomBlocks;

use App\Support\VideoEmbed;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor\RichContentCustomBlock;
use Filament\Forms\Components\TextInput;

class VideoEmbedBlock extends RichContentCustomBlock
{
    public static function getId(): string
    {
        return 'video_embed';
    }

    public static function getLabel(): string
    {
        return 'Video';
    }

    public static function configureEditorAction(Action $action): Action
    {
        return $action
            ->modalHeading('Add video')
            ->modalDescription('Paste a YouTube, Vimeo, or direct video file URL.')
            ->schema([
                TextInput::make('url')
                    ->label('Video URL')
                    ->url()
                    ->required()
                    ->placeholder('https://www.youtube.com/watch?v=...')
                    ->helperText('Supports YouTube, Vimeo, and .mp4 / .webm / .ogg links.')
                    ->rule(function (): \Closure {
                        return function (string $attribute, mixed $value, \Closure $fail): void {
                            if (VideoEmbed::fromUrl(is_string($value) ? $value : null) === null) {
                                $fail('Enter a valid YouTube, Vimeo, or direct video file URL.');
                            }
                        };
                    }),
                TextInput::make('title')
                    ->label('Title (optional)')
                    ->maxLength(120),
            ]);
    }

    /**
     * @param  array<string, mixed>  $config
     */
    public static function getPreviewLabel(array $config): string
    {
        $title = trim((string) ($config['title'] ?? ''));

        return $title !== '' ? "Video: {$title}" : 'Video';
    }

    /**
     * @param  array<string, mixed>  $config
     */
    public static function toPreviewHtml(array $config): string
    {
        return view('filament.forms.components.rich-editor.rich-content-custom-blocks.video-embed.preview', [
            'url' => $config['url'] ?? null,
            'title' => $config['title'] ?? null,
            'embed' => VideoEmbed::fromUrl(isset($config['url']) && is_string($config['url']) ? $config['url'] : null),
        ])->render();
    }

    /**
     * @param  array<string, mixed>  $config
     * @param  array<string, mixed>  $data
     */
    public static function toHtml(array $config, array $data): string
    {
        $embed = VideoEmbed::fromUrl(isset($config['url']) && is_string($config['url']) ? $config['url'] : null);

        if ($embed === null) {
            return '';
        }

        return view('filament.forms.components.rich-editor.rich-content-custom-blocks.video-embed.index', [
            'embed' => $embed,
            'title' => $config['title'] ?? null,
        ])->render();
    }
}
