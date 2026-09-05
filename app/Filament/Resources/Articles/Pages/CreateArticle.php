<?php

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Resources\Articles\ArticleResource;
use App\Support\Slug;
use Filament\Resources\Pages\CreateRecord;
use Infinity\FilamentTranslatable\Actions\SelectLocaleAction;
use Infinity\FilamentTranslatable\Resources\Pages\Concerns\HasTranslatableCreateRecord;

class CreateArticle extends CreateRecord
{
    use HasTranslatableCreateRecord;

    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            SelectLocaleAction::make(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug'] = Slug::from((string) ($data['title'] ?? ''));

        return $data;
    }
}
