# Setup

## Requirements

- PHP **8.3+** with `pdo_mysql`
- Composer 2
- MySQL / MariaDB
- Node.js (for Vite assets later)

## Installed versions (scaffold)

| Package | Version |
|---------|---------|
| laravel/framework | v13.30.1 |
| filament/filament | v5.7.8 |
| spatie/laravel-translatable | 6.14.1 |
| spatie/laravel-medialibrary | 11.23.7 |

## 1. Clone & install

```bash
git clone git@github.com:ahmedabobasha1/areva-development.git
cd areva-development
git checkout feature/01-scaffold-laravel   # or main after merge
composer install
cp .env.example .env
php artisan key:generate
```

## 2. MySQL

Create an empty database (example name used in this project):

```sql
CREATE DATABASE areva_cms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Set in `.env` (do **not** commit `.env`):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=areva_cms
DB_USERNAME=root
DB_PASSWORD=your_password_here
APP_NAME="Areva Development"
```

## 3. Migrate

```bash
php artisan migrate
```

## 4. Filament admin user

If none exists yet:

```bash
php artisan make:filament-user
```

Scaffold default (change after first login):

- URL: `http://localhost:8000/admin/login`
- Email: `admin@areva.com.eg`
- Password: set via `make:filament-user` (local only)

## 5. Run locally

```bash
php artisan serve
```

Public site placeholder: `http://localhost:8000`  
Admin: `http://localhost:8000/admin`

## Legacy static prototype

Previous HTML/CSS site is under [`legacy-static/`](../legacy-static/) for design reference until Blade cutover.
