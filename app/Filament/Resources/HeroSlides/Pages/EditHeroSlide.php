<?php

namespace App\Filament\Resources\HeroSlides\Pages;

use App\Filament\Resources\HeroSlides\HeroSlideResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\DeleteAction;
use Infinity\FilamentTranslatable\Actions\SelectLocaleAction;
use Infinity\FilamentTranslatable\Resources\Pages\Concerns\HasTranslatableEditRecord;

class EditHeroSlide extends EditRecord
{
    use HasTranslatableEditRecord;

    protected static string $resource = HeroSlideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            SelectLocaleAction::make(),
            DeleteAction::make(),
        ];
    }
}
