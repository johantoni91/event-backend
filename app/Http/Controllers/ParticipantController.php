<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Participant;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    function index()
    {
        $participant = Participant::select('id', 'name', 'whatsapp', 'NIP', 'keterangan')->orderBy('created_at', 'desc')->get();
        $arr = [];
        if ($participant) {
            $arr = [
                'data'      => $participant,
                'message'   => 'Success',
                'status'    =>  200,
            ];
        } else {
            $arr = [
                'message'   => 'Failed',
                'status'    =>  400,
            ];
        }
        return response()->json($arr, $arr['status']);
    }

    function find($participant_id)
    {
        $id = Participant::select('id', 'name', 'whatsapp', 'NIP', 'keterangan')->where('id', $participant_id)->first();
        $arr = [];
        if ($id) {
            $arr = [
                'code'  => 200,
                'message'   => 'Get participant by id success',
                'data'  => $id
            ];
        } else {
            $arr = [
                'code'  => 300,
                'message'   => 'Get participant by id failed',
                'data'  => $id
            ];
        }
        return response()->json($arr, $arr['code']);
    }

    function nip(Request $request)
    {
        $arr = [];
        $req = $request->getQueryString();
        if (!$req) {
            $arr = [
                'code'  => 301,
                'message'   => "Please insert param NIP"
            ];
        } else {
            $nip = explode('NIP=', $req);
            $cek = Participant::select('id', 'name', 'whatsapp', 'keterangan')->where('NIP', $nip['1'])->first();
            $arr = [];
            if ($cek) {
                $arr = [
                    'code'  => 200,
                    'message'   => 'Get participant by id success',
                    'data'  => $cek
                ];
            } else {
                $arr = [
                    'code'  => 404,
                    'message'   => 'NIP not found',
                    'data'  => $cek
                ];
            }
        }
        return response()->json($arr, $arr['code']);
    }

    function store(Request $request)
    {
        $data = [
            'name' => $request->name,
            'whatsapp' => $request->whatsapp,
            'NIP' => $request->NIP,
            'keterangan' => $request->keterangan,
        ];

        $this->validate($request, [
            'name' => 'required|regex:/^[\pL\s]+$/u|min:3|unique:App\Models\Participant,name',
            'NIP'   => 'required|unique:App\Models\Participant,NIP',
            'whatsapp' => 'required|unique:App\Models\participant,whatsapp',
        ]);

        return Helpers::insertParticipant($data);
    }

    function update(Request $request)
    {
        $data = [
            'id'         => $request->id,
            'name'       => $request->name,
            'whatsapp'   => $request->whatsapp,
            'NIP'        => $request->NIP,
            'keterangan' => $request->keterangan,
        ];

        $this->validate($request, [
            'id'        => 'required',
            // 'name'      => 'required|regex:/^[\pL\s]+$/u|min:3|unique:App\Models\Participant,name',
            // 'whatsapp'  => 'required|unique:App\Models\participant,whatsapp',
            // 'NIP'       => 'required|unique:App\Models\Participant,NIP',
        ]);

        return Helpers::updateParticipant($data);
    }

    function delete(Request $request)
    {
        $id = $request->id;

        $this->validate($request, [
            'id'    => 'required'
        ]);

        $user = Participant::find($id);
        return Helpers::endPointParticipantDelete($user);
    }
}
