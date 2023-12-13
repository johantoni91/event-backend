<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $table = 'participants';
    protected $fillable = [
        'name', 'whatsapp', 'keterangan', 'NIP'
    ];

    // public function events()
    // {
    //     return $this->belongsToMany(Attendance::class, 'NIP');
    // }
}
