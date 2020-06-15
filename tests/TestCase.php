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
        $config = require(__DIR__ . '/../docker/config/restql.php');
        $app['config']->set('restql', $config);

        // Setup database connection
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => __DIR__ . '/../docker/database/testing.sqlite'
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
}
