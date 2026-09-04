<?php

namespace App\Filament\Resources\PopularTopics\Pages;

use App\Filament\Resources\PopularTopics\PopularTopicResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\DeleteAction;
use Infinity\FilamentTranslatable\Actions\SelectLocaleAction;
use Infinity\FilamentTranslatable\Resources\Pages\Concerns\HasTranslatableEditRecord;

class EditPopularTopic extends EditRecord
{
    use HasTranslatableEditRecord;

    protected static string $resource = PopularTopicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            SelectLocaleAction::make(),
            DeleteAction::make(),
        ];
    }
}
