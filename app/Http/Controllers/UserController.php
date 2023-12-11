<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

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
            'password'  => Hash::make($request->password)
        ];
        $this->validate($request, [
            'name'  => 'required|unique:App\Models\User,name|unique:App\Models\User,name',
            'email'  => 'required|email:rfc,dns|unique:App\Models\User,email',
            'password'  => 'required'
        ]);

        $registration = User::insert($data);
        return Helpers::endPointRegistrationUser($registration, 'registration');
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

        $login = User::where('email', $data['email'])->first();
        if (Hash::check($data['password'], $login->password) && $login->email == $data['email']) {
            $token = Str::random(80);
            $login->update([
                'remember_token' => $token
            ]);
            $login->save();
            return response()->json([
                'message'   => 'Success login',
                'token'     => $token,
                'status'    => 200
            ], 200);
        } else {
            return response()->json([
                'message'   => 'Failed login',
                'status'    => 400
            ]);
        }
    }

    function update(Request $request)
    {
        $data = [
            'id'        => $request->id,
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password)
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
        $token = $request->bearerToken();
        $user = User::where('remember_token', $token)->first();
        if ($user) {
            $user->update([
                'remember_token'    => ''
            ]);
            return response()->json([
                'message'   => 'Success logout',
                'status'    => 200
            ], 200);
        } else {
            return response()->json([
                'message'   => 'Failed logout',
                'status'    => 401
            ], 401);
        }
    }
}
