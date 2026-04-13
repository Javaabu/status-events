<?php

namespace Javaabu\StatusEvents\Tests\Unit;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Javaabu\StatusEvents\StatusEvents;
use Javaabu\StatusEvents\Tests\TestCase;

class StatusEventsServiceProviderTest extends TestCase
{
    /** @test */
    public function it_publishes_migrations(): void
    {
        $migrationPath = database_path('migrations');

        File::deleteDirectory($migrationPath);

        $this->artisan('vendor:publish', [
            '--tag' => 'status-events-migrations',
        ])->assertExitCode(0);

        $this->assertFileExists($migrationPath . '/create_status_events_table.php');

        File::deleteDirectory($migrationPath);
    }

    /** @test */
    public function it_publishes_config(): void
    {
        $configPath = $this->app->basePath('config/status-events.php');

        File::delete($configPath);

        $this->artisan('vendor:publish', [
            '--tag' => 'status-events-config',
        ])->assertExitCode(0);

        $this->assertFileExists($configPath);

        File::delete($configPath);
    }

    /** @test */
    public function it_registers_migrations_by_default(): void
    {
        $this->assertTrue(StatusEvents::$runsMigrations);
    }

    /** @test */
    public function it_can_ignore_migrations(): void
    {
        StatusEvents::ignoreMigrations();

        $this->assertFalse(StatusEvents::$runsMigrations);
    }

    /** @test */
    public function it_merges_config(): void
    {
        $this->assertIsArray(config('status-events'));
    }
}