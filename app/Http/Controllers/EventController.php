<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Event;
use App\Models\Participant;
use Illuminate\Http\Request;

class EventController extends Controller
{
    function getEvent(Request $request)
    {
        $id = $request->id;
        $event = Event::where('id', $id)->first();
        return Helpers::endPointEvent($event, 'found ' . $event['event']);
    }

    function store(Request $request)
    {
        $data = [
            'id_peserta'    => $request->id_peserta,
            'event'         => $request->event,
            'location'      => $request->location,
            'start'         => $request->start,
            'end'           => $request->end
        ];

        $this->validate($request, [
            'id_peserta'    => 'required',
            'event'         => 'required',
            'location'      => 'required',
            'start'         => 'required|date',
            'end'           => 'required|date'
        ]);

        return Helpers::EventHandler($data);
    }

    function update(Request $request)
    {
        $data = [
            'id_peserta'    => $request->id_peserta,
            'event'         => $request->event,
            'location'      => $request->location,
            'start'         => $request->start,
            'end'           => $request->end
        ];

        $this->validate($request, [
            'id_peserta'    => 'required',
            'event'         => 'required',
            'location'      => 'required',
            'start'         => 'required|date',
            'end'           => 'required|date'
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
