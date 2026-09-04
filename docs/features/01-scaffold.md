# Feature 01 — Scaffold Laravel

**Branch:** `feature/01-scaffold-laravel`

## Goal

Turn the static Areva GitHub repo into a Laravel 13 + Filament 5 app on MySQL, keeping the old HTML under `legacy-static/`.

## Done

- [x] Branch created from `main`
- [x] Static site moved to `legacy-static/`
- [x] Laravel 13 installed at repo root
- [x] MySQL `areva_cms` configured in `.env` (not committed)
- [x] Default + media migrations run
- [x] Filament panel at `/admin`
- [x] Spatie Translatable + Media Library installed
- [x] Docs under `docs/` + command log

## How to verify

```bash
php artisan serve
# open http://localhost:8000/admin/login
```

## Next branch

`feature/02-schema-models` — Category, Article, and related tables/models/seeders.
