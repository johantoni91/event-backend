<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    function getEvent()
    {
        $event = Event::all();
        if ($event) {
            return response()->json($event, 200);
        } else {
            return response()->json('Failed get event table', 400);
        }
    }

    function store(Request $request)
    {
        $data = [
            'participants_id'   => $request->participants_id,
            'event'             => $request->event,
            'location'          => $request->location,
            'start'             => $request->start,
            'end'               => $request->end
        ];

        $this->validate($request, [
            'participants_id'   => 'required',
            'event'             => 'required',
            'location'          => 'required',
            'start'             => 'required|date',
            'end'               => 'required|date'
        ]);

        return Helpers::EventHandler($data);
    }

    function update(Request $request)
    {
        $data = [
            'participants_id'   => $request->participants_id,
            'event'             => $request->event,
            'location'          => $request->location,
            'start'             => $request->start,
            'end'               => $request->end
        ];

        $this->validate($request, [
            'participants_id'   => 'required',
            'event'             => 'required',
            'location'          => 'required',
            'start'             => 'required|date',
            'end'               => 'required|date'
        ]);

        return Helpers::EventHandlerUpdate($data);
    }

    function delete(Request $request)
    {
        $id = $request->id;
        $find = Event::where('id', $id)->first();
        $res = [];

        if ($find) {
            $find->delete();
            $res = [
                'message'   => 'Success delete event' . $find->event,
                'status'    => 200
            ];
        } else {
            $res = [
                'message'   => 'Failed delete event',
                'status'    => 400
            ];
        }
        return response()->json($res, $res['status']);
    }
}
