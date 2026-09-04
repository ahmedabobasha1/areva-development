# Maintenance — Media uploads in Filament

**Branch:** `chore/maintenance`  
**Base:** `master`  
**Commit:** `e08d33a` — Add Filament media uploads for articles and categories.

## Why

Models already had Spatie Media Library collections (`cover`, `gallery`, `hero`, etc.), and the public Blade views already read them — but admin forms had no upload fields, so editors could not set images.

## Delivered

- Installed `filament/spatie-laravel-media-library-plugin` (^5)
- **Articles:** Main image (`cover`), related images (`gallery`), SEO image (`seo`)
- **Categories:** Main image (`hero`), SEO image (`seo`)
- **Hero slides / Popular topics:** Main image (`image`)
- Article page renders gallery when related images exist
- `FILESYSTEM_DISK=public` (`.env` / `.env.example`) + `php artisan storage:link` for public URLs

## Files changed on this branch

| File | Change |
|------|--------|
| `composer.json` / `composer.lock` | Added `filament/spatie-laravel-media-library-plugin` ^5 |
| `.env.example` | `FILESYSTEM_DISK=public` |
| `app/Filament/Resources/Articles/ArticleResource.php` | Images section: cover, gallery (multiple/reorderable), seo; table thumb column |
| `app/Filament/Resources/Categories/CategoryResource.php` | Images section: hero, seo; table thumb column |
| `app/Filament/Resources/HeroSlides/HeroSlideResource.php` | Main image upload + table thumb |
| `app/Filament/Resources/PopularTopics/PopularTopicResource.php` | Main image upload + table thumb |
| `resources/views/articles/show.blade.php` | Gallery grid from `gallery` media collection |
| `public/assets/css/styles.css` | `.article-gallery` styles (desktop + mobile) |
| `docs/ARCHITECTURE.md` | Filament resources note images |
| `docs/COMMANDS.md` | Logged install + storage:link commands |
| `docs/features/maintenance-media.md` | This document |
| `docs/README.md` | Branch index entry |

## Admin usage

1. Open `/admin/articles` or `/admin/categories` (also Hero slides / Popular topics)
2. Edit a record → **Images** section → upload
3. Save — public pages use `getFirstMediaUrl(...)` / `getMedia('gallery')` automatically

## Commands run

```bash
git checkout -b chore/maintenance
composer require filament/spatie-laravel-media-library-plugin:"^5.0" -W --no-interaction
php artisan storage:link
php artisan filament:optimize-clear
# local .env: FILESYSTEM_DISK=public (not committed)
```

## Verify

- Admin edit forms show **Images** upload fields
- After upload, article/category public pages show the new images
- Article gallery appears under the body when related images exist

## Slug auto-generation (follow-up)

- **Create:** slug field is hidden; generated from article `title` / category `name` via `App\Support\Slug`
- **Edit:** slug field is visible so admins can change it
- **Arabic:** Arabic letters are kept in the slug (not transliterated), e.g. `مستقبل-المعيشة-في-القاهرة-الجديدة`
- **English:** still lowercased hyphenated ASCII, e.g. `future-of-modern-living`
- Files: `app/Support/Slug.php`, `CreateArticle.php`, `CreateCategory.php`, Article/Category resources

