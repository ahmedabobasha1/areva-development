<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;
use Infinity\FilamentTranslatable\Actions\SelectLocaleAction;
use Infinity\FilamentTranslatable\Resources\Pages\Concerns\HasTranslatableListRecords;

class ListCategories extends ListRecords
{
    use HasTranslatableListRecords;

    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            SelectLocaleAction::make(),
            CreateAction::make(),
        ];
    }
}
