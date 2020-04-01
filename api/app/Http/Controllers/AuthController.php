<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\User;

class AuthController extends Controller
{
    public function login(Request $request) 
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['username', 'password']);

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function profile(Request $request)
    {
        return response()->json(Auth::user(), 200);
    }

    public function changeUsername(Request $request) 
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'username' => 'required|string|min:3|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = Auth::user();

        $user->update($data);

        return response()->json(null, 204);
    }

    public function changePassword(Request $request) 
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'password' => 'required|string|min:8|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = Auth::user();

        $data['password'] = Hash::make($data['password']);

        $user->update($data);

        return response()->json(null, 204);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return response()->json(null, 204);
    }
}
