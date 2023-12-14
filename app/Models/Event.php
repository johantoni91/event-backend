<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Event extends Pivot
{
    protected $table = 'events';
    protected $guarded = ['id'];

    public function sessions()
    {
        return $this->belongsToMany(Sessions::class, 'event_sessions', 'events_id', 'sessions_id');
    }

    public function participants()
    {
        return $this->belongsToMany(Participant::class, 'session_attendances', 'events_id', 'participants_id');
    }
}
