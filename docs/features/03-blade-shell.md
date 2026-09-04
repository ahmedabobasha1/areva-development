# Feature 03 — Blade shell

**Branch:** `feature/03-blade-shell`

## Goal

Port the luxury static HTML/CSS/JS from `legacy-static/` into Laravel Blade layouts and public pages driven by Eloquent data (EN/AR URLs).

## Delivered

- Assets copied to `public/assets/` (CSS, images, JS)
- Layout: `resources/views/layouts/app.blade.php`
- Partials: `header`, `footer`, `lang-switcher`, `seo`
- Pages: `home`, `categories/show`, `articles/show`
- Controllers: `HomeController`, `CategoryController`, `ArticleController`, `ContactController`
- Routes: `/{locale}` home, category, blog, contact POST (`en|ar`); `/` → `/en`
- Locale language switcher uses real URLs (not JS-only)
- Fix: `Setting::getValue()` caches the value array only (not Eloquent models) so database cache unserialize works

## Verify

```bash
php artisan cache:clear
# Expect HTTP 200:
# /en
# /en/categories/new-cairo
# /en/blog/future-of-modern-living-in-new-cairo
# /ar (and AR category/article equivalents)
```

## Next

`feature/04-public-routes` — locale middleware, cleaner slug queries, contact GET polish.
