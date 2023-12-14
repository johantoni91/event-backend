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

    function registration(Request $request, $event_id, $session_id)
    {
        $nip = $request->nip;
        $this->validate($request, [
            'nip'    => 'required'
        ]);
        $id = Participant::where('nip', $nip)->first();
        $arr = [];
        if ($id) {
            $absence = Attendance::where('participants_id', $id->id)->where('events_id', $event_id)->where('sessions_id', $session_id)->first();
            $events_id = Event::where('id', $event_id)->first();
            $sessions_id = Sessions::where('id', $session_id)->first();
            if (!$absence) {
                Attendance::insert([
                    'participants_id' => $id->id,
                    'sessions_id' => $sessions_id->id,
                    'events_id' => $events_id->id,
                ]);
                $arr = [
                    'code'  => 200,
                    'message' => "Berhasil absen pada session " . $absence->sessions_id
                ];
            } else {
                $arr = [
                    'code'  => 400,
                    'message' => "Peserta sudah melakukan absensi pada session " . $absence->sessions_id
                ];
            }
        } else {
            $arr = [
                "code"      => 400,
                "message"   => "NIP tidak valid"
            ];
        }
        return response()->json($arr, $arr['code']);
    }

    function findAbsence($event_id, $session_id)
    {
        $events_id = Attendance::where('events_id', $event_id)->where('sessions_id', $session_id);
        $participants = Participant::select('id', 'name', 'NIP', 'whatsapp', 'keterangan')->whereExists($events_id)->get();
        $get = [];
        $arr = [];
        if ($participants) {
            foreach ($participants as $part) {
                $get = $part;
            }
            $arr = [
                'code'              => 200,
                'message'           => "Get all sessions's attendances",
                'data'              => $get,
                'is_present'        => true
            ];
        } else {
            $arr = [
                'code'              => 200,
                'message'           => "Get all sessions's attendances",
                // 'data'              => $get
                'is_present'        => false
            ];
        }
        return response()->json($arr, $arr['code']);

        // 'id'            => $participants->id,
        //             'name'          => $participants->name,
        //             'nip'           => $participants->NIP,
        //             'whatsapp'      => $participants->whatsapp,
        //             'keterangan'    => $participants->keterangan,
        //             'is_present'    => false
    }

    function findSession($session_id)
    {
        $find = Sessions::select('id', 'name')->where('id', $session_id)->first();
        $arr = [];
        if ($find) {
            $arr = [
                'code'      => 200,
                'message'   => "Data session " . $find->id,
                'data'      => $find
            ];
        } else {
            $arr = [
                'code'      => 400,
                'message'   => "Data session not found"
            ];
        }
        return response()->json($arr, $arr['code']);
    }

    function postSession(Request $request)
    {
        $name = ['name' => $request->name];
        $check = Sessions::where('name', $request->name)->first();
        if (!$check) {
            Sessions::insert($name);
            return response()->json(['message' => 'Success insert session'], 200);
        } else {
            return response()->json(['message' => 'Failed insert session, session has been added.'], 300);
        }
    }

    function updateSession(Request $request)
    {
        $id = $request->id;
        $name = ['name' => $request->name];
        $update = Sessions::where('id', $id)->first();
        if ($update) {
            $update->update($name);
            return response()->json(['message' => 'Success update session'], 200);
        } else {
            return response()->json(['message' => 'Failed update session'], 300);
        }
    }

    function deleteSession(Request $request)
    {
        $id = $request->id;
        $del = Sessions::where('id', $id)->first();
        if ($del) {
            $del->delete();
            return response()->json(['message' => 'Success delete session'], 200);
        } else {
            return response()->json(['message' => 'Failed delete session'], 300);
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
