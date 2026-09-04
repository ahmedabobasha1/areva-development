# Command log

Every significant command run while building Areva CMS.  
**Never log secrets** (DB passwords, APP_KEY, real admin passwords).

---

## feature/01-scaffold-laravel

### Environment checks

```bash
php -v
composer -V
```

Result: PHP 8.3.6, Composer 2.8.12.

### Git branch + move static prototype

```bash
cd /home/ahmed-abobasha/freelance
git checkout -b feature/01-scaffold-laravel
mkdir -p legacy-static
git mv assets blog categories index.html robots.txt sitemap.xml .nojekyll legacy-static/
```

### Create Laravel 13 project and copy into repo root

```bash
cd /tmp
rm -rf areva-laravel
composer create-project laravel/laravel areva-laravel --no-interaction
cd /home/ahmed-abobasha/freelance
rsync -a --exclude='.git' /tmp/areva-laravel/ ./
```

Installed skeleton: `laravel/laravel` **v13.10.1**, framework **v13.30.1**.

### MySQL configuration

Database created by developer: `areva_cms`.

```bash
# .env updated to (password omitted from docs):
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=areva_cms
# DB_USERNAME=root
# DB_PASSWORD=<local-secret>

php artisan config:clear
php artisan db:show
php artisan migrate --force
```

Default Laravel migrations applied: `users`, `cache`, `jobs`.

### Filament 5 + Spatie packages

```bash
composer require filament/filament:"^5.0" spatie/laravel-translatable spatie/laravel-medialibrary --no-interaction
php artisan filament:install --panels --no-interaction
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-migrations" --no-interaction
php artisan migrate --force
php artisan make:filament-user --name="Areva Admin" --email="admin@areva.com.eg" --password="********" --no-interaction
php artisan route:list --path=admin
```

Resolved versions:

| Package | Version |
|---------|---------|
| laravel/framework | v13.30.1 |
| filament/filament | v5.7.8 |
| spatie/laravel-translatable | 6.14.1 |
| spatie/laravel-medialibrary | 11.23.7 |

Admin panel route: `/admin/login`.

### Documentation added

```bash
mkdir -p docs/features scripts
# wrote docs/README.md docs/SETUP.md docs/ARCHITECTURE.md docs/COMMANDS.md
# wrote docs/features/01-scaffold.md
# wrote scripts/append-command-log.sh
```

---

## Workspace path rename (2026-09-04)

```bash
cd /home/ahmed-abobasha
mv freelance areva-development
cd areva-development
```

Local project path is now `/home/ahmed-abobasha/areva-development` (same name as the GitHub repo). Re-open this folder in Cursor if the IDE still points at `freelance`.

---

## feature/02-schema-models

```bash
cd /home/ahmed-abobasha/areva-development
git checkout -b feature/02-schema-models
```

Created migrations:

```bash
# files under database/migrations/
# 2026_09_04_160000_create_categories_table.php
# 2026_09_04_160100_create_articles_table.php
# 2026_09_04_160200_create_hero_slides_table.php
# 2026_09_04_160300_create_popular_topics_table.php
# 2026_09_04_160400_create_contact_messages_table.php
# 2026_09_04_160500_create_settings_table.php
```

Models: `Category`, `Article`, `HeroSlide`, `PopularTopic`, `ContactMessage`, `Setting`.

```bash
php artisan migrate --force
php artisan db:seed --force
php artisan tinker --execute="echo 'categories='.App\\Models\\Category::count().' articles='.App\\Models\\Article::count().' settings='.App\\Models\\Setting::count();"
```

Result: categories=5, articles=1, settings=4.

---

## feature/02-schema-models

```bash
cd /home/ahmed-abobasha/areva-development
git checkout -b feature/02-schema-models
```

Created migrations + models for `categories`, `articles`, `hero_slides`, `popular_topics`, `contact_messages`, `settings`.

```bash
php artisan migrate --force
php artisan db:seed --force
php artisan tinker --execute="echo 'categories='.App\\Models\\Category::count().' articles='.App\\Models\\Article::count().' settings='.App\\Models\\Setting::count();"
```

Result: 5 categories, 1 article, 4 settings keys.

---

## feature/03-blade-shell

```bash
cd /home/ahmed-abobasha/areva-development
git checkout -b feature/03-blade-shell
```

### Port static assets into Laravel public

```bash
mkdir -p public/assets
cp -a legacy-static/assets/. public/assets/
```

Adjusted CSS so `.lang-btn` works as `<a>` (locale URLs). Removed JS-only language switcher logic from `public/assets/js/main.js`.

### Blade layout + pages + controllers

Created:

- `resources/views/layouts/app.blade.php`
- `resources/views/partials/{seo,header,footer,lang-switcher}.blade.php`
- `resources/views/home.blade.php`
- `resources/views/categories/show.blade.php`
- `resources/views/articles/show.blade.php`
- `app/Http/Controllers/{Home,Category,Article,Contact}Controller.php`
- Updated `routes/web.php` — `/` → `/en`; `/{locale}` home, category, blog, contact POST

