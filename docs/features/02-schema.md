# Feature 02 — Schema & models

**Branch:** `feature/02-schema-models`

## Goal

Create bilingual domain tables/models and seed demo content from the static prototype.

## Tables

- `categories` — translatable name/slug/description + SEO + sort/active
- `articles` — belongs to category; translatable content + SEO; featured/trending; publish status
- `hero_slides` — home slider; optional article link
- `popular_topics` — home topics; optional category link
- `contact_messages` — form inbox
- `settings` — key/JSON value (site, seo_defaults, organization, social)

## Verify

```bash
php artisan migrate:status
php artisan db:seed --force
```

## Next

`feature/03-blade-shell` — port `legacy-static` HTML/CSS into Blade layouts.
