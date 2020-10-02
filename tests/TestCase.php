<?php

namespace Owowagency\LaravelMedia\Tests;

use OwowAgency\Snapshots\MatchesSnapshots;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;
use Owowagency\LaravelMedia\LaravelMediaServiceProvider;

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
