# Feature 06 — SEO builder, sitemap, robots

**Branch:** `feature/06-seo`

## Goal

Centralize public SEO metadata, emit JSON-LD + hreflang, and serve dynamic `sitemap.xml` / `robots.txt` that respect index flags.

## Delivered

- `App\Support\SeoBuilder` — home / category / article SEO arrays
  - meta + OG + Twitter (via partial)
  - hreflang EN/AR/`x-default`
  - JSON-LD (Organization/WebSite, CollectionPage, Article)
  - `robots` from `robots_index` / `robots_follow`
- Controllers use `SeoBuilder` instead of inline SEO arrays
- `GET /sitemap.xml` — published + indexable categories/articles for each locale
- `GET /robots.txt` — allow public site, disallow `/admin` + `/livewire`, point to sitemap
- Removed static `public/robots.txt` so the route is used

## Verify

```bash
# Expect 200
curl -I http://127.0.0.1:8000/robots.txt
curl -I http://127.0.0.1:8000/sitemap.xml
# Home HTML should include canonical, hreflang, ld+json
```

## Done

All planned feature branches `01`–`06` are implemented with per-branch docs under `docs/features/` and a full command log in `docs/COMMANDS.md`.
