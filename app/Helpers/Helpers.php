<?php

namespace App\Helpers;

use App\Models\Event;
use App\Models\Participant;

class Helpers
{
    //USERS
    public static function endPointUser($data)
    {
        $res = [];
        if ($data) {
            $res = [
                'message'   => 'Success registration',
                'status'    => 200
            ];
        } else {
            $res = [
                'message'   => 'Failed registration',
                'status'    => 400
            ];
        }
        return response()->json($res, $res['status']);
    }

    public static function endPointUserUpdate($check, $data)
    {
        $res = [];
        if ($check) {
            $check->update($data);
            $res = [
                'message'   => 'Success update data ' . $data['name'],
                'status'    => 200
            ];
        } else {
            $res = [
                'message'   => 'Failed update data user',
                'status'    => 400
            ];
        }
        return response()->json($res, $res['status']);
    }

    public static function endPointUserDelete($data)
    {
        $res = [];
        if ($data) {
            $data->delete();
            $res = [
                'message'   => 'Success delete user',
                'status'    => 200
            ];
        } else {
            $res = [
                'message'   => 'Failed delete user',
                'status'    => 400
            ];
        }
        return response()->json($res, $res['status']);
    }

    public static function endPointUserLogout($data)
    {
        $res = [];
        if ($data) {
            $res = [
                'message'   => 'Logout user',
                'data'      => $data,
                'status'    => 200
            ];
        } else {
            $res = [
                'message'   => 'Failed logout user',
                'status'    => 400
            ];
        }
        return response()->json($res, $res['status']);
    }

    //PARTICIPANT
    public static function endPointParticipant($action, $data, $name)
    {
        $res = [];
        if ($data) {
            $res = [
                'message'   => 'Success ' . $action . $name,
                'status'    => 200
            ];
        } else {
            $res = [
                'message'   => 'Failed ' . $action,
                'status'    => 400
            ];
        }
        return response()->json($res, $res['status']);
    }

    public static function endPointParticipantDelete($data)
    {
        $res = [];
        if ($data) {
            $data->delete();
            $res = [
                'message'   => 'Success delete participant',
                'status'    => 200
            ];
        } else {
            $res = [
                'message'   => 'Failed delete participant',
                'status'    => 400
            ];
        }
        return response()->json($res, $res['status']);
    }

    public static function insertParticipant($data)
    {
        $participant = Participant::insert($data);
        return Helpers::endPointParticipant('Create participant ', $participant, '');
    }

    public static function updateParticipant($data)
    {
        $participant = Participant::where('id', $data['id'])->first();
        if ($participant) {
            $participant->update($data);
        }
        return Helpers::endPointParticipant('Update participant ', $participant, $participant['name']);
    }

    //EVENT
    public static function endPointEvent($data, $action)
    {
        $res = [];
        if ($data) {
            $res = [
                'message'   => 'Success ' . $action,
                'status'    => 200
            ];
        } else {
            $res = [
                'message'   => 'Failed ' . $action,
                'status'    => 400
            ];
        }
        return response()->json($res, $res['status']);
    }

    public static function EventHandler($data)
    {
        $content = Event::insert($data)->hasAttached(
            Participant::where('id', $data['id_peserta']),
            ['active' => true]
        );
        return Helpers::endPointEvent('Created event ', $content);
    }

    public static function EventHandlerUpdate($data)
    {
        $event = Event::find($data['id']);
        if ($event) {
            $event->update($data)->hasAttached(
                Participant::where('id', $data['id_peserta']),
                ['active' => true]
            );
        }
        return Helpers::endPointEvent('Update event ', $event);
    }
}
