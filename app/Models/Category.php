<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Category extends Model implements HasMedia
{
    use HasTranslations;
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'meta_title',
        'meta_description',
        'og_title',
        'og_description',
        'robots_index',
        'robots_follow',
        'sort',
        'is_active',
    ];

    public array $translatable = [
        'name',
        'slug',
        'description',
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
            'is_active' => 'boolean',
            'sort' => 'integer',
        ];
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function popularTopics(): HasMany
    {
        return $this->hasMany(PopularTopic::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('hero')->singleFile();
        $this->addMediaCollection('seo')->singleFile();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWhereSlug($query, string $slug, ?string $locale = null)
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
            ->active()
            ->first();
    }
}
