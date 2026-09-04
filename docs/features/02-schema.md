# Feature 02 — Schema & seeders

**Branch:** `feature/02-schema-models`

## Goal

Create MySQL tables and Eloquent models for bilingual CMS content + SEO fields, and seed sample Areva data.

## Tables

- `categories` — translatable name/slug/description/SEO
- `articles` — belongs to category; draft/published; featured/trending
- `hero_slides` — home slider
- `popular_topics` — home topics
- `contact_messages` — form inbox
- `settings` — JSON key/value (site, seo_defaults, organization, social)

## Models

`Category`, `Article`, `HeroSlide`, `PopularTopic`, `ContactMessage`, `Setting`  
Uses Spatie `HasTranslations` + `InteractsWithMedia` where images apply.

## Verify

```bash
php artisan migrate:status
php artisan db:seed --force
```

## Next

`feature/03-blade-shell` — port `legacy-static` HTML/CSS into Blade layouts.
