# Laravel Cloud deploy checklist (Areva CMS)

Short production checklist for publishing this repo on [Laravel Cloud](https://cloud.laravel.com).

Pricing (as of public docs): **Starter $5/mo + usage** (first month free, includes $5 usage credit), **Growth $20/mo + usage**, **Business $200/mo + usage**. Set a spending limit in Cloud. Details: https://laravel.com/cloud/pricing

---

## 0. Before you start

- [ ] Merge the branch you want live into `main` (or deploy from `chore/maintenance` only for staging).
- [ ] Repo is on GitHub and Laravel Cloud can access it.
- [ ] Choose plan: **Starter** is enough for an early Areva CMS / blog.
- [ ] Prefer **MySQL** in Cloud (this app already uses `DB_CONNECTION=mysql`).

---

## 1. Create the Cloud app

1. Sign up / log in at https://cloud.laravel.com
2. Create an organization (if needed).
3. **New application** → import this GitHub repo.
4. Select the deploy branch (`main` for production).
5. Pick a region close to Egypt/EU if most visitors are local (e.g. EU West / Frankfurt).
6. Enable **scale-to-zero** on Starter to keep idle cost low.
7. Set an org **spending limit** (example: `$25`–`$40`).

---

## 2. Database

1. Add a **MySQL** database in Cloud for this environment.
2. Attach it to the app environment (Cloud usually injects DB env vars).
3. Confirm these are set (names may be auto-filled by Cloud):

```env
DB_CONNECTION=mysql
DB_HOST=...
DB_PORT=3306
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...
```

Do **not** use local `.env` values from your machine.

---

## 3. Environment variables

Set these in the Cloud environment UI (production values):

### Required

```env
APP_NAME="Areva Development"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...          # generate once; never reuse from a public gist
APP_URL=https://your-domain.com

APP_LOCALE=en
APP_FALLBACK_LOCALE=en

LOG_CHANNEL=stack
LOG_LEVEL=error

SESSION_DRIVER=database
SESSION_LIFETIME=120

CACHE_STORE=database
QUEUE_CONNECTION=database

FILESYSTEM_DISK=public
```

Generate a key locally if needed:

```bash
php artisan key:generate --show
```

Paste only into Cloud secrets — do not commit it.

### Mail (when you need contact form email)

```env
MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="${APP_NAME}"
```

Until mail is configured, `MAIL_MAILER=log` is fine for smoke tests (messages won’t leave the server).

### Media / S3 (recommended for production uploads)

Local `public` disk works for a first smoke test, but **uploads can be lost on redeploy**. For real use, set:

```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=...
AWS_SECRET_ACCESS_KEY=...
AWS_DEFAULT_REGION=...
AWS_BUCKET=...
AWS_URL=https://your-bucket-or-cdn-url
AWS_USE_PATH_STYLE_ENDPOINT=false
```

Spatie Media Library uses the default disk unless a collection overrides it. After switching to S3, re-upload or migrate existing media.

---

## 4. Build & deploy commands

Configure the Cloud build to match this project:

### Install / build

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
```

Notes:
- Vite output is required for Filament/frontend assets (`npm run build`).
- PHP **8.3+** is required (`composer.json`).

### Release / deploy hooks (typical)

```bash
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan filament:upgrade
```

Optional first-time seed (staging only, or once on empty prod):

```bash
php artisan db:seed --force
```

Default seeded admin (change password immediately):

- Email: `admin@areva.com.eg`
- Password: `password` (from `DatabaseSeeder` — **rotate after deploy**)

---

## 5. Domain & HTTPS

1. Add custom domain in Cloud (Starter includes custom domains).
2. Point DNS (A/CNAME) as Cloud instructs.
3. Wait for HTTPS certificate.
4. Set `APP_URL` to the final `https://...` URL (no trailing slash mismatch issues).
5. Hard-refresh admin and public site; Filament media previews need `APP_URL` to match the browser host.

Admin path (this app):

- `https://your-domain.com/admin`

---

## 6. Post-deploy smoke test

- [ ] `/en` and `/ar` home load
- [ ] Category page with children loads
- [ ] Article page loads
- [ ] `/admin` login works
- [ ] Create/edit article + upload cover image
- [ ] Image appears in admin Media tab and on public pages
- [ ] `/sitemap.xml` and `/robots.txt` respond
- [ ] Contact form submits without 500

---

## 7. Areva-specific notes

| Topic | What to remember |
|-------|------------------|
| Locales | Public routes are `/{locale}/...` with `en` / `ar` |
| Filament | Admin at `/admin`; EN/AR via translatable plugin |
| Popular topics | Home section uses published articles with `is_trending` |
| Nested categories | `parent_id` tree; home shows roots only |
| Media | Spatie collections: article `cover`/`gallery`/`seo`, category `hero`/`seo`, hero slide `image` |
| Queues | `QUEUE_CONNECTION=database` — add a worker/queue in Cloud if you enable queued jobs later |
| Boost | `laravel/boost` is **dev-only**; not needed in production |

---

## 8. Suggested first-month budget

For a low-traffic Areva site on **Starter** + scale-to-zero + small MySQL:

- Plan: **$0 first month**, then **$5/mo** base
- Usage: often covered partly by the **$5 monthly credit**
- Practical cap: set spending limit ~**$25** while testing

Use Cloud’s pricing calculator if traffic grows: https://laravel.com/cloud/pricing

---

## Quick command cheatsheet (Cloud console / SSH if available)

```bash
php artisan migrate --force
php artisan storage:link
php artisan db:seed --force          # once, carefully
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan filament:upgrade
php artisan about
```
