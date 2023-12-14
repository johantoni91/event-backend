<?php

namespace App\Helpers;

use App\Models\Event;
use App\Models\Participant;

class Helpers
{

    public static function endPointRegistrationUser($data, $content)
    {
        $res = [];
        if ($data) {
            $res = [
                'message'   => 'Success ' . $content,
                'status'    => 200
            ];
        } else {
            $res = [
                'message'   => 'Failed ' . $content,
                'status'    => 400
            ];
        }
        return response()->json($res, $res['status']);
    }

    public static function endPointUserUpdate($check, $data)
    {
        $res = [];
        if ($check) {
            if ($data['password'] == null) {
                $check->update([
                    'name'  => $data['name'],
                    'email' => $data['email']
                ]);
                $res = [
                    'message'   => 'Success update data ' . $data['name'],
                    'status'    => 200
                ];
            } else {
                $check->update($data);
                $check->save();
                $res = [
                    'message'   => 'Success update data ' . $data['name'],
                    'status'    => 200
                ];
            }
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
        if ($data['name'] == null) {
            $participant->update([
                'whatsapp'  => $data['whatsapp'],
                'NIP'  => $data['NIP'],
                'keterangan'  => $data['keterangan'],
            ]);
        } elseif ($data['whatsapp'] == null) {
            $participant->update([
                'name'  => $data['name'],
                'NIP'  => $data['NIP'],
                'keterangan'  => $data['keterangan'],
            ]);
        } elseif ($data['NIP'] == null) {
            $participant->update([
                'whatsapp'  => $data['whatsapp'],
                'name'  => $data['name'],
                'keterangan'  => $data['keterangan'],
            ]);
        } elseif ($data['keterangan'] == null) {
            $participant->update([
                'whatsapp'  => $data['whatsapp'],
                'NIP'  => $data['NIP'],
                'name'  => $data['name'],
            ]);
        } else {
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
                'code'      => 200,
                'message'   => 'Success ' . $action,
                'data'      => $data
            ];
        } else {
            $res = [
                'message'   => 'Failed ' . $action,
                'code'    => 400
            ];
        }
        return response()->json($res, $res['code']);
    }

    public static function EventHandler($data)
    {
        $content = Event::insert($data);
        $res = [];
        if ($content) {
            $res = [
                'code'      => 200,
                'message'   => 'Success create event',
                'data'      => Event::select('id', 'event', 'location', 'start', 'end')->where('event', $data['event'])->where('location', $data['location'])->get()
            ];
        } else {
            $res = [
                'message'   => 'Failed create event',
                'code'    => 400
            ];
        }
        return response()->json($res, $res['code']);
    }

    public static function EventHandlerUpdate($data)
    {
        $event = Event::find($data['id']);
        if ($event) {
            $event->update($data);
        }
        return Helpers::endPointEvent($event, 'Update event');
    }

    public static function endPointSession($data)
    {
        if ($data) {
            return response()->json($data, 200);
        } else {
            return response()->json('Failed get table', 400);
        }
    }
}
