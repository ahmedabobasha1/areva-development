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

Public URLs: `/{locale}/...` with locales from `config/areva.php` (`en`, `ar`).  
`SetLocale` middleware (`locale` alias) sets `app()->setLocale()`.  
HTML `lang` / `dir` set from locale in the Blade layout.  
`App\Support\LocaleUrl` builds home/category/article/contact URLs.  
Models resolve translated slugs with `whereSlug()` (JSON locale columns).

## SEO

`App\Support\SeoBuilder` builds page SEO (meta/OG/hreflang/JSON-LD/robots).  
`partials/seo.blade.php` renders tags.  
Dynamic routes: `/sitemap.xml`, `/robots.txt` (indexable published content only).

## Public Blade (feature/03)

| Piece | Path |
|-------|------|
| Layout | `resources/views/layouts/app.blade.php` |
| Partials | `header`, `footer`, `lang-switcher`, `seo` |
| Pages | `home`, `categories/show`, `articles/show` |
| Controllers | `Home`, `Category`, `Article`, `Contact` |

`Setting::getValue()` caches decoded JSON values only (never Eloquent models).

## Filament admin (feature/05)

Panel path: `/admin` with `ezappslab/filament-translatable` locale switcher (EN/AR).

| Resource | Group | Notes |
|----------|-------|-------|
| Categories | Content | Translatable + SEO + main/SEO images |
| Articles | Content | Translatable + RichEditor + SEO + cover/gallery/SEO images |
| Hero slides | Home | Translatable titles/CTAs + main image |
| Popular topics | Home | Translatable + main image |
| Contact messages | Inbox | Read-only create; mark read on open |
| Settings | System | JSON `value` editor |

## Domain models (feature/02)

| Model | Notes |
|-------|--------|
| `Category` | EN/AR name, slug, description, SEO; media: hero, seo |
| `Article` | EN/AR content + SEO; status draft/published; featured/trending |
| `HeroSlide` | Home slider; optional `article_id` |
| `PopularTopic` | Home topics; optional `category_id` |
| `ContactMessage` | Public form submissions |
| `Setting` | `site`, `seo_defaults`, `organization`, `social` JSON keys |

