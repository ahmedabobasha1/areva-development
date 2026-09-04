<?php

namespace App\Filament\Resources\PopularTopics\Pages;

use App\Filament\Resources\PopularTopics\PopularTopicResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;
use Infinity\FilamentTranslatable\Actions\SelectLocaleAction;
use Infinity\FilamentTranslatable\Resources\Pages\Concerns\HasTranslatableListRecords;

class ListPopularTopics extends ListRecords
{
    use HasTranslatableListRecords;

    protected static string $resource = PopularTopicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            SelectLocaleAction::make(),
            CreateAction::make(),
        ];
    }
}
