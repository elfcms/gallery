<?php

namespace Elfcms\Gallery\Providers;

use Elfcms\Basic\Http\Middleware\AccountUser;
use Elfcms\Basic\Http\Middleware\AdminUser;
use Elfcms\Basic\Http\Middleware\CookieCheck;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class ElfGalleryProvider extends ServiceProvider
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
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'gallery');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'gallery');

        $this->publishes([
            __DIR__.'/../config/gallery.php' => config_path('elfcms/gallery.php'),
        ]);

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/elfcms/gallery'),
        ]);

        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/elfcms/gallery'),
        ]);

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/elfcms/gallery'),
        ], 'public');

        $startFile = __DIR__.'/../../start.json';
        $firstStart = false;
        if (file_exists($startFile)) {
            $json = File::get($startFile);
            $fileArray = json_decode($json,true);
            if ($fileArray['first_run']) {
                $firstStart = true;
            }
        }
        if ($firstStart) {
            Artisan::call('vendor:publish',['--provider'=>'Elfcms\Gallery\Providers\ElfGalleryProvider','--force'=>true]);
            Artisan::call('migrate');
            if (unlink($startFile)) {
                //
            }
            elseif (!empty($fileArray)) {
                file_put_contents($startFile,json_encode($fileArray));
            }
        }

        $router->middlewareGroup('admin', array(
            AdminUser::class
        ));

        $router->middlewareGroup('account', array(
            AccountUser::class
        ));

        $router->middlewareGroup('cookie', array(
            CookieCheck::class
        ));
    }
}
