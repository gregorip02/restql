<?php

namespace Restql\Providers;

use Restql\Services\ConfigService;
use Illuminate\Support\ServiceProvider;

final class RestqlServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->configPath(), 'restql');

        if ($this->appIsWorking()) {
            $this->app->singleton(ConfigService::class, function ($app) {
                return new ConfigService($app['config']['restql']);
            });
        }
    }

    /**
     * Determine if the app is running in console and isn't running test.
     *
     * @return bool
     */
    protected function appIsWorking(): bool
    {
        return $this->app->runningInConsole() && !$this->app->runningUnitTests();
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
        if ($this->app->runningInConsole()) {
            /// Register the RestQL config.
            $this->publishes([
                $this->configPath() => config_path('restql.php')
            ], 'restql-config');
        }
    }
}
