<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
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
            'absen'     => $request->absen,
            'makan'     => $request->makan,
            'souvenir'  => $request->souvenir,
            'checkout'  => $request->checkout,
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
        $data = [
            'participants_id'   => $request->participants_id,
            'events_id'         => $request->events_id,
            'sessions_id'       => $request->sessions_id
        ];
        return Helpers::postSession($data, $this->table_attendance);
    }
}
