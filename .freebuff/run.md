# Run doc — Maries Restaurant (TastyIgniter 4)

## Reproduce the uncommitted artifacts (fresh checkout)

1. **Copy the environment file** from the main checkout:
   `cp .env .env` is already present in this worktree — for a fresh checkout,
   copy the working `.env` from the main checkout (never commit it). It wires
   the app to MariaDB at `127.0.0.1:3307` (db `tastyigniter`, user `root`, no
   password, prefix `ti_`) and sets `APP_URL=http://127.0.0.1:8080`.
2. **Install dependencies**: `composer install` (vendor/ is already populated
   in this worktree). PHP needs the `bcmath` and `exif` extensions.
3. **Start MariaDB** (the sandbox instance):
   `mariadbd --datadir="<workspace>/.freebuff/mysql/data" --socket="<workspace>/.freebuff/mysql/mysql.sock" --port=3307 --bind-address=127.0.0.1` — or the pre-existing sandbox instance on port 3307.
4. **Install/activate the theme** (already done here): `themes/maries` is the
   active theme (code `maries`). DB row in `ti_themes` must have `status=1` and
   `is_default=1` or the active theme silently falls back to the first enabled
   one. `app/Providers/AppServiceProvider.php` resolves the active theme's
   `bootstrap.php` on every request (required for manual-theme Livewire
   components).
5. **Publish theme assets**:
   `php -d extension=bcmath -d extension=exif artisan igniter:theme-vendor-publish --theme=maries`
   (remove `public/vendor/maries` first — the command silently skips the
   copy if the destination already exists).
6. **Seed the demo menu** (idempotent):
   `php -d extension=bcmath -d extension=exif artisan tinker --execute="require base_path('themes/maries/seed.php');"`

## Run the server

```bash
php -d extension=bcmath -d extension=exif artisan serve --host=127.0.0.1 --port=8080
```

- Homepage: http://127.0.0.1:8080/  (Maries theme, Livewire menu/cart/booking)
- Admin:    http://127.0.0.1:8080/admin (login `admin@example.com` / `admin123`)
- Detach with `nohup ... & disown` (or `setsid` if the runner reaps the group).
