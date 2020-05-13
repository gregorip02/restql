<?php

namespace Tests;

use Illuminate\Http\Request;
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
        $app['config']->set('restql.query_param', 'query');
        $app['config']->set('restql.allowed_models', [
            'authors' => 'App\Author'
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
        return [
            'Restql\Providers\RestqlServiceProvider'
        ];
    }
}
