<?php

namespace Javaabu\StatusEvents\Tests\Unit;

use Javaabu\StatusEvents\Tests\InteractsWithDatabase;
use Javaabu\StatusEvents\Tests\TestCase;
use Schema;

class MigrationsTest extends TestCase
{
    use InteractsWithDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->runMigrations();
    }

    /** @test */
    public function it_migrates_the_table_during_tests(): void
    {
        $this->assertTrue(Schema::hasTable('status_events'));
    }
}
