<?php

namespace Restql\Providers;

use Illuminate\Support\ServiceProvider;
use Restql\Console\AuthorizerMakeCommand;
use Restql\Console\ClausuleMakeCommand;
use Restql\Console\ResolverMakeCommand;
use Restql\Console\SchemaRestqlCommand;
use Restql\Services\ConfigService;

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

        if ($this->runningInConsole()) {
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
    protected function runningInConsole(): bool
    {
        return $this->app->runningInConsole() && ! $this->app->runningUnitTests();
    }

    /**
     * Determine if the app is running test.
     *
     * @return bool
     */
    protected function runningTests(): bool
    {
        return $this->app->runningInConsole() && $this->app->runningUnitTests();
    }

    /**
     * Set the config path.
     *
     * @return string
     */
    protected function configPath(): string
    {
        return __DIR__.'/../../config/restql.php';
    }

    /**
     * Register the config for publishing.
     *
     */
    public function boot(): void
    {
        if ($this->runningInConsole()) {
            /// Register the RestQL config.
            $this->publishes([
                $this->configPath() => config_path('restql.php')
            ], 'restql-config');

            /// Register the RestQL commands.
            $this->commands([
                ResolverMakeCommand::class,
                AuthorizerMakeCommand::class,
                ClausuleMakeCommand::class,
                SchemaRestqlCommand::class
            ]);
        }
    }
}
