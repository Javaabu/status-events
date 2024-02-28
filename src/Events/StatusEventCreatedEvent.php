<?php

namespace Javaabu\StatusEvents\Events;

use Javaabu\StatusEvents\Models\StatusEvent;
use Illuminate\Foundation\Events\Dispatchable;

class StatusEventCreatedEvent
{
    use Dispatchable;

    public function __construct(public StatusEvent $statusEvent)
    {
    }
}
