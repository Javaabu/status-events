<?php

namespace Javaabu\StatusEvents\Tests\Unit;

use Javaabu\StatusEvents\StatusEvents;
use Javaabu\StatusEvents\Tests\TestCase;

class StatusEventsTest extends TestCase
{
    public function setUp(): void
    {
        StatusEvents::$runsMigrations = true;
        parent::setUp();
    }

    /** @test */
    public function it_has_default_runs_migrations_value(): void
    {
        $this->assertTrue(StatusEvents::$runsMigrations);
    }

    /** @test */
    public function it_can_ignore_migrations(): void
    {
        $instance = StatusEvents::ignoreMigrations();

        $this->assertFalse(StatusEvents::$runsMigrations);
        $this->assertInstanceOf(StatusEvents::class, $instance);
    }
}