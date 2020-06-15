<?php

namespace Testing;

use Illuminate\Http\Request;
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

        // Setup database connection
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => $this->appNamespace('database/testing.sqlite')
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
            'Restql\Providers\RestqlServiceProvider'
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
        return realpath(__DIR__ . '/App/' . $path);
    }
}
