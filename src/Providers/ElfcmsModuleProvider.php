<?php

namespace Elfcms\Gallery\Providers;

use Elfcms\Elfcms\Http\Middleware\AccountUser;
use Elfcms\Elfcms\Http\Middleware\AdminUser;
use Elfcms\Elfcms\Http\Middleware\CookieCheck;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class ElfcmsModuleProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $moduleDir = dirname(__DIR__);

        $this->loadRoutesFrom($moduleDir . '/routes/web.php');
        $this->loadViewsFrom($moduleDir . '/resources/views', 'elfcms');
        $this->loadMigrationsFrom($moduleDir . '/database/migrations');

        $this->loadTranslationsFrom($moduleDir . '/resources/lang', 'gallery');

        $this->publishes([
            $moduleDir . '/resources/lang' => resource_path('lang/elfcms/gallery'),
        ], 'lang');

        $this->publishes([
            $moduleDir . '/config/gallery.php' => config_path('elfcms/gallery.php'),
        ], 'config');

        $this->publishes([
            $moduleDir . '/resources/views/admin' => resource_path('views/elfcms/admin'),
        ], 'admin');

        $this->publishes([
            $moduleDir . '/public/admin' => public_path('elfcms/admin/modules/gallery/'),
        ], 'admin');

        $this->publishes([
            $moduleDir.'/resources/views/components' => resource_path('views/elfcms/modules/gallery/components'),
        ],'components');

        Blade::component('gallery-slider', \Elfcms\Gallery\View\Components\Slider::class);
        Blade::component('gallery-gallery', \Elfcms\Gallery\View\Components\Gallery::class);
    }
}
