<?php

namespace App\Filament\Resources\Articles;

use App\Filament\Resources\Articles\Pages\CreateArticle;
use App\Filament\Resources\Articles\Pages\EditArticle;
use App\Filament\Resources\Articles\Pages\ListArticles;
use App\Filament\Support\SeoFields;
use App\Models\Article;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
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
use Illuminate\Support\Str;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Article')
                    ->columns(2)
                    ->schema([
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('status')
                            ->options([
                                Article::STATUS_DRAFT => 'Draft',
                                Article::STATUS_PUBLISHED => 'Published',
                            ])
                            ->required()
                            ->default(Article::STATUS_DRAFT),
                        TextInput::make('title')
                            ->required()
                            ->maxLength(190)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (?string $state, callable $set, callable $get): void {
                                if (filled($get('slug'))) {
                                    return;
                                }

                                $set('slug', Str::slug((string) $state));
                            })
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(190)
                            ->columnSpanFull(),
                        Textarea::make('excerpt')
                            ->rows(3)
                            ->columnSpanFull(),
                        RichEditor::make('body')
                            ->columnSpanFull(),
                        DateTimePicker::make('published_at'),
                        TextInput::make('read_time_minutes')
                            ->numeric()
                            ->default(5)
                            ->required(),
                        Toggle::make('is_featured')->default(false),
                        Toggle::make('is_trending')->default(false),
                    ]),
                ...SeoFields::make(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->defaultSort('published_at', 'desc')
            ->columns([
                TextColumn::make('title')->searchable()->limit(40),
                TextColumn::make('category.name')->toggleable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('published_at')->dateTime()->sortable(),
                IconColumn::make('is_featured')->boolean(),
                IconColumn::make('is_trending')->boolean(),
                IconColumn::make('robots_index')->boolean()->toggleable(isToggledHiddenByDefault: true),
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
            'index' => ListArticles::route('/'),
            'create' => CreateArticle::route('/create'),
            'edit' => EditArticle::route('/{record}/edit'),
        ];
    }
}
