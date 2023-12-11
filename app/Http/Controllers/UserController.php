<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    function index()
    {
        $user = User::orderBy('name')->get();
        return response()->json([
            'data'      => $user,
            'message'   => 'Success get all users',
            'status'    => true
        ], 200);
    }

    function register(Request $request)
    {
        $data = [
            'name'  => $request->name,
            'email'  => $request->email,
            'password'  => $request->password
        ];
        $this->validate($request, [
            'name'  => 'required|unique:App\Models\User,name|unique:App\Models\User,name',
            'email'  => 'required|email:rfc,dns|unique:App\Models\User,email',
            'password'  => 'required'
        ]);

        $registration = User::insert($data);
        return Helpers::endPointUser($registration);
    }

    function login(Request $request)
    {
        $data = [
            'email'  => $request->email,
            'password'  => $request->password
        ];
        $this->validate($request, [
            'email'  => 'required|email:rfc,dns',
            'password'  => 'required'
        ]);

        $login = User::where('email', $data['email'])->where('password', $data['password'])->first();
        return Helpers::endPointUser($login);
    }

    function update(Request $request)
    {
        $data = [
            'id'        => $request->id,
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => $request->password
        ];

        $this->validate($request, [
            'id'        => 'required',
            'name'      => 'required',
            'email'     => 'required|email:rfc,dns',
            'password'  => 'required'
        ]);

        $check = User::where('id', $data['id'])->first();
        return Helpers::endPointUserUpdate($check, $data);
    }

    function delete(Request $request)
    {
        $id = $request->id;
        $user = User::where('id', $id)->first();
        return Helpers::endPointUserDelete($user);
    }

    function logout(Request $request)
    {
        $id = $request->id;
        $user = User::where('id', $id)->first();
        return Helpers::endPointUserLogout($user);
    }
}
