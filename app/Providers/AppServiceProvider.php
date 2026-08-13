<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->booted(function () {
            // Manual themes are only loaded by the main controller, which means
            // Livewire update requests never see their components. If the active
            // theme ships a bootstrap.php, load it on every request.
            $theme = resolve(\Igniter\Main\Classes\ThemeManager::class)->findTheme(\Igniter\Main\Classes\Theme::getActiveCode());
            if (! $theme) {
                return;
            }

            $bootstrap = $theme->getPath().'/bootstrap.php';
            if (is_file($bootstrap)) {
                require_once $bootstrap;
            }
        });
    }
}
