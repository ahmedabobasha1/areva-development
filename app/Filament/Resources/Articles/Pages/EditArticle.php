<?php

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Resources\Articles\ArticleResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\DeleteAction;
use Infinity\FilamentTranslatable\Actions\SelectLocaleAction;
use Infinity\FilamentTranslatable\Resources\Pages\Concerns\HasTranslatableEditRecord;

class EditArticle extends EditRecord
{
    use HasTranslatableEditRecord;

    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            SelectLocaleAction::make(),
            DeleteAction::make(),
        ];
    }
}
