<?php

namespace Owowagency\LaravelBasicMedia;

use Illuminate\Support\ServiceProvider;

class LaravelBasicMediaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return  void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../translations', 'laravel-basic-media');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
