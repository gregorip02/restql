<?php

namespace Testing;

use Illuminate\Support\ServiceProvider;

final class Provider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Load application routes for testing.
        $this->loadRoutesFrom(__DIR__ . '/App/routes.php');
    }
}
