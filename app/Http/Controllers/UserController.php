<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function create(CreateUserRequest $request)
    {
        $user = new User();
        $user->email = $request->input('email');
        $user->name = $request->input('name');
        $user->password = $request->input('password');

        if (!$user->save()) {
            return response()->json(['error' => 'Something went wrong']);
        }

        return response()->json($user);
    }

    public function login(LoginUserRequest $request)
    {
        $loginData = $request->only('email', 'password');

        if (!Auth::attempt($loginData)) {
            return response()->json(['error' => 'Error you need to login'], 401);
        }

        $token = Auth::user()->createToken('passport-token')->accessToken;

        return response()->json(['token' => $token]);
    }

    public function update(UpdateUserRequest $request)
    {
        $user = Auth::guard('api')->user();

        $user->name = $request->input('name');
        $user->email = $request->input('email');

        if (!$user->save()) {
            return response()->json(['error' => 'User saving error']);
        }

        return response()->json($user);
    }
}
