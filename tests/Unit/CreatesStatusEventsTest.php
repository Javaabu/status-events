<?php

namespace Javaabu\StatusEvents\Tests\Unit;

use Illuminate\Support\Facades\Event;
use Javaabu\StatusEvents\Events\StatusEventCreatedEvent;
use Javaabu\StatusEvents\Models\StatusEvent;
use Javaabu\StatusEvents\Tests\Enums\ApplicationStatuses;
use Javaabu\StatusEvents\Tests\InteractsWithDatabase;
use Javaabu\StatusEvents\Tests\Models\Application;
use Javaabu\StatusEvents\Tests\Models\User;
use Javaabu\StatusEvents\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CreatesStatusEventsTest extends TestCase
{
    use InteractsWithDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->runMigrations();
    }

    #[Test]
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

    #[Test]
    public function it_creates_status_event_without_user(): void
    {
        $application = Application::create([
            'name' => 'Test Application',
            'description' => 'A test application',
            'status' => ApplicationStatuses::Draft,
        ]);

        $statusEvent = $application->createStatusEvent(
            ApplicationStatuses::Processing->value,
            'System processing',
        );

        $this->assertInstanceOf(StatusEvent::class, $statusEvent);
        $this->assertDatabaseHas('status_events', [
            'trackable_id' => $application->id,
            'trackable_type' => Application::class,
            'status' => ApplicationStatuses::Processing,
            'remarks' => 'System processing',
            'user_id' => null,
            'user_type' => null,
        ]);
    }

    #[Test]
    public function it_dispatches_status_event_created_event(): void
    {
        Event::fake();

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $application = Application::create([
            'name' => 'Test Application',
            'description' => 'A test application',
            'status' => ApplicationStatuses::Draft,
        ]);

        $application->createStatusEvent(
            ApplicationStatuses::Processing->value,
            'Processing',
            $user,
        );

        Event::assertDispatched(StatusEventCreatedEvent::class, function ($event) use ($application) {
            return $event->statusEvent->trackable_id === $application->id;
        });
    }

    #[Test]
    public function it_can_retrieve_status_events(): void
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

        $application->createStatusEvent(
            ApplicationStatuses::Processing->value,
            'First status',
            $user,
        );

        $application->createStatusEvent(
            ApplicationStatuses::Complete->value,
            'Second status',
            $user,
        );

        $this->assertCount(2, $application->statusEvents);
        $this->assertEquals('complete', $application->statusEvents->last()->status);
    }

    #[Test]
    public function it_deletes_status_events_when_model_is_deleted(): void
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

        $application->createStatusEvent(
            ApplicationStatuses::Processing->value,
            'Processing',
            $user,
        );

        $statusEventId = $application->statusEvents->first()->id;

        $application->delete();

        $this->assertDatabaseMissing('status_events', [
            'id' => $statusEventId,
        ]);
    }

    #[Test]
    public function it_can_get_latest_remarks(): void
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

        $application->createStatusEvent(
            ApplicationStatuses::Processing->value,
            'First remarks',
            $user,
        );

        sleep(1);

        $application->createStatusEvent(
            ApplicationStatuses::Complete->value,
            'Latest remarks',
            $user,
        );

        $application->refresh();

        $this->assertEquals('Latest remarks', $application->latestRemarks);
    }

    #[Test]
    public function it_returns_null_for_latest_remarks_when_no_events_exist(): void
    {
        $application = Application::create([
            'name' => 'Test Application',
            'description' => 'A test application',
            'status' => ApplicationStatuses::Draft,
        ]);

        $this->assertNull($application->latestRemarks);
    }
}
