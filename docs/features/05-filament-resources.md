# Feature 05 — Filament resources (EN/AR)

**Branch:** `feature/05-filament-resources`

## Goal

Admin CRUD for Areva CMS content with locale switching (EN/AR) and SEO fields.

## Delivered

- Package: `ezappslab/filament-translatable` (Filament 5 + Spatie Translatable)
- Panel plugin locales: English + Arabic
- Resources:
  - Categories (content + SEO)
  - Articles (content + RichEditor body + SEO + status)
  - Hero slides
  - Popular topics
  - Contact messages (inbox; mark read on open; no create)
  - Settings (JSON value editor)
- Shared `App\Filament\Support\SeoFields`
- Locale switcher on list/create/edit for translatable resources

## Admin

- URL: `/admin/login`
- Brand: Areva CMS

## Verify

```bash
php artisan filament:optimize-clear
# Open /admin/login and confirm resources appear under Content / Home / Inbox / System
```

## Next

`feature/06-seo` — SeoBuilder, sitemap.xml, robots.txt, published-only indexing.
