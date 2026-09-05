<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Category extends Model implements HasMedia
{
    use HasTranslations;
    use InteractsWithMedia;

    protected $fillable = [
        'parent_id',
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

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort');
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('hero')->singleFile();
        $this->addMediaCollection('seo')->singleFile();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeRoots(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function scopeWhereSlug(Builder $query, string $slug, ?string $locale = null): Builder
    {
        $locales = $locale
            ? array_values(array_unique([$locale, ...config('areva.locales', ['en', 'ar'])]))
            : config('areva.locales', ['en', 'ar']);

        return $query->whereJsonContainsLocales('slug', $locales, $slug);
    }

    /**
     * @return list<int>
     */
    public function descendantIds(): array
    {
        if (! $this->exists || $this->id === null) {
            return [];
        }

        $ids = [];
        $frontier = self::query()
            ->where('parent_id', $this->id)
            ->pluck('id')
            ->all();

        while ($frontier !== []) {
            array_push($ids, ...$frontier);
            $frontier = self::query()
                ->whereIn('parent_id', $frontier)
                ->pluck('id')
                ->all();
        }

        return $ids;
    }

    /**
     * This category id plus all descendant ids.
     *
     * @return list<int>
     */
    public function selfAndDescendantIds(): array
    {
        if (! $this->exists || $this->id === null) {
            return [];
        }

        return [$this->id, ...$this->descendantIds()];
    }

    /**
     * Categories that would create a cycle if chosen as parent of this record.
     *
     * @return Collection<int, int>
     */
    public function invalidParentIds(): Collection
    {
        if (! $this->exists || $this->id === null) {
            return collect();
        }

        return collect([$this->id, ...$this->descendantIds()]);
    }
}
