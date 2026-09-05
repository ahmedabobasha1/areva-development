<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use App\Support\Slug;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Infinity\FilamentTranslatable\Actions\SelectLocaleAction;
use Infinity\FilamentTranslatable\Resources\Pages\Concerns\HasTranslatableEditRecord;

class EditCategory extends EditRecord
{
    use HasTranslatableEditRecord;

    protected static string $resource = CategoryResource::class;

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
            $slug = Slug::from((string) ($data['name'] ?? ''));
        }

        $data['slug'] = $slug;

        return $data;
    }
}
