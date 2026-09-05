<?php

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Resources\Articles\ArticleResource;
use App\Support\Slug;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
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

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $slug = Slug::from((string) ($data['slug'] ?? ''));

        if ($slug === '') {
            $slug = Slug::from((string) ($data['title'] ?? ''));
        }

        $data['slug'] = $slug;

        return $data;
    }
}
