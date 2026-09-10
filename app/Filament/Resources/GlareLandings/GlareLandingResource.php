<?php

namespace App\Filament\Resources\GlareLandings;

use App\Filament\Resources\GlareLandings\Pages\ManageGlareLanding;
use App\Models\GlareLanding;
use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class GlareLandingResource extends Resource
{
    protected static ?string $model = GlareLanding::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|\UnitEnum|null $navigationGroup = 'Home';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'GLARE Landing';

    protected static ?string $modelLabel = 'GLARE Landing';

    protected static ?string $pluralModelLabel = 'GLARE Landing';

    protected static ?string $slug = 'glare-landing';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('GLARE')
                    ->persistTabInQueryString()
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Hero')
                            ->schema([
                                Section::make('Hero content')
                                    ->columns(2)
                                    ->schema([
                                        Textarea::make('hero_title')
                                            ->label('Headline')
                                            ->required()
                                            ->rows(3)
                                            ->helperText('Use a new line to split the headline into two rows.')
                                            ->columnSpanFull(),
                                        Textarea::make('hero_lead')
                                            ->label('Supporting text')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        TextInput::make('cta_label')
                                            ->label('CTA label')
                                            ->maxLength(120),
                                        TextInput::make('scroll_label')
                                            ->label('Scroll hint')
                                            ->maxLength(120),
                                        TextInput::make('back_label')
                                            ->label('Back link label')
                                            ->maxLength(120),
                                        Toggle::make('is_published')
                                            ->label('Published')
                                            ->default(true)
                                            ->helperText('When off, the public /glare page returns 404.'),
                                        SpatieMediaLibraryFileUpload::make('hero_image')
                                            ->label('Hero background')
                                            ->collection('hero_image')
                                            ->image()
                                            ->imageEditor()
                                            ->downloadable()
                                            ->openable()
                                            ->maxSize(8192)
                                            ->columnSpanFull(),
                                        SpatieMediaLibraryFileUpload::make('logo')
                                            ->label('Logo (white / light version)')
                                            ->collection('logo')
                                            ->image()
                                            ->downloadable()
                                            ->openable()
                                            ->maxSize(2048)
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Tab::make('Contact')
                            ->schema([
                                Section::make('Contact section')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('contact_eyebrow')
                                            ->label('Eyebrow')
                                            ->maxLength(120),
                                        TextInput::make('contact_title')
                                            ->label('Heading')
                                            ->maxLength(190)
                                            ->columnSpanFull(),
                                        Textarea::make('contact_lead')
                                            ->label('Intro text')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        TextInput::make('contact_banner')
                                            ->label('Side banner text')
                                            ->maxLength(190)
                                            ->columnSpanFull(),
                                        TextInput::make('submit_label')
                                            ->label('Submit button')
                                            ->maxLength(120),
                                        TextInput::make('trust_line')
                                            ->label('Trust line')
                                            ->maxLength(190),
                                        SpatieMediaLibraryFileUpload::make('contact_image')
                                            ->label('Side image')
                                            ->collection('contact_image')
                                            ->image()
                                            ->imageEditor()
                                            ->downloadable()
                                            ->openable()
                                            ->maxSize(8192)
                                            ->columnSpanFull(),
                                    ]),
                                Section::make('Project types')
                                    ->schema([
                                        Repeater::make('project_types')
                                            ->label('Dropdown options')
                                            ->defaultItems(0)
                                            ->reorderable()
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['label']['en'] ?? $state['value'] ?? null)
                                            ->schema([
                                                TextInput::make('value')
                                                    ->label('Stored value')
                                                    ->required()
                                                    ->maxLength(120),
                                                TextInput::make('label.en')
                                                    ->label('Label (EN)')
                                                    ->required()
                                                    ->maxLength(120),
                                                TextInput::make('label.ar')
                                                    ->label('Label (AR)')
                                                    ->required()
                                                    ->maxLength(120),
                                            ])
                                            ->columns(3)
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Tab::make('SEO')
                            ->schema([
                                Section::make('Search & social')
                                    ->columns(1)
                                    ->schema([
                                        TextInput::make('meta_title')
                                            ->label('Meta title')
                                            ->maxLength(70),
                                        Textarea::make('meta_description')
                                            ->label('Meta description')
                                            ->rows(3)
                                            ->maxLength(160),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageGlareLanding::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}
