<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'array',
        ];
    }

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $setting = Cache::rememberForever("setting.{$key}", function () use ($key) {
            return static::query()->where('key', $key)->first();
        });

        return $setting?->value ?? $default;
    }

    public static function setValue(string $key, mixed $value): self
    {
        $setting = static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value],
        );

        Cache::forget("setting.{$key}");

        return $setting;
    }

    protected static function booted(): void
    {
        static::saved(function (Setting $setting): void {
            Cache::forget("setting.{$setting->key}");
        });

        static::deleted(function (Setting $setting): void {
            Cache::forget("setting.{$setting->key}");
        });
    }
}
