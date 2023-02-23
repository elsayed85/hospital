<?php

namespace App\Providers;

use App\Services\Translation\Drivers\Translation;
use App\Services\Translation\Scanner;
use App\Services\Translation\TranslationManager;
use Illuminate\Filesystem\Filesystem;
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
        $this->registerTranslation();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    public function registerTranslation()
    {
        $this->app->singleton(Scanner::class, function () {

            return new Scanner(new Filesystem(), [app_path(), resource_path()], ['trans', '__']);
        });

        $this->app->singleton(Translation::class, function ($app) {
            return (new TranslationManager($app, $app['config']['translation'], $app->make(Scanner::class)))->resolve();
        });
    }
}
