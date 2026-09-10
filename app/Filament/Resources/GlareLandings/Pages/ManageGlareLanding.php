<?php

namespace App\Filament\Resources\GlareLandings\Pages;

use App\Filament\Resources\GlareLandings\GlareLandingResource;
use App\Models\GlareLanding;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;
use Infinity\FilamentTranslatable\Actions\SelectLocaleAction;
use Infinity\FilamentTranslatable\Resources\Pages\Concerns\HasTranslatableEditRecord;

class ManageGlareLanding extends EditRecord
{
    use HasTranslatableEditRecord;

    protected static string $resource = GlareLandingResource::class;

    protected static ?string $title = 'GLARE Landing';

    public function mount(int|string|null $record = null): void
    {
        $landing = GlareLanding::current();

        parent::mount($landing->getKey());
    }

    protected function getHeaderActions(): array
    {
        return [
            SelectLocaleAction::make(),
            Action::make('viewLive')
                ->label('View live page')
                ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->url(fn (): string => route('glare', ['locale' => app()->getLocale() === 'ar' ? 'ar' : 'en']))
                ->openUrlInNewTab(),
        ];
    }

    protected function getRedirectUrl(): ?string
    {
        return static::getResource()::getUrl('index');
    }
}
