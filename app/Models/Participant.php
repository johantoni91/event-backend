<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $table = 'participants';
    protected $fillable = [
        'name', 'whatsapp', 'keterangan', 'NIP'
    ];

    public function sessionAttendances()
    {
        return $this->belongsToMany(
            Sessions::class,
            'session_attendances',
            'participants_id',
            'events_id'
        );
    }

    public function attendances()
    {
        return $this->belongsToMany(
            Sessions::class,
            'attendances',
            'participants_id',
            'events_id',
            'sessions_id'
        );
    }
}
