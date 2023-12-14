<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionAttendance extends Model
{
    protected $table = 'session_attendances';
    protected $fillable = [
        'participants_id', 'events_id'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'events_id');
    }
}
