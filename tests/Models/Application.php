<?php

namespace Javaabu\StatusEvents\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Javaabu\StatusEvents\Interfaces\Trackable;
use Javaabu\StatusEvents\Tests\Enums\ApplicationStatuses;
use Javaabu\StatusEvents\Traits\HasStatusEvents;

class Application extends Model implements Trackable
{
    use HasStatusEvents;

    protected $fillable = [
        'name',
        'description',
        'status'
    ];

    protected $casts = [
        'status' => ApplicationStatuses::class
    ];

    public function getStatusColors(): array
    {
        return ApplicationStatuses::colors();
    }

    public function getStatusLabels(): array
    {
        return ApplicationStatuses::labels();
    }
}
