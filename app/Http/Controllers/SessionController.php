<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SessionController extends Controller
{
    private $table_session = 'sessions';
    private $table_event_session = 'event_sessions';
    private $table_attendance = 'attendances';

    function getSession()
    {
        $sessions = DB::table($this->table_session)->get();
        if ($sessions) {
            return response()->json($sessions, 200);
        } else {
            return response()->json('Failed get sessions table', 400);
        }
    }

    function getEventSession()
    {
        $event_session = DB::table($this->table_event_session)->get();
        if ($event_session) {
            return response()->json($event_session, 200);
        } else {
            return response()->json('Failed get event sessions table', 400);
        }
    }

    function getAttendance()
    {
        $attendance = DB::table($this->table_attendance)->get();
        if ($attendance) {
            return response()->json($attendance, 200);
        } else {
            return response()->json('Failed get attendances table', 400);
        }
    }
}
