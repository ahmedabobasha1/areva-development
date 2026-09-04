# Branch docs — `chore/maintenance`

**Base:** `master`  
**Purpose:** Track every change made on this maintenance branch (media uploads + slug behavior).

---

## Commits (newest first)

| Commit | Message |
|--------|---------|
| `bbb032e` | Include latest docs commit in the maintenance branch changelog |
| `566294f` | Document every chore/maintenance change in the branch doc |
| `ebd4e6c` | Keep Arabic letters in auto-generated slugs |
| `1ebb482` | Auto-generate article and category slugs on create |
| `c84dab2` | Index chore/maintenance in the docs README branch table |
| `c3ccc16` | Document all chore/maintenance media upload changes |
| `e08d33a` | Add Filament media uploads for articles and categories |

---

## Change 1 — Media uploads in Filament admin

### Why

Models already had Spatie Media Library collections (`cover`, `gallery`, `hero`, `image`, `seo`), and public Blade already called `getFirstMediaUrl(...)`. Admin forms had **no upload fields**, so editors could not set images.

### What changed

- Installed package: `filament/spatie-laravel-media-library-plugin` (^5)
- Set `FILESYSTEM_DISK=public` in `.env.example` (local `.env` too; not committed)
- Ran `php artisan storage:link`

### Admin uploads added

| Resource | Fields | Media collection |
|----------|--------|------------------|
| Articles | Main image | `cover` |
| Articles | Related images (multiple, reorderable) | `gallery` |
| Articles | SEO / OG image | `seo` |
| Categories | Main image | `hero` |
| Categories | SEO / OG image | `seo` |
| Hero slides | Main image | `image` |
| Popular topics | Main image | `image` |

### Public site

- Article detail shows a **gallery** under the body when `gallery` media exists
- CSS: `.article-gallery` / `.article-gallery-item` (desktop + mobile)

### Files touched (media)

| File | Change |
|------|--------|
| `composer.json` | Require `filament/spatie-laravel-media-library-plugin` |
| `composer.lock` | Lockfile update |
| `.env.example` | `FILESYSTEM_DISK=public` |
| `app/Filament/Resources/Articles/ArticleResource.php` | Images section + table thumb |
| `app/Filament/Resources/Categories/CategoryResource.php` | Images section + table thumb |
| `app/Filament/Resources/HeroSlides/HeroSlideResource.php` | Image upload + table thumb |
| `app/Filament/Resources/PopularTopics/PopularTopicResource.php` | Image upload + table thumb |
| `resources/views/articles/show.blade.php` | Gallery markup |
| `public/assets/css/styles.css` | Gallery styles |
| `docs/ARCHITECTURE.md` | Note image fields on Filament resources |

### How to use

1. `/admin/articles` or `/admin/categories` (also Hero / Popular topics)
2. Edit → **Images** → upload → save
3. Public pages pick up media via Spatie helpers automatically

### Commands

```bash
git checkout -b chore/maintenance
composer require filament/spatie-laravel-media-library-plugin:"^5.0" -W --no-interaction
php artisan storage:link
php artisan filament:optimize-clear
# local .env: FILESYSTEM_DISK=public
```

---

## Change 2 — Auto-generate slug (no manual insert on create)

### Why

Admins should not type the slug when creating. Slug comes from title/name. On edit, admins may change it.

### Behavior

| Screen | Slug field |
|--------|------------|
| **Create** article / category | Hidden — generated automatically |
| **Edit** article / category | Visible — admin can modify |

### Generation rules (`App\Support\Slug`)

| Title language | Example title | Stored slug |
|----------------|---------------|-------------|
| English | `Future of Modern Living` | `future-of-modern-living` |
| Arabic | `مستقبل المعيشة في القاهرة الجديدة` | `مستقبل-المعيشة-في-القاهرة-الجديدة` |

- Arabic **letters are kept** (not transliterated to Latin)
- Spaces → hyphens; punctuation stripped
- Latin letters lowercased

### Files touched (slug)

| File | Change |
|------|--------|
| `app/Support/Slug.php` | **Added** — shared slug helper (keeps Arabic letters) |
| `app/Filament/Resources/Articles/Pages/CreateArticle.php` | `mutateFormDataBeforeCreate` sets slug from `title` |
| `app/Filament/Resources/Categories/Pages/CreateCategory.php` | `mutateFormDataBeforeCreate` sets slug from `name` |
| `app/Filament/Resources/Articles/ArticleResource.php` | Slug `visibleOn('edit')` only |
| `app/Filament/Resources/Categories/CategoryResource.php` | Slug `visibleOn('edit')` only |

---

## Change 3 — Documentation on this branch

| File | Change |
|------|--------|
| `docs/features/maintenance-media.md` | **This file** — full branch changelog (must be updated for every change) |
| `docs/COMMANDS.md` | Command log entries for media + slug work |
| `docs/README.md` | Index row for `chore/maintenance` |
| `docs/ARCHITECTURE.md` | Filament resources mention images |

---

## Full file list vs `master`

```
.env.example
app/Filament/Resources/Articles/ArticleResource.php
app/Filament/Resources/Articles/Pages/CreateArticle.php
app/Filament/Resources/Categories/CategoryResource.php
app/Filament/Resources/Categories/Pages/CreateCategory.php
app/Filament/Resources/HeroSlides/HeroSlideResource.php
app/Filament/Resources/PopularTopics/PopularTopicResource.php
app/Support/Slug.php
composer.json
composer.lock
docs/ARCHITECTURE.md
docs/COMMANDS.md
docs/README.md
docs/features/maintenance-media.md
public/assets/css/styles.css
resources/views/articles/show.blade.php
```

---

## Rule for this branch

**Any new code/config/docs change on `chore/maintenance` must be added to this file** (what / why / files / commands) in the same commit or immediately after.

---

## Verify checklist

- [ ] Admin Images uploads work for articles + categories
- [ ] Article gallery shows on public article page when related images exist
- [ ] Create article/category: no slug field; slug saved from title/name
- [ ] Edit: slug visible and editable
- [ ] Arabic title → Arabic slug (letters preserved)
- [ ] English title → Latin hyphenated slug
