# Maries Theme

A TastyIgniter 4 theme for **Maries**, an Italian restaurant in Tunis,
Tunisia. It is a port of the restaurant's original static Bootstrap site
(`index.html` / `style.css` / `main.js`, preserved in git history) into a
full TastyIgniter theme, with the menu and ordering flow wired to the Local and
Cart extensions through Livewire.

## Structure

```
themes/pasta/                      Theme directory (code: maries)
  theme.json                       Drop-in manifest for manual (non-Composer) installs
  composer.json                    Composer manifest (name, type, code, paths)
  theme.php                        Runs on every theme page request
  bootstrap.php                    Autoloader + Livewire component registration + helpers
  seed.php                         Idempotent demo-menu seed (run once, see below)
  screenshot.png                   1200x900 theme preview image
  index.html                       Original static site, kept for reference
  public/                          Published to public/vendor/maries
    css/style.css                  Ported stylesheet + cart/menu/booking additions
    js/main.js                     Ported JavaScript (isotope menu filter removed)
    img/ ...                       Hero slides, menu photos, gallery, testimonials
    video/about.mp4                About section video
  src/Livewire/
    MenuList.php                   Menu list with category filters (Maries\Livewire\)
    CartBox.php                    Floating order cart + checkout (places orders)
    Booking.php                    Reservation booking form (Livewire)
  resources/
    meta/
      assets.json                  Favicon, meta tags, global CSS/JS
      fields.php                   Theme settings (Design > Themes > Customise)
    views/
      _layouts/default.blade.php   Page shell: header, footer, floating cart
      _pages/home.blade.php        Homepage (permalink: /)
      includes/                    hero, about, menu, events, book, gallery,
                                   testimonials, contact, header, footer partials
      livewire/                    menu-list, cart-box (with checkout) and booking views
```

## Installation

**Via Composer** (distributable / marketplace): the package registers itself
through `extra.tastyigniter-theme` in `composer.json` (Composer-installed
themes live in `vendor/` and are discovered via the package manifest).

**Manual drop-in**: place the theme directory at `themes/pasta/`. TastyIgniter
discovers manually installed themes by globbing `themes/*/theme.json` (one
directory level), so the directory must sit directly under `themes/` and contain
a `theme.json` alongside `composer.json`.

Publish the theme assets to the web-accessible `public/vendor/maries`
directory:

```bash
php artisan igniter:theme-vendor-publish --theme=maries
```

Note: this command silently skips the copy if the destination directory
already exists — remove `public/vendor/maries` before re-publishing after
asset changes.

## Activation

Install and activate the theme from the Admin (Design > Themes). For a manual
install the row must be created with `status` and `is_default` set, which the
Admin UI does when you activate the theme.

## Demo menu seed

The menu section is powered by the Local extension's menu items. Seed the
Maries menu (categories Drinks / Salads / Specialty + the site's dishes,
with photos from `public/img/menu/`) and deactivate the installer's demo menus:

```bash
php artisan tinker --execute="require base_path('themes/pasta/seed.php');"
```

The seed is idempotent — safe to re-run. Prices are stored in the store's
configured currency (GBP on this install); the original site quoted Tunisian
dinars. Menu items map to their photographs by slug, e.g. `caesar-salad` →
`img/menu/caesar.jpg` (see `MenuList::menuItemImages()`).

## Livewire components

- `maries::menu-list` — the menu section: category filter pills, a large
  featured-dish spotlight (always the first dish with a photo) and a two-column
  card grid with dish photos, Playfair Display names, prices and "Add to order"
  buttons. Livewire re-renders the whole grid on filter change; the grid drops
  to one column and the filters become a swipeable strip on mobile.
- `maries::cart-box` — a floating "Your order" panel (bottom-right,
  collapsible) with quantities, totals and the **checkout**: a "Place order"
  button that opens a form (name, email, phone, notes) and, on confirm, saves
  a real order through the Cart extension's `OrderManager` (collection / pick-up
  only, cash on delivery — the store's only enabled gateway), then shows the
  order reference and empties the cart. Orders land in Admin > Sales > Orders.
- `maries::booking` — the reservation form in the Book section, backed by
  the Reservation extension (`Admin\Models\Reservations`).

`bootstrap.php` is the single source of truth for the theme's PHP-side setup
(PSR-4 autoloader, `Livewire::component()` registration, the `maries_assets()`
helper). It is loaded from two places:

1. `theme.php` — the framework's page-render hook, and
2. the app's `AppServiceProvider` (`booted`) — required for **manual** installs,
   because TastyIgniter only loads `theme.php` for page renders. Without it,
   `/livewire/update` requests cannot resolve the components and every
   interaction fails with a 419 (Livewire's release-token check).

## Booking form

The Book section's form is a Livewire component (`maries::booking`) that
saves real reservations through the Reservation extension. On submit it:

- validates the inputs (name, email, phone, party size, date within the
  location's advance-booking window, time within opening hours),
- creates a `ti_reservations` row via `Admin\Models\Reservations` with status
  Pending and an auto-assigned table, and
- shows the confirmation reference (e.g. R-xxxx) inline without a reload.

It resolves the default location through `Location::currentOrDefault()` and
reads the same minimum-party / advance-days settings the location uses. It
depends on the Reservation extension and a default location being configured.

## Design system

The theme ships a small custom type/color system (see the "Design refinements"
block in `public/css/style.css`):

- **Type:** Playfair Display (serif display, headings + brand) paired with
  Poppins (geometric sans, body/UI). Both are self-hosted woff2 files in
  `public/fonts/` (declared via `@font-face` in the layout with
  `font-display: swap`) - no Google Fonts request at runtime.
- **Color:** one gold accent (`#ffb03b`) on warm charcoal neutrals. All
  gold-filled buttons use dark espresso text (`#1a1814`) to pass WCAG AA
  contrast, instead of the template's white-on-gold.
- **Motion:** restrained CSS transitions plus tactile `:active` states;
  everything collapses to static under `prefers-reduced-motion`.

## Theme settings

Edit under Admin > Design > Themes > Customise (defined in
`resources/meta/fields.php`):

- **General** — restaurant name, phone, email, address, opening hours. These
  populate the top bar, contact section and footer.
- **Social Media** — Facebook and Instagram URLs shown in the footer.

Settings are read in templates via `$this->theme->field_name`, e.g.
`$this->theme->phone`.

## Notes

- Order placement requires `guest_order` enabled (default) and at least one
  active payment gateway (COD ships enabled). The checkout intentionally offers
  only collection: this location has no delivery areas configured, so delivery
  would fail validation.
- The contact form is still static (as in the original site) — it sends
  nothing. Wire it to a mail handler if needed.
- The hero, events and testimonials sliders use Bootstrap's carousel and
  Swiper from CDN, as in the original site.
- `index.html` at the theme root is the untouched original static page, kept
  for reference.
- Fully responsive: the fixed header collapses to a hamburger menu, the
  hero and menu scale down, and the floating cart becomes a bottom sheet that
  starts collapsed on phones (see the mobile CSS block in `style.css` and the
  collapse logic in `main.js`).
