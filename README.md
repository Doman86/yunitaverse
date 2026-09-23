# YUNITAVERSE ☾

**YUNITAVERSE** is a personal digital universe / digital scrapbook / personal archive for **Yunita Dwi Alung** — a small place she can visit again and again.

> YUNITAVERSE is the website (the brand). Yunita Dwi Alung is the person it belongs to.

Built with Laravel 13, Blade, Tailwind CSS 4, Alpine.js, and MySQL. Mobile-first, soft dark, editorial — designed to be used as an Instagram bio link.

---

## ✦ Features

- **Landing page** — moon, YUNITAVERSE title, real-time clock + date (Asia/Jakarta), ENTER button
- **Home** — central hub with four doors: HER ARCHIVE, MOD, SOUNDTRACK, MY SPACE
- **HER ARCHIVE** — Profile, Moments (scrapbook), Journey (timeline), Achievements, Activities, Favorites
- **MOD** — mood-based experience (Good / Normal / Sad) with Things To Do, Notes, Photos, Music (Spotify embeds), Surprise
- **SOUNDTRACK** — playlists & songs via legal Spotify embeds
- **MY SPACE** — Yunita's personal corner to create / edit / delete her own content (ownership enforced via policies)
- **Hidden admin panel** at `/manage` — full CRUD, publish/unpublish, search & filter, profile, users, settings
- **Hidden admin entry** — click the YUNITAVERSE title on the landing page 5 times
- **Settings** — site name, tagline, timezone, accent theme, social preview (Open Graph / Twitter card)

## ✦ Requirements

- PHP >= 8.3
- Composer
- Node.js & npm
- MySQL

## ✦ Installation

```sh
composer install
npm install
cp .env.example .env
php artisan key:generate
```

### Database setup

Create a MySQL database named `yunitaverse` (or adjust `DB_DATABASE` in `.env`), then:

```sh
php artisan migrate --seed
php artisan storage:link
```

### Local development

```sh
php artisan serve
npm run dev
```

Or use the all-in-one command:

```sh
composer run dev
```

### Build for production

```sh
npm run build
```

## ✦ Login

There is **no public registration**. Two roles only: `admin` and `yunita`.

| Role | Username | Password | Entrance |
|---|---|---|---|
| Admin | `Doman` | `password` | `/manage/login` (hidden — or click the landing title 5×) |
| Yunita | `Yunita` | `password` | `/login` → My Space |

> ⚠️ **Change both default passwords before production!**

## ✦ Routing overview

```
/                       Landing page (realtime clock, hidden admin entry)
/home                   Central hub
/archive/profile        Profile
/archive/moments        Moments (scrapbook)
/archive/journey        Journey (timeline)
/archive/achievements   Achievements
/archive/activities     Activities
/archive/favorites      Favorites
/mod                    MOD mood select
/mod/good|normal|sad    Mood hubs
/mod/{mood}/things|notes|photos|music
/soundtrack             Soundtrack page
/login                  Yunita login
/my-space               My Space (auth required)
/manage/login           Hidden admin login
/manage/dashboard       Admin dashboard
```

## ✦ Content ownership

Every content row stores `created_by`. Yunita can only see / edit / delete **her own** content — enforced by `ContentPolicy` and query-level `ownedBy()` scopes. Admin has full access. Changing the ID in the URL will not leak other users' content.

## ✦ Deployment (Hostinger / shared hosting)

1. Point the document root to `/public`.
2. Set the production environment in `.env`:

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yunitaverse.my.id
```

3. On the server:

```sh
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan storage:link
php artisan route:cache
php artisan config:cache
npm run build
```

## ✦ Production security checklist

- [ ] Change default passwords for `Doman` and `Yunita`
- [ ] `APP_DEBUG=false`
- [ ] Strong `APP_KEY`
- [ ] MySQL user with a strong password
- [ ] HTTPS enabled
- [ ] Never commit `.env` to Git

## ✦ Notes

- Spotify content is embedded legally via Spotify Embed — nothing is downloaded or re-hosted.
- The realtime clock uses the visitor's browser with the `Asia/Jakarta` timezone; it is never taken from the database.
- MOD SAD content focuses on rest, music, photos, and healthy support — nothing harmful.

---

*made to be revisited.* ☾

Auto deploy test - 09/23/2026 21:16:01
