<?php

namespace Owowagency\LaravelMedia;

use Illuminate\Support\ServiceProvider;

class LaravelMediaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return  void
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../translations' => $this->app->langPath('vendor/laravel-media'),
        ]);

        $this->loadTranslationsFrom(__DIR__.'/../translations', 'laravel-media');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laravel-media.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-media');
    }
}
