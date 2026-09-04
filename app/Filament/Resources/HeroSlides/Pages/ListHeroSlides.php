<?php

namespace App\Filament\Resources\HeroSlides\Pages;

use App\Filament\Resources\HeroSlides\HeroSlideResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;
use Infinity\FilamentTranslatable\Actions\SelectLocaleAction;
use Infinity\FilamentTranslatable\Resources\Pages\Concerns\HasTranslatableListRecords;

class ListHeroSlides extends ListRecords
{
    use HasTranslatableListRecords;

    protected static string $resource = HeroSlideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            SelectLocaleAction::make(),
            CreateAction::make(),
        ];
    }
}
