<?php

namespace Javaabu\StatusEvents\Tests\Unit;

use Javaabu\StatusEvents\Models\StatusEvent;
use Javaabu\StatusEvents\Tests\Enums\ApplicationStatuses;
use Javaabu\StatusEvents\Tests\InteractsWithDatabase;
use Javaabu\StatusEvents\Tests\Models\Application;
use Javaabu\StatusEvents\Tests\Models\User;
use Javaabu\StatusEvents\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class StatusEventTest extends TestCase
{
    use InteractsWithDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->runMigrations();
    }

    #[Test]
    public function it_can_create_status_event_from_input(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $statusEvent = StatusEvent::createFromInput(
            ApplicationStatuses::Processing->value,
            'Processing remarks',
            $user,
        );

        $this->assertEquals('processing', $statusEvent->status);
        $this->assertEquals('Processing remarks', $statusEvent->remarks);
        $this->assertNotNull($statusEvent->event_at);
        $this->assertEquals($user->id, $statusEvent->user_id);
        $this->assertEquals(User::class, $statusEvent->user_type);
    }

    /** @test */
    public function it_can_create_status_event_from_input_without_user(): void
    {
        $statusEvent = StatusEvent::createFromInput(
            ApplicationStatuses::Draft->value,
            'Draft remarks',
        );

        $this->assertEquals('draft', $statusEvent->status);
        $this->assertEquals('Draft remarks', $statusEvent->remarks);
        $this->assertNotNull($statusEvent->event_at);
        $this->assertNull($statusEvent->user_id);
        $this->assertNull($statusEvent->user_type);
    }

    #[Test]
    public function it_has_trackable_relationship(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $application = Application::create([
            'name' => 'Test Application',
            'description' => 'A test application',
            'status' => ApplicationStatuses::Draft,
        ]);

        $statusEvent = $application->createStatusEvent(
            ApplicationStatuses::Processing->value,
            'Processing',
            $user,
        );

        $this->assertInstanceOf(Application::class, $statusEvent->trackable);
        $this->assertEquals($application->id, $statusEvent->trackable->id);
    }

    #[Test]
    public function it_has_user_relationship(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $application = Application::create([
            'name' => 'Test Application',
            'description' => 'A test application',
            'status' => ApplicationStatuses::Draft,
        ]);

        $statusEvent = $application->createStatusEvent(
            ApplicationStatuses::Processing->value,
            'Processing',
            $user,
        );

        $this->assertInstanceOf(User::class, $statusEvent->user);
        $this->assertEquals($user->id, $statusEvent->user->id);
    }

    #[Test]
    public function it_can_get_status_class(): void
    {
        $application = Application::create([
            'name' => 'Test Application',
            'description' => 'A test application',
            'status' => ApplicationStatuses::Draft,
        ]);

        $statusEvent = $application->createStatusEvent(
            ApplicationStatuses::Processing->value,
            'Processing',
        );

        $this->assertEquals(ApplicationStatuses::class, $statusEvent->getStatusClass());
    }

    #[Test]
    public function it_casts_event_at_to_datetime(): void
    {
        $application = Application::create([
            'name' => 'Test Application',
            'description' => 'A test application',
            'status' => ApplicationStatuses::Draft,
        ]);

        $statusEvent = $application->createStatusEvent(
            ApplicationStatuses::Processing->value,
            'Processing',
        );

        $this->assertInstanceOf(\Carbon\Carbon::class, $statusEvent->event_at);
    }

    #[Test]
    public function it_can_be_created_with_custom_event_at(): void
    {
        $customDate = now()->subDays(1);

        $application = Application::create([
            'name' => 'Test Application',
            'description' => 'A test application',
            'status' => ApplicationStatuses::Draft,
        ]);

        $statusEvent = $application->createStatusEvent(
            ApplicationStatuses::Draft->value,
            'Test',
        );

        $statusEvent->event_at = $customDate;
        $statusEvent->save();

        $this->assertEquals($customDate->toDateTimeString(), $statusEvent->fresh()->event_at->toDateTimeString());
    }
}