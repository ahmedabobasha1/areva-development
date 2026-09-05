<?php

namespace App\Filament\Support;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;

class SeoFields
{
    /**
     * @return array<int, Section>
     */
    public static function make(): array
    {
        return [
            Section::make('SEO')
                ->columns(2)
                ->schema([
                    TextInput::make('meta_title')
                        ->label('Meta title')
                        ->maxLength(70),
                    TextInput::make('og_title')
                        ->label('OG title')
                        ->maxLength(70),
                    Textarea::make('meta_description')
                        ->label('Meta description')
                        ->rows(3)
                        ->maxLength(160)
                        ->columnSpanFull(),
                    Textarea::make('og_description')
                        ->label('OG description')
                        ->rows(3)
                        ->maxLength(200)
                        ->columnSpanFull(),
                    Toggle::make('robots_index')
                        ->label('Allow indexing')
                        ->default(true),
                    Toggle::make('robots_follow')
                        ->label('Allow following links')
                        ->default(true),
                ]),
        ];
    }
}
