<?php

namespace Javaabu\StatusEvents;

use Illuminate\Support\ServiceProvider;

class StatusEventsServiceProvider extends ServiceProvider
{
    /**
     * Boostrap the application services.
     */
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->registerMigrations();

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'status-events-migrations');

        $this->publishes([
            __DIR__ . '/../config/permission.php' => config_path('permission.php'),
        ], 'status-events-config');
    }

    public function register(): void
    {
        // Enable only if you want to include a helpers file.
        // require_once __DIR__ . '/helpers.php';

        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'status-events');
    }

        /**
     * Register migration files.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        if (StatusEvents::$runsMigrations) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
    }
}
