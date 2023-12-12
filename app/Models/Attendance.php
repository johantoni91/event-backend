<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendances';
    protected $fillable = [
        'participants_id', 'events_id', 'sessions_id'
    ];

    public $timestamps = false;
}
