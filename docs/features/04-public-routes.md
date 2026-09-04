# Feature 04 — Public routes & locale middleware

**Branch:** `feature/04-public-routes`

## Goal

Harden public locale routing: middleware, JSON slug queries, shared nav data, and URL helpers.

## Delivered

- `config/areva.php` — supported locales + default
- `SetLocale` middleware (`locale` alias) sets `app()->setLocale()`
- Routes use `->middleware('locale')` and config-driven locale list
- `Category::whereSlug()` / `Article::whereSlug()` via Spatie `whereJsonContainsLocales`
- `App\Support\LocaleUrl` helpers for home/category/article/contact
- View composer shares `navCategories` with header/footer
- `GET /{locale}/contact` redirects to `/{locale}#contact`
- Contact forms post via named route `contact.store`

## Verify

```bash
php artisan route:list --columns=method,uri,name,middleware
# Expect 200 on /en, category, article; 302 / → /en; 302 /en/contact → /en#contact; 404 /xx
```

## Next

`feature/05-filament-resources` — Filament admin CRUD for EN/AR content + SEO fields.
