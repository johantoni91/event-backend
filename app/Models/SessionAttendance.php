<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionAttendance extends Model
{
    protected $table = 'session_attendance';
    protected $guarded = ['id'];

    public function events()
    {
        return $this->belongsTo(Event::class, 'events_id', 'id');
    }
}
