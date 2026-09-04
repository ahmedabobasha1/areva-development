# Architecture (in progress)

## Stack

- Laravel 13 (Blade public site + Filament 5 admin)
- MySQL database `areva_cms`
- Spatie Translatable (EN/AR content)
- Spatie Media Library (images)

## Repository layout

```
/
├── app/                 # Laravel app + Filament
├── docs/                # Project documentation + command log
├── legacy-static/       # Previous static HTML prototype
├── public/              # Web root (includes Filament assets)
├── resources/views/     # Blade (to be filled in feature/03)
├── routes/web.php
└── scripts/             # Helper scripts
```

## Locales

Planned public URLs: `/{locale}/...` with `en` and `ar` (feature/04).

## SEO

Planned `SeoBuilder` + `partials/seo.blade.php`, sitemap, robots (feature/06).

## Domain models (feature/02)

Implemented: `Category`, `Article`, `HeroSlide`, `PopularTopic`, `ContactMessage`, `Setting` with Spatie Translatable JSON fields and SEO columns. Seeded 5 categories + 1 published article + site settings.

## Domain models (feature/02)

| Model | Notes |
|-------|--------|
| `Category` | EN/AR name, slug, description, SEO; media: hero, seo |
| `Article` | EN/AR content + SEO; status draft/published; featured/trending |
| `HeroSlide` | Home slider; optional `article_id` |
| `PopularTopic` | Home topics; optional `category_id` |
| `ContactMessage` | Public form submissions |
| `Setting` | `site`, `seo_defaults`, `organization`, `social` JSON keys |

