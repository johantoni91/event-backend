<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Attendance;
use App\Models\Participant;
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
        return Helpers::endPointSession($sessions);
    }

    function getEventSession()
    {
        $event_session = DB::table($this->table_event_session)->get();
        return Helpers::endPointSession($event_session);
    }

    function getAttendance()
    {
        $attendance = DB::table($this->table_attendance)->get();
        return Helpers::endPointSession($attendance);
    }

    function postSession(Request $request)
    {
        $data = [
            'name'      => $request->name,
        ];
        return Helpers::postSession($data, $this->table_session);
    }

    function postEventSession(Request $request)
    {
        $data = [
            'events_id'     => $request->events_id,
            'sessions_id'   => $request->sessions_id
        ];
        return Helpers::postSession($data, $this->table_event_session);
    }

    function postAttendance(Request $request)
    {
        $NIP = $request->NIP;
        $check = Participant::where('NIP', $NIP)->first();
        if ($check) {
            $data = [
                'participants_id'   => $request->participants_id,
                'events_id'         => $request->events_id,
                'sessions_id'       => $request->sessions_id
            ];
            $check_v2 = Attendance::where('participants_id', $data['participants_id'])->where('events_id', $data['events_id'])->where('sessions_id', $data['sessions_id'])->first();
            if ($check_v2) {
                return response()->json([
                    'message'   => 'Post Attendance failed, participant was absenced before'
                ], 400);
            } else {
                return Helpers::postSession($data, $this->table_attendance);
            }
        } else {
            return response()->json([
                'message'   => 'Post Attendance failed'
            ], 400);
        }
    }
}
