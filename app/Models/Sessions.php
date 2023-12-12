<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sessions extends Model
{
    protected $table = 'sessions';
    protected $guarded = ['id'];

    public function events_id()
    {
        return $this->belongsToMany('events_id', Event::class);
    }
}
