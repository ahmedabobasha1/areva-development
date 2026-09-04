<?php

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Resources\Articles\ArticleResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;
use Infinity\FilamentTranslatable\Actions\SelectLocaleAction;
use Infinity\FilamentTranslatable\Resources\Pages\Concerns\HasTranslatableListRecords;

class ListArticles extends ListRecords
{
    use HasTranslatableListRecords;

    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            SelectLocaleAction::make(),
            CreateAction::make(),
        ];
    }
}
