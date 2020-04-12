<?php

namespace Restql\Providers;

use Illuminate\Support\ServiceProvider;

class RestqlServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->configPath(), 'restql');
    }

    /**
     * Set the config path
     *
     * @return string
     */
    protected function configPath(): string
    {
        return __DIR__ . '/../../config/restql.php';
    }

    /**
     * Register the config for publishing
     *
     */
    public function boot(): void
    {
        $this->publishes([
            $this->configPath() => config_path('restql.php')
        ], 'restql-config');
    }
}
