<?php

namespace App\Filament\Resources\PopularTopics\Pages;

use App\Filament\Resources\PopularTopics\PopularTopicResource;
use Filament\Resources\Pages\CreateRecord;
use Infinity\FilamentTranslatable\Actions\SelectLocaleAction;
use Infinity\FilamentTranslatable\Resources\Pages\Concerns\HasTranslatableCreateRecord;

class CreatePopularTopic extends CreateRecord
{
    use HasTranslatableCreateRecord;

    protected static string $resource = PopularTopicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            SelectLocaleAction::make(),
        ];
    }
}
