<?php

namespace Javaabu\StatusEvents\Interfaces;

use Illuminate\Support\Carbon;
use Javaabu\StatusEvents\Models\StatusEvent;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Trackable
{
    //    public function tracking(): MorphMany;
    //
    //    public function addStatusEvent(
    //        string $status,
    //        string $remarks = null,
    //        ?Carbon $event_at = null,
    //        ?TrackingSubject $user = null
    //    ): StatusEvent;

    public function getStatusColors(): array;
    public function getStatusLabels(): array;
}
