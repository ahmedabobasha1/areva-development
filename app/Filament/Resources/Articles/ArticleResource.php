<?php

namespace App\Filament\Resources\Articles;

use App\Filament\Resources\Articles\Pages\CreateArticle;
use App\Filament\Resources\Articles\Pages\EditArticle;
use App\Filament\Resources\Articles\Pages\ListArticles;
use App\Filament\Support\SeoFields;
use App\Models\Article;
use App\Support\Slug;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
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
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                Tabs::make('Article')
                    ->persistTabInQueryString()
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Content')
                            ->schema([
                                Section::make()
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
                                            ->afterStateUpdated(function (?string $state, callable $set, string $operation): void {
                                                if ($operation !== 'edit') {
                                                    return;
                                                }

                                                $set('slug', Slug::from((string) $state));
                                            })
                                            ->columnSpanFull(),
                                        TextInput::make('slug')
                                            ->required()
                                            ->maxLength(190)
                                            ->helperText('Auto-updated from the title on blur. Spaces/special characters are cleaned like Str::slug; Arabic letters are kept. You can still edit it manually.')
                                            ->visibleOn('edit')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (?string $state, callable $set): void {
                                                $set('slug', Slug::from((string) $state));
                                            })
                                            ->dehydrateStateUsing(fn (?string $state): string => Slug::from((string) $state))
                                            ->columnSpanFull(),
                                        Textarea::make('excerpt')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        RichEditor::make('body')
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Tab::make('Media')
                            ->schema([
                                Section::make('Images')
                                    ->description('Main cover image, related gallery images, and optional SEO/OG image.')
                                    ->schema([
                                        SpatieMediaLibraryFileUpload::make('cover')
                                            ->label('Main image')
                                            ->collection('cover')
                                            ->image()
                                            ->imageEditor()
                                            ->downloadable()
                                            ->openable()
                                            ->maxSize(5120)
                                            ->helperText('Primary blog image used on cards, hero, and article page.'),
                                        SpatieMediaLibraryFileUpload::make('gallery')
                                            ->label('Related images')
                                            ->collection('gallery')
                                            ->multiple()
                                            ->reorderable()
                                            ->image()
                                            ->downloadable()
                                            ->openable()
                                            ->maxSize(5120)
                                            ->helperText('Extra photos shown in the article gallery.'),
                                        SpatieMediaLibraryFileUpload::make('seo_image')
                                            ->label('SEO / OG image')
                                            ->collection('seo')
                                            ->image()
                                            ->downloadable()
                                            ->openable()
                                            ->maxSize(5120)
                                            ->helperText('Optional. Falls back to the main image when empty.'),
                                    ]),
                            ]),
                        Tab::make('SEO')
                            ->schema(SeoFields::make()),
                        Tab::make('Options')
                            ->schema([
                                Section::make()
                                    ->columns(2)
                                    ->schema([
                                        DateTimePicker::make('published_at'),
                                        TextInput::make('read_time_minutes')
                                            ->numeric()
                                            ->default(5)
                                            ->required(),
                                        Toggle::make('is_featured')->default(false),
                                        Toggle::make('is_trending')
                                            ->label('Popular topic (home)')
                                            ->default(false),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->defaultSort('published_at', 'desc')
            ->columns([
                SpatieMediaLibraryImageColumn::make('cover')
                    ->collection('cover')
                    ->circular(false)
                    ->square(),
                TextColumn::make('title')->searchable()->limit(40),
                TextColumn::make('category.name')->toggleable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('published_at')->dateTime()->sortable(),
                IconColumn::make('is_featured')->boolean(),
                IconColumn::make('is_trending')
                    ->label('Popular')
                    ->boolean(),
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
