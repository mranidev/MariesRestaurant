<?php

declare(strict_types=1);

/**
 * Maries theme bootstrap.
 *
 * TastyIgniter only loads a manual theme's `theme.php` when a page is rendered
 * through the main controller (main.controller.beforeRemap). Livewire update
 * requests bypass that hook, so Livewire components registered only in
 * `theme.php` are unknown during hydration — Livewire then aborts the update
 * with a 419 because it cannot resolve the component.
 *
 * This file is the single source of truth for the theme's PHP-side setup. It is
 * required by both:
 *
 *   1. `theme.php` — the framework's page-render hook, and
 *   2. the app's `AppServiceProvider` (booted) — so every request, including
 *      `/livewire/update`, has the autoloader and components registered.
 *
 * A `require_once` + guard keeps it idempotent within a single request.
 */

if (!defined('MARIES_THEME_BOOTED')) {
    define('MARIES_THEME_BOOTED', true);

    // PSR-4 autoloader for the theme's PHP classes (src/ -> Maries\)
    spl_autoload_register(static function (string $class): void {
        $prefix = 'Maries\\';
        if (!str_starts_with($class, $prefix)) {
            return;
        }

        $file = __DIR__.'/src/'.str_replace('\\', '/', substr($class, strlen($prefix))).'.php';
        if (is_file($file)) {
            require $file;
        }
    });

    // Register the theme's Livewire components.
    Livewire\Livewire::component('maries::menu-list', Maries\Livewire\MenuList::class);
    Livewire\Livewire::component('maries::cart-box', Maries\Livewire\CartBox::class);
    Livewire\Livewire::component('maries::booking', Maries\Livewire\Booking::class);
    Livewire\Livewire::component('maries::flash-deals', Maries\Livewire\FlashDeals::class);
    Livewire\Livewire::component('maries::cart-item-modal', Maries\Livewire\CartItemModal::class);
    Livewire\Livewire::component('maries::menu-directory', Maries\Livewire\MenuDirectory::class);
    Livewire\Livewire::component('maries::login', Maries\Livewire\Login::class);
    Livewire\Livewire::component('maries::register', Maries\Livewire\Register::class);

    // Register the theme's translation namespace (resources/lang/{locale}/default.php)
    // so lang('maries::default.key') resolves for both English and Arabic.
    app('translator')->addNamespace('maries', __DIR__.'/resources/lang');

    // Helper for published theme assets (public/vendor/maries/...).
    if (!function_exists('maries_assets')) {
        function maries_assets(string $path = ''): string
        {
            return asset('vendor/maries/'.ltrim($path, '/'));
        }
    }
}
