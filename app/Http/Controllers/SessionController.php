<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Attendance;
use App\Models\Event;
use App\Models\EventSession;
use App\Models\Participant;
use App\Models\Sessions;
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
        if ($data) {
            Sessions::insert($data);
            return response()->json(['message' => 'Success insert session'], 200);
        } else {
            return response()->json(['message' => 'Failed insert session'], 300);
        }
    }

    function postEventSession(Request $request)
    {
        $data = [
            'events_id'     => $request->events_id,
            'sessions_id'   => $request->sessions_id
        ];
        if ($data) {
            $events_id = Event::where('id', $data['events_id'])->first();
            $sessions_id = Sessions::where('id', $data['sessions_id'])->first();
            if ($events_id == null || $sessions_id == null) {
                return response()->json([
                    'message'   => 'Event or Session is not found.'
                ], 301);
            } else {
                $arr = [
                    'events_id' => $events_id->id,
                    'sessions_id' => $sessions_id->id
                ];
                EventSession::insert($arr);
                return response()->json(['message' => 'Success insert event session'], 200);
            }
        } else {
            return response()->json(['message' => 'Failed insert event session'], 304);
        }
    }

    function postAttendance(Request $request)
    {
        $NIP = $request->NIP;
        $check = Participant::where('NIP', $NIP)->first();
        if ($check) {
            $data = [
                'events_id'         => $request->events_id,
                'sessions_id'       => $request->sessions_id
            ];
            $check_v2 = Attendance::where('participants_id', $check->id)->where('events_id', $data['events_id'])->where('sessions_id', $data['sessions_id'])->first();

            if ($check_v2) {
                return response()->json([
                    'message'   => 'Post Attendance failed, participant was absenced before'
                ], 300);
            } else {
                $events_id = Event::where('id', $data['events_id'])->first();
                $sessions_id = Sessions::where('id', $data['sessions_id'])->first();
                if ($events_id && $sessions_id == null) {
                    return response()->json([
                        'message'   => 'Session is null.'
                    ], 301);
                } elseif ($events_id == null && $sessions_id) {
                    return response()->json([
                        'message'   => 'Event is null.'
                    ], 302);
                } else {
                    $save = Attendance::create([
                        'participants_id'   => $check->id,
                        'events_id'         => $events_id->id,
                        'sessions_id'       => $sessions_id->id,
                    ]);
                    if ($save) {
                        return response()->json(['message' => 'Success insert Attendance'], 200);
                    } else {
                        return response()->json(['message' => 'Failed insert Attendance'], 303);
                    }
                }
            }
        } else {
            return response()->json([
                'message'   => 'Post Attendance failed'
            ], 400);
        }
    }
}
