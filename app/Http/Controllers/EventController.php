<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Event;
use App\Models\EventSession;
use App\Models\Sessions;
use Illuminate\Http\Request;

class EventController extends Controller
{
    function getEvent()
    {
        $event = Event::with('sessions')->get();
        if ($event) {
            return response()->json($event, 200);
        } else {
            return response()->json('Event null', 400);
        }
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
        $findEvent = Event::where('id', $id)->first();
        $findSession = EventSession::where('events_id', $id)->get();
        $res = [];

        if ($findEvent) {
            $findSession->delete();
            $res = [
                'message'   => 'Success delete event-session',
                'status'    => 200
            ];
        } else {
            $res = [
                'message'   => 'Failed delete event-session',
                'status'    => 400
            ];
        }
        return response()->json($res, $res['status']);
    }
}
