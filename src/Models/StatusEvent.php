<?php

namespace Javaabu\StatusEvents\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Javaabu\Activitylog\Traits\LogsActivity;
use Javaabu\StatusEvents\Events\StatusEventCreatedEvent;
use Javaabu\StatusEvents\Interfaces\TrackingSubject;

class StatusEvent extends Model
{
    use LogsActivity;

    public static array $logAttributes = ['*'];

    protected $fillable = [
        'status',
        'remarks',
        'event_at',
    ];

    protected $casts = [
        'event_at' => 'datetime',
    ];

    protected $dispatchesEvents = [
        'created' => StatusEventCreatedEvent::class,
    ];

    public static function createFromInput(string $status, ?string $remarks = null, ?TrackingSubject $user = null): StatusEvent
    {
        $status_event = new StatusEvent();
        $status_event->status = $status;
        $status_event->remarks = $remarks;
        $status_event->event_at = now();

        if ($user) { // system events will not have user
            $status_event->user()->associate($user);
        }

        return $status_event;
    }

    public function trackable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): MorphTo
    {
        return $this->morphTo();
    }

    public function eventAt()
    {
        return Attribute::make(
            set: fn ($value) => isset($value) ? Carbon::parse($value) : Carbon::now()
        );
    }

    public function getStatusClass()
    {
        $trackable_class = Model::getActualClassNameForMorph($this->trackable_type);
        return $trackable_class::$status_class;
    }
}
