<?php

namespace App\Filament\Resources\Glare1Landings\Pages;

use App\Filament\Resources\Glare1Landings\Glare1LandingResource;
use App\Models\Glare1Landing;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;
use Infinity\FilamentTranslatable\Actions\SelectLocaleAction;
use Infinity\FilamentTranslatable\Resources\Pages\Concerns\HasTranslatableEditRecord;

class ManageGlare1Landing extends EditRecord
{
    use HasTranslatableEditRecord;

    protected static string $resource = Glare1LandingResource::class;

    protected static ?string $title = 'GLARE1 Landing';

    public function mount(int|string|null $record = null): void
    {
        $landing = Glare1Landing::current();

        parent::mount($landing->getKey());
    }

    protected function getHeaderActions(): array
    {
        return [
            SelectLocaleAction::make(),
            Action::make('viewLive')
                ->label('View live page')
                ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->url(fn (): string => route('glare1', ['locale' => app()->getLocale() === 'ar' ? 'ar' : 'en']))
                ->openUrlInNewTab(),
        ];
    }

    protected function getRedirectUrl(): ?string
    {
        return static::getResource()::getUrl('index');
    }
}
