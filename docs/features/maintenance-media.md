# Maintenance — Media uploads in Filament

**Branch:** `chore/maintenance`

## Why

Models already had Spatie Media Library collections (`cover`, `gallery`, `hero`, etc.), and the public Blade views already read them — but admin forms had no upload fields, so editors could not set images.

## Delivered

- Installed `filament/spatie-laravel-media-library-plugin` (^5)
- **Articles:** Main image (`cover`), related images (`gallery`), SEO image (`seo`)
- **Categories:** Main image (`hero`), SEO image (`seo`)
- **Hero slides / Popular topics:** Main image (`image`)
- Article page renders gallery when related images exist
- `FILESYSTEM_DISK=public` + `php artisan storage:link` for public URLs

## Admin usage

1. Open `/admin/articles` or `/admin/categories`
2. Edit a record → **Images** section → upload
3. Save — public pages use `getFirstMediaUrl(...)` automatically
