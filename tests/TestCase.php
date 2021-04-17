<?php

namespace Testing;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Add RestQL config from docker evironment.
        $config = require($this->appNamespace('config/restql.php'));
        $app['config']->set('restql', $config);

        $database = $this->appNamespace('database/database.sqlite');

        // Create database if not exists
        file_exists($database) ?: file_put_contents($database, '');

        // Setup database connection
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => $database
        ]);
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
            'Restql\Providers\RestqlServiceProvider',
            'Testing\Provider',
        ];
    }

    /**
     * Get the application testing namespace.
     *
     * @param  string $path
     * @return string
     */
    protected function appNamespace(string $path = ''): string
    {
        return __DIR__ . '/App/' . $path;
    }
}
