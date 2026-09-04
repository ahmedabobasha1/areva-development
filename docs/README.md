# Areva CMS documentation

| Doc | Purpose |
|-----|---------|
| [SETUP.md](SETUP.md) | Install, MySQL, migrate, run server, Filament login |
| [ARCHITECTURE.md](ARCHITECTURE.md) | App structure, models, locales, SEO (grows with features) |
| [COMMANDS.md](COMMANDS.md) | Log of every significant command/script run during the build |
| [features/](features/) | Per-feature branch notes |

## Feature branches

| Branch | Feature |
|--------|---------|
| `feature/01-scaffold-laravel` | Laravel 13 + Filament 5 + MySQL + legacy-static |
| `feature/02-schema-models` | Domain migrations/models/seeders |
| `feature/03-blade-shell` | Blade layouts from static HTML |
| `feature/04-public-routes` | Locale public routes |
| `feature/05-filament-resources` | Admin CRUD EN/AR |
| `feature/06-seo` | SeoBuilder, sitemap, robots, hreflang |
| `chore/maintenance` | Maintenance branch (media uploads + Arabic-aware auto slugs) — **full changelog:** [features/maintenance-media.md](features/maintenance-media.md) |
| `master` | Merged features 01–06 |
