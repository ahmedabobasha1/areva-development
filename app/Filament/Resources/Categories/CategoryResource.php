<?php

namespace App\Filament\Resources\Categories;

use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Filament\Support\SeoFields;
use App\Models\Category;
use App\Support\Slug;
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
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Category')
                    ->persistTabInQueryString()
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Content')
                            ->schema([
                                Section::make()
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->required()
                                            ->maxLength(190)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (?string $state, callable $set, string $operation): void {
                                                if ($operation !== 'edit') {
                                                    return;
                                                }

                                                $set('slug', Slug::from((string) $state));
                                            }),
                                        TextInput::make('slug')
                                            ->required()
                                            ->maxLength(190)
                                            ->helperText('Auto-updated from the name on blur. Spaces/special characters are cleaned like Str::slug; Arabic letters are kept. You can still edit it manually.')
                                            ->visibleOn('edit')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (?string $state, callable $set): void {
                                                $set('slug', Slug::from((string) $state));
                                            })
                                            ->dehydrateStateUsing(fn (?string $state): string => Slug::from((string) $state)),
                                        Textarea::make('description')
                                            ->rows(4)
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Tab::make('Media')
                            ->schema([
                                Section::make('Images')
                                    ->description('Main category image shown on the category page and home cards.')
                                    ->schema([
                                        SpatieMediaLibraryFileUpload::make('hero')
                                            ->label('Main image')
                                            ->collection('hero')
                                            ->image()
                                            ->imageEditor()
                                            ->downloadable()
                                            ->openable()
                                            ->maxSize(5120),
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
                                        Select::make('parent_id')
                                            ->label('Parent category')
                                            ->relationship(
                                                name: 'parent',
                                                titleAttribute: 'name',
                                                modifyQueryUsing: function ($query, ?Category $record = null) {
                                                    if (! $record instanceof Category || ! $record->exists) {
                                                        return $query;
                                                    }

                                                    $invalidIds = $record->invalidParentIds();

                                                    if ($invalidIds->isEmpty()) {
                                                        return $query;
                                                    }

                                                    return $query->whereNotIn('id', $invalidIds);
                                                },
                                            )
                                            ->searchable()
                                            ->preload()
                                            ->nullable(),
                                        TextInput::make('sort')
                                            ->numeric()
                                            ->default(0)
                                            ->required(),
                                        Toggle::make('is_active')
                                            ->default(true)
                                            ->required(),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultSort('sort')
            ->columns([
                SpatieMediaLibraryImageColumn::make('hero')
                    ->collection('hero')
                    ->circular(false)
                    ->square(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('parent.name')
                    ->label('Parent')
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('slug')->toggleable(),
                TextColumn::make('sort')->sortable(),
                IconColumn::make('is_active')->boolean(),
                IconColumn::make('robots_index')->boolean()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
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
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }
}
