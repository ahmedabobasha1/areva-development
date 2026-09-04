<?php

namespace App\Filament\Resources\PopularTopics;

use App\Filament\Resources\PopularTopics\Pages\CreatePopularTopic;
use App\Filament\Resources\PopularTopics\Pages\EditPopularTopic;
use App\Filament\Resources\PopularTopics\Pages\ListPopularTopics;
use App\Models\PopularTopic;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PopularTopicResource extends Resource
{
    protected static ?string $model = PopularTopic::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHashtag;

    protected static string|\UnitEnum|null $navigationGroup = 'Home';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Popular topic')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')->required()->maxLength(190)->columnSpanFull(),
                        Textarea::make('excerpt')->rows(3)->columnSpanFull(),
                        TextInput::make('cta_label')->maxLength(120),
                        TextInput::make('cta_url')->url()->maxLength(255),
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload(),
                        TextInput::make('sort')->numeric()->default(0)->required(),
                        Toggle::make('is_active')->default(true)->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->defaultSort('sort')
            ->columns([
                TextColumn::make('title')->searchable(),
                TextColumn::make('category.name')->toggleable(),
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
            'index' => ListPopularTopics::route('/'),
            'create' => CreatePopularTopic::route('/create'),
            'edit' => EditPopularTopic::route('/{record}/edit'),
        ];
    }
}
