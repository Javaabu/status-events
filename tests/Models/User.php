<?php

namespace Javaabu\StatusEvents\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Javaabu\StatusEvents\Interfaces\TrackingSubject;

class User extends Model implements TrackingSubject
{
    protected $fillable = [
        'name',
        'email'
    ];

}
