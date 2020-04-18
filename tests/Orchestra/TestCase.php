<?php

namespace Test\Orchestra;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'prefix'   => '',
            'driver'   => 'sqlite',
            'database' => ':memory:',
        ]);
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return ['Restql\Providers\RestqlServiceProvider'];
    }

    /**
     * Get base path.
     *
     * @return string
     */
    protected function getBasePath()
    {
        // The docker container laravel root path
        return '/var/www/app';
    }
}
