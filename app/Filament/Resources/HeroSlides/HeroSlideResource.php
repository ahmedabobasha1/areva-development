<?php

namespace App\Filament\Resources\HeroSlides;

use App\Filament\Resources\HeroSlides\Pages\CreateHeroSlide;
use App\Filament\Resources\HeroSlides\Pages\EditHeroSlide;
use App\Filament\Resources\HeroSlides\Pages\ListHeroSlides;
use App\Models\HeroSlide;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HeroSlideResource extends Resource
{
    protected static ?string $model = HeroSlide::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static string|\UnitEnum|null $navigationGroup = 'Home';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Hero slide')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')->required()->maxLength(190)->columnSpanFull(),
                        Textarea::make('subtitle')->rows(3)->columnSpanFull(),
                        TextInput::make('cta_label')->maxLength(120),
                        TextInput::make('cta_url')->url()->maxLength(255),
                        Select::make('article_id')
                            ->relationship('article', 'title')
                            ->searchable()
                            ->preload(),
                        TextInput::make('sort')->numeric()->default(0)->required(),
                        Toggle::make('is_active')->default(true)->required(),
                        SpatieMediaLibraryFileUpload::make('image')
                            ->label('Main image')
                            ->collection('image')
                            ->image()
                            ->imageEditor()
                            ->downloadable()
                            ->openable()
                            ->maxSize(5120)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->defaultSort('sort')
            ->columns([
                SpatieMediaLibraryImageColumn::make('image')->collection('image')->square(),
                TextColumn::make('title')->searchable(),
                TextColumn::make('article.title')->toggleable(),
                TextColumn::make('sort')->sortable(),
                IconColumn::make('is_active')->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHeroSlides::route('/'),
            'create' => CreateHeroSlide::route('/create'),
            'edit' => EditHeroSlide::route('/{record}/edit'),
        ];
    }
}
