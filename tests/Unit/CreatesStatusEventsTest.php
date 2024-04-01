<?php

namespace Javaabu\StatusEvents\Tests\Unit;

use Javaabu\StatusEvents\Tests\Enums\ApplicationStatuses;
use Javaabu\StatusEvents\Tests\InteractsWithDatabase;
use Javaabu\StatusEvents\Tests\Models\Application;
use Javaabu\StatusEvents\Tests\Models\User;
use Javaabu\StatusEvents\Tests\TestCase;

class CreatesStatusEventsTest extends TestCase
{
    use InteractsWithDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->runMigrations();
    }

    /** @test */
    public function it_can_create_a_simple_status_event(): void
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'example@example.com',
        ]);

        /** @var Application $application */
        $application = Application::create([
            'name' => 'John Doe',
            'description' => 'This is a test application',
            'status' => ApplicationStatuses::Draft,
        ]);

        $this->assertDatabaseHas('applications', [
            'name' => 'John Doe',
            'description' => 'This is a test application',
            'status' => ApplicationStatuses::Draft,
        ]);

        $this->assertDatabaseMissing('status_events', [
            'trackable_id' => $application->id,
            'trackable_type' => Application::class,
            'status' => ApplicationStatuses::Draft,
        ]);

        $application->status = ApplicationStatuses::Processing;
        $application->save();

        $application->createStatusEvent(
            ApplicationStatuses::Processing->value,
            'Processing the application',
            $user,
        );

        $this->assertDatabaseHas('status_events', [
            'trackable_id' => $application->id,
            'trackable_type' => Application::class,
            'status' => ApplicationStatuses::Processing,
            'remarks' => 'Processing the application',
        ]);
    }
}
