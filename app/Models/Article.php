<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Article extends Model implements HasMedia
{
    use HasTranslations;
    use InteractsWithMedia;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'excerpt',
        'body',
        'meta_title',
        'meta_description',
        'og_title',
        'og_description',
        'robots_index',
        'robots_follow',
        'status',
        'published_at',
        'is_featured',
        'is_trending',
        'read_time_minutes',
    ];

    public array $translatable = [
        'title',
        'slug',
        'excerpt',
        'body',
        'meta_title',
        'meta_description',
        'og_title',
        'og_description',
    ];

    protected function casts(): array
    {
        return [
            'robots_index' => 'boolean',
            'robots_follow' => 'boolean',
            'is_featured' => 'boolean',
            'is_trending' => 'boolean',
            'published_at' => 'datetime',
            'read_time_minutes' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function heroSlides(): HasMany
    {
        return $this->hasMany(HeroSlide::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
        $this->addMediaCollection('seo')->singleFile();
        $this->addMediaCollection('gallery');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', self::STATUS_PUBLISHED)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeTrending(Builder $query): Builder
    {
        return $query->where('is_trending', true);
    }

    public function scopeWhereSlug(Builder $query, string $slug, ?string $locale = null): Builder
    {
        $locales = $locale
            ? array_values(array_unique([$locale, ...config('areva.locales', ['en', 'ar'])]))
            : config('areva.locales', ['en', 'ar']);

        return $query->whereJsonContainsLocales('slug', $locales, $slug);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getRouteKey(): mixed
    {
        $locale = request()->route('locale') ?? app()->getLocale();

        return $this->getTranslation('slug', $locale)
            ?: $this->getTranslation('slug', config('areva.default_locale', 'en'));
    }

    public function resolveRouteBinding($value, $field = null): ?Model
    {
        $locale = request()->route('locale') ?? app()->getLocale();

        return $this->whereSlug((string) $value, $locale)
            ->published()
            ->with('category')
            ->first();
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED
            && $this->published_at !== null
            && $this->published_at->lte(now());
    }
}
