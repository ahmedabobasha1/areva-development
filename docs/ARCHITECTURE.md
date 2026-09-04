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
├── public/assets/       # Ported CSS/JS/images from legacy-static
├── resources/views/     # Blade layouts + public pages (feature/03)
├── routes/web.php
└── scripts/             # Helper scripts
```

## Locales

Public URLs: `/{locale}/...` with `en` and `ar` (started in feature/03; middleware polish in feature/04).  
HTML `lang` / `dir` set from locale in the Blade layout.

## SEO

Basic meta/canonical/hreflang in `partials/seo.blade.php` (feature/03).  
Planned `SeoBuilder`, sitemap, robots (feature/06).

## Public Blade (feature/03)

| Piece | Path |
|-------|------|
| Layout | `resources/views/layouts/app.blade.php` |
| Partials | `header`, `footer`, `lang-switcher`, `seo` |
| Pages | `home`, `categories/show`, `articles/show` |
| Controllers | `Home`, `Category`, `Article`, `Contact` |

`Setting::getValue()` caches decoded JSON values only (never Eloquent models).

## Domain models (feature/02)

| Model | Notes |
|-------|--------|
| `Category` | EN/AR name, slug, description, SEO; media: hero, seo |
| `Article` | EN/AR content + SEO; status draft/published; featured/trending |
| `HeroSlide` | Home slider; optional `article_id` |
| `PopularTopic` | Home topics; optional `category_id` |
| `ContactMessage` | Public form submissions |
| `Setting` | `site`, `seo_defaults`, `organization`, `social` JSON keys |

