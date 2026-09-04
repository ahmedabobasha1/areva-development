# Branch docs — `chore/maintenance`

**Base:** `master`  
**Purpose:** Track every change made on this maintenance branch (media uploads + slug behavior).

---

## Commits (newest first)

| Commit | Message |
|--------|---------|
| _(pending)_ | Slug cleaning on edit (title/name + slug field) |
| `b3386fe` | Format slugs like Str::slug while keeping Arabic letters |
| `773c5ea` | Finalize maintenance branch changelog with complete history |
| `d7c0952` | Keep maintenance branch commit table complete |
| `7e1ba98` | Sync commit list in maintenance branch documentation |
| `bbb032e` | Include latest docs commit in the maintenance branch changelog |
| `566294f` | Document every chore/maintenance change in the branch doc |
| `ebd4e6c` | Keep Arabic letters in auto-generated slugs |
| `1ebb482` | Auto-generate article and category slugs on create |
| `c84dab2` | Index chore/maintenance in the docs README branch table |
| `c3ccc16` | Document all chore/maintenance media upload changes |
| `e08d33a` | Add Filament media uploads for articles and categories |

> When adding new **feature** work, append a new “Change N” section and list the new commit(s). Do not create infinite docs-only commits only to refresh this table.

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
| **Create** article / category | Hidden — generated automatically from title/name |
| **Edit** article / category | Visible — regenerates from title/name on blur; manual slug input is also cleaned like `Str::slug` on blur/save |

### Generation rules (`App\Support\Slug`)

Works like Laravel `Str::slug` for spaces and special characters:

| Input issue | Handling |
|-------------|----------|
| Spaces / underscores / punctuation / symbols | Converted to `-` (same idea as `Str::slug`) |
| Multiple separators | Collapsed to one `-` |
| Leading/trailing `-` | Trimmed |
| English / ASCII titles | Uses `Str::slug()` exactly |
| Arabic titles | Same formatting, **Arabic letters kept** |

| Title language | Example title | Stored slug |
|----------------|---------------|-------------|
| English | `Future of Modern Living!!!` | `future-of-modern-living` |
| English | `foo_bar.baz` | `foo-barbaz` (same as `Str::slug`) |
| Arabic | `مستقبل المعيشة!!! في القاهرة` | `مستقبل-المعيشة-في-القاهرة` |

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


## Change 4 — Slug formatting like `Str::slug`

### Why

Slugs must clean spaces and special characters the same way Laravel `Str::slug` does, while still keeping Arabic letters.

### Behavior

- ASCII/English titles → exact `Str::slug($title)`
- Arabic / mixed titles → non-letter/number runs become `-`, collapse hyphens, keep Arabic letters

### Files

| File | Change |
|------|--------|
| `app/Support/Slug.php` | Updated formatting rules |
| `docs/features/maintenance-media.md` | This changelog entry |

---

## Change 5 — Slug cleaning on edit as well

### Why

Create already generated/cleaned slugs. Edit must do the same: spaces and special characters cleaned, Arabic letters kept.

### Behavior on edit

1. Change **title** / **name** → slug auto-updates on blur via `Slug::from`
2. Type in **slug** → cleaned on blur via `Slug::from`
3. On save → `EditArticle` / `EditCategory` `mutateFormDataBeforeSave` forces `Slug::from` (falls back to title/name if empty)

### Files

| File | Change |
|------|--------|
| `app/Filament/Resources/Articles/ArticleResource.php` | Title + slug live blur cleaning on edit |
| `app/Filament/Resources/Categories/CategoryResource.php` | Name + slug live blur cleaning on edit |
| `app/Filament/Resources/Articles/Pages/EditArticle.php` | `mutateFormDataBeforeSave` slug normalize |
| `app/Filament/Resources/Categories/Pages/EditCategory.php` | `mutateFormDataBeforeSave` slug normalize |
| `docs/features/maintenance-media.md` | This changelog entry |

---
## Rule for this branch

**Any new code/config/docs change on `chore/maintenance` must be added to this file** (what / why / files / commands) in the same commit or immediately after.

---

## Verify checklist

- [ ] Admin Images uploads work for articles + categories
- [ ] Article gallery shows on public article page when related images exist
- [ ] Create article/category: no slug field; slug saved from title/name
- [ ] Edit: slug visible and editable
- [ ] Edit: title/name blur regenerates slug; slug field cleaned on blur/save
- [ ] Arabic title → Arabic slug (letters preserved)
- [ ] English title → Latin hyphenated slug
