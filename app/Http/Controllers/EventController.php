<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Event;
use App\Models\EventSession;
use App\Models\Participant;
use App\Models\SessionAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    function getEvent()
    {
        $event = Event::select('id', 'event', 'location', 'start', 'end')->orderBy('created_at', 'desc')->with('sessions')->get();
        if ($event) {
            return response()->json($event, 200);
        } else {
            return response()->json('Event null', 400);
        }
    }

    function find($event_id)
    {
        $id = Event::select('id', 'event', 'location', 'start', 'end')->with('sessions')->where('id', $event_id)->first();
        $arr = [];
        if ($id) {
            $arr = [
                'code'  => 200,
                'message'   => 'Get event by id success',
                'data'  => $id
            ];
        } else {
            $arr = [
                'code'  => 300,
                'message'   => 'Get event by id failed',
                'data'  => $id
            ];
        }
        return response()->json($arr, $arr['code']);
    }

    function findAbsence($event_id)
    {
        $event = Event::find($event_id);

        if (!$event) {
            return response()->json([
                'code' => 404,
                'message' => "Event Nor Found",
            ]);
        }

        return response()->json([
            'code' => 200,
            'message' => "Get all event's attendances",
            'data' => $event->participants,
        ]);
    }

    function store(Request $request)
    {
        $data = [
            'event'     => $request->event,
            'location'  => $request->location,
            'start'     => $request->start,
            'end'       => $request->end,
        ];

        $this->validate($request, [
            'event'    => 'required',
            'location' => 'required',
            'start'    => 'required',
            'end'      => 'required',
        ]);

        return Helpers::EventHandler($data);
    }

    function registration(Request $request, $event_id)
    {
        $nip = $request->nip;
        $check_nip = Participant::where('NIP', $nip)->first();
        $check_events = SessionAttendance::where('participants_id', $nip)->first();
        $event = Event::where('id', $event_id)->first();
        $arr = [];
        if (!$check_nip) {
            $arr = [
                "code"      => 400,
                "message"   => "NIP tidak valid"
            ];
        } else {
            if ($check_events) {
                $arr = [
                    "code"      => 400,
                    "message"   => "Peserta sudah melakukan registrasi pada event " . $check_events->events->event
                ];
            } else {
                SessionAttendance::insert([
                    'participants_id' => $check_nip->id,
                    'events_id'     => $event->id
                ]);
                $arr = [
                    "code"      => 200,
                    "message"   => "Berhasil registrasi event " . $event->event
                ];
            }
        }
        return response()->json($arr, $arr['code']);
    }

    function update(Request $request)
    {
        $data = [
            'id'        => $request->id,
            'event'     => $request->event,
            'location'  => $request->location,
            'start'     => $request->start,
            'end'       => $request->end,
        ];

        $this->validate($request, [
            'id'        => 'required',
            'event'     => 'required',
            'location'  => 'required',
        ]);

        return Helpers::EventHandlerUpdate($data);
    }

    function delete(Request $request)
    {
        $id = $request->id;
        $findEvent = Event::with('sessions')->where('id', $id)->first();
        $res = [];
        if ($findEvent) {
            $findSession = EventSession::where('events_id', $id)->get();
            $dataSesi = [];
            foreach ($findSession as $sesi) {
                $dataSesi[] = $sesi->id;
            }
            if ($dataSesi) {
                $findEvent->delete();
                $res = [
                    'message'   => 'Success delete event',
                    'status'    => 200
                ];
            } else {
                $findEvent->delete();
                $res = [
                    'message'   => 'Success delete event without event sessions',
                    'status'    => 201
                ];
            }
        } else {
            $res = [
                'message'   => 'Failed delete event-session',
                'status'    => 300
            ];
        }
        return response()->json($res, $res['status']);
    }
}
