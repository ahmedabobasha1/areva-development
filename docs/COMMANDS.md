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

## Later features

Commands for `feature/02` … `feature/06` will be appended below as those branches are built.

---

## Workspace path rename (2026-09-04)

```bash
cd /home/ahmed-abobasha
mv freelance areva-development
cd areva-development
```

Local project path is now `/home/ahmed-abobasha/areva-development` (same name as the GitHub repo). Re-open this folder in Cursor if the IDE still points at `freelance`.
