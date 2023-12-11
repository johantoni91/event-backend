<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Participant;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    function index()
    {
        $participant = Participant::all();
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

    function store(Request $request)
    {
        $data = [
            'name' => $request->name,
            'whatsapp' => $request->whatsapp,
            'NIP' => $request->NIP,
            'keterangan' => $request->keterangan,
        ];

        $this->validate($request, [
            'name' => 'required|unique:App\Models\Participant,name',
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
            // 'name'      => 'required|unique:App\Models\Participant,name',
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
