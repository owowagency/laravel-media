<?php

namespace Owowagency\LaravelMedia\Tests;

use OwowAgency\Snapshots\MatchesSnapshots;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Owowagency\LaravelMedia\LaravelMediaServiceProvider;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;

class TestCase extends BaseTestCase
{
    use MatchesSnapshots;

    /**
     * A base 64 string.
     *
     * @var string
     */
    protected $base64 = 'data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk/g8AAQsBBD48D9kAAAAASUVORK5CYII=';

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Run the package's migrations.
        $this->artisan('migrate:fresh')->run();

        // Run the tests' migrations.
        $this->loadMigrationsFrom(__DIR__.'/Support/database/migrations');
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set(
            'media-library.path_generator',
            \Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator::class,
        );

        $app['config']->set(
            'media-library.url_generator',
            \Spatie\MediaLibrary\Support\UrlGenerator\DefaultUrlGenerator::class,
        );
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            LaravelMediaServiceProvider::class,
            MediaLibraryServiceProvider::class,
        ];
    }
}
