<?php

declare(strict_types=1);

use Igniter\System\Facades\Assets;

/**
 * Theme PHP file.
 *
 * Loaded on every theme page request, just before the page code runs.
 * PHP-side setup (autoloader + Livewire component registration) lives in
 * bootstrap.php, which is also required from the app's AppServiceProvider so
 * Livewire update requests can resolve the components.
 */

require_once __DIR__.'/bootstrap.php';

Assets::putJsVars(['themeCode' => 'maries']);