### Setting cache fix (HTTP 500 on category/article)

Bug: `Setting::getValue()` cached Eloquent models with `Cache::rememberForever` (database driver). Unserialize failed with “incomplete object”.

```bash
# Fixed app/Models/Setting.php to cache $setting?->value only
php artisan cache:clear
php artisan view:clear
php artisan tinker --execute="Illuminate\Support\Facades\DB::table('cache')->truncate();"
```

### Verify public pages

```bash
php -r '
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
foreach ([
  "/en",
  "/en/categories/new-cairo",
  "/en/blog/future-of-modern-living-in-new-cairo",
  "/ar",
  "/ar/categories/new-cairo",
  "/ar/blog/future-of-modern-living-in-new-cairo",
] as $uri) {
  $response = $kernel->handle(Illuminate\Http\Request::create($uri, "GET"));
  echo $uri." => ".$response->getStatusCode()."\n";
}
'
```

Result: all listed URLs returned **HTTP 200**.

### Documentation

```bash
# wrote docs/features/03-blade-shell.md
# updated docs/ARCHITECTURE.md
# appended this section to docs/COMMANDS.md
./scripts/append-command-log.sh "feature/03-blade-shell" "php artisan cache:clear" "After Setting::getValue cache fix"
```


### 2026-09-04T15:41:39Z (feature/03-blade-shell)

```bash
php artisan cache:clear && php artisan view:clear
```

Cleared stale Setting model cache after getValue fix

### 2026-09-04T15:41:39Z (feature/03-blade-shell)

```bash
php artisan tinker --execute="Illuminate\Support\Facades\DB::table('cache')->truncate();"
```

Truncated cache table to drop serialized Setting models

---

## feature/04-public-routes

```bash
cd /home/ahmed-abobasha/areva-development
git checkout -b feature/04-public-routes
```

### Locale config + middleware + URL helper

Created:

- `config/areva.php`
- `app/Http/Middleware/SetLocale.php`
- `app/Support/LocaleUrl.php`
- Registered `locale` alias in `bootstrap/app.php`
- View composer for `navCategories` in `AppServiceProvider`

### Model slug scopes + route polish

```bash
# Added Category::whereSlug / Article::whereSlug (Spatie whereJsonContainsLocales)
# Updated routes/web.php with middleware('locale') + GET contact redirect
# Controllers no longer call app()->setLocale() manually
```

### Verify

```bash
php artisan route:list --columns=method,uri,name,middleware
php -r '
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
foreach (["/","/en","/en/categories/new-cairo","/en/blog/future-of-modern-living-in-new-cairo","/en/contact","/ar","/xx"] as $uri) {
  $response = $kernel->handle(Illuminate\Http\Request::create($uri, "GET"));
  echo $uri." => ".$response->getStatusCode()."\n";
}
'
```

Result: `/` and `/en/contact` redirect; public pages **200**; invalid locale `/xx` → **404**.

### Documentation

```bash
# wrote docs/features/04-public-routes.md
# updated docs/ARCHITECTURE.md + docs/COMMANDS.md
./scripts/append-command-log.sh "feature/04-public-routes" "php artisan route:list" "Verified locale middleware on public routes"
```


### 2026-09-04T15:50:55Z (feature/04-public-routes)

```bash
git checkout -b feature/04-public-routes
```

Started public routes / locale middleware feature

### 2026-09-04T15:50:55Z (feature/04-public-routes)

```bash
php artisan route:list --columns=method,uri,name,middleware
```

Listed public locale routes with middleware

---

## feature/05-filament-resources

```bash
cd /home/ahmed-abobasha/areva-development
git checkout -b feature/05-filament-resources
```

### Install Filament 5 translatable plugin

```bash
composer require ezappslab/filament-translatable --no-interaction
php artisan filament-translatable:install --no-interaction
```

Configured `config/filament-translatable.php` locales to **English + Arabic**.

Registered `FilamentTranslatablePlugin` on `AdminPanelProvider` with the same locales.

### Generate + polish resources

```bash
php artisan make:filament-resource Category --generate --embed-schemas --embed-table --record-title-attribute=name --no-interaction
php artisan make:filament-resource Article --generate --embed-schemas --embed-table --record-title-attribute=title --no-interaction
php artisan make:filament-resource HeroSlide --generate --embed-schemas --embed-table --record-title-attribute=title --no-interaction
php artisan make:filament-resource PopularTopic --generate --embed-schemas --embed-table --record-title-attribute=title --no-interaction
php artisan make:filament-resource ContactMessage --generate --embed-schemas --embed-table --record-title-attribute=name --no-interaction
php artisan make:filament-resource Setting --generate --embed-schemas --embed-table --record-title-attribute=key --no-interaction
php artisan filament:optimize-clear
```

