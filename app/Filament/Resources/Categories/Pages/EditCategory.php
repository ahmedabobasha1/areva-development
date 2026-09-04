<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\DeleteAction;
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
}
