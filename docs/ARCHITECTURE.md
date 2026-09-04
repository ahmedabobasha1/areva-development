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

## Domain models (next: feature/02)

Category, Article, HeroSlide, PopularTopic, ContactMessage, Setting — with bilingual SEO fields.