Added SEO section helper `app/Filament/Support/SeoFields.php` and wired `SelectLocaleAction` + translatable page concerns on Category/Article/HeroSlide/PopularTopic pages.

### Verify

```bash
php artisan about
# Resource URLs resolve under /admin/{categories,articles,hero-slides,popular-topics,contact-messages,settings}
# /admin/login => HTTP 200
```

### Documentation

```bash
# wrote docs/features/05-filament-resources.md
# updated docs/ARCHITECTURE.md + docs/COMMANDS.md
./scripts/append-command-log.sh "feature/05-filament-resources" "composer require ezappslab/filament-translatable" "Filament 5 EN/AR admin translations"
```


### 2026-09-04T15:59:18Z (feature/05-filament-resources)

```bash
composer require ezappslab/filament-translatable --no-interaction
```

Installed Filament 5 Spatie translatable plugin

### 2026-09-04T15:59:18Z (feature/05-filament-resources)

```bash
php artisan make:filament-resource Category Article HeroSlide PopularTopic ContactMessage Setting --generate ...
```

Generated Filament resources then customized forms/SEO/locale switcher

### 2026-09-04T15:59:18Z (feature/05-filament-resources)

```bash
php artisan filament:optimize-clear
```

Cleared Filament component cache after resources

---

## feature/06-seo

```bash
cd /home/ahmed-abobasha/areva-development
git checkout -b feature/06-seo
```

### SeoBuilder + dynamic sitemap/robots

Created:

- `app/Support/SeoBuilder.php`
- `app/Http/Controllers/SitemapController.php`
- `app/Http/Controllers/RobotsController.php`
- `resources/views/seo/sitemap.blade.php`
- Routes `/sitemap.xml` and `/robots.txt`
- Removed `public/robots.txt` (static file would bypass the route)

Updated `HomeController`, `CategoryController`, `ArticleController` to use `SeoBuilder`.

### Verify

```bash
php -r '
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
foreach (["/robots.txt","/sitemap.xml","/en"] as $uri) {
  echo $uri." => ".$kernel->handle(Illuminate\Http\Request::create($uri,"GET"))->getStatusCode()."\n";
}
'
```

Result: **200** for robots, sitemap, and home with canonical/hreflang/JSON-LD present.

### Documentation

```bash
# wrote docs/features/06-seo.md
# updated docs/ARCHITECTURE.md + docs/COMMANDS.md
./scripts/append-command-log.sh "feature/06-seo" "git checkout -b feature/06-seo" "Started SEO builder / sitemap / robots feature"
```


### 2026-09-04T16:02:58Z (feature/06-seo)

```bash
git checkout -b feature/06-seo
```

Started SEO builder / sitemap / robots feature

### 2026-09-04T16:02:58Z (feature/06-seo)

```bash
rm public/robots.txt
```

Removed static robots so Laravel route serves dynamic robots.txt

---

## chore/maintenance — media uploads

```bash
cd /home/ahmed-abobasha/areva-development
git checkout -b chore/maintenance
composer require filament/spatie-laravel-media-library-plugin:"^5.0" -W --no-interaction
php artisan storage:link
# FILESYSTEM_DISK=public in .env
```

Added Filament `SpatieMediaLibraryFileUpload` fields:

- Articles: `cover` (main), `gallery` (related), `seo`
- Categories: `hero` (main), `seo`
- Hero slides / Popular topics: `image`

Public article view shows gallery images from the `gallery` collection.


### 2026-09-04T22:24:41Z (chore/maintenance)

```bash
composer require filament/spatie-laravel-media-library-plugin:"^5.0" -W
```

Filament admin image uploads via Spatie Media Library

### 2026-09-04T22:24:41Z (chore/maintenance)

```bash
php artisan storage:link
```

Public disk symlink for uploaded media URLs

### Documentation refresh (chore/maintenance)

```bash
# Expanded docs/features/maintenance-media.md with full file change list
# Updated docs/README.md branch index
git add docs/
git commit -m "Document all chore/maintenance media upload changes."
git push -u origin chore/maintenance
```


### 2026-09-04T22:30:16Z (chore/maintenance)

```bash
git commit && git push
```

Documented full file change list for media uploads on chore/maintenance

### Slug auto-generation (chore/maintenance)

```bash
# Added app/Support/Slug.php
# CreateArticle / CreateCategory: mutateFormDataBeforeCreate generates slug
# Article + Category forms: slug visibleOn('edit') only
```


### 2026-09-04T22:40:12Z (chore/maintenance)

```bash
slug auto from title/name on create
```

Slug hidden on create; editable on edit for articles and categories

### 2026-09-04T22:45:20Z (chore/maintenance)

```bash
update App\Support\Slug to keep Arabic letters
```

Arabic titles now produce Arabic URL slugs instead of Latin transliteration
