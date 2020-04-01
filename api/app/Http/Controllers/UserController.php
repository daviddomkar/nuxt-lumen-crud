<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use \Validator;
use App\User;
use App\Key;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
          'room_id' => 'exists:rooms,id',
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }

        $users = array_key_exists('room_id', $data) ? User::where('room_id', $data['room_id'])->get() : User::all();

        return response()->json($users, 200);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json($user, 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'first_name' => 'required|string|min:3|max:255',
            'last_name' => 'required|string|min:3|max:255',
            'position' => 'required|string|min:3|max:255',
            'salary' => 'required|numeric',
            'room_id' => 'required|exists:rooms,id',
            'username' => 'required|string|min:3|max:255|unique:users',
            'password' => 'required|string|min:8|max:255',
            'admin' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return response()->json($user, 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->all();

        $validator = Validator::make($data, [
            'first_name' => 'string|min:3|max:255',
            'last_name' => 'string|min:3|max:255',
            'position' => 'string|min:3|max:255',
            'salary' => 'numeric',
            'room_id' => 'exists:rooms,id',
            'username' => ['string', 'min:3', 'max:255', Rule::unique('users')->ignore($user)],
            'password' => 'string|min:8|max:255',
            'admin' => 'boolean',
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }

        if(array_key_exists('password', $data)) {
          $data['password'] = Hash::make($data['password']);
        }

        if($id == Auth::user()['id'] && array_key_exists('admin', $data) && $data['admin'] != Auth::user()['admin']) {
          return response()->json(['error' => 'Forbidden'], 403);
        }

        $user->update($data);

        return response()->json($user, 200);
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);

        if ($user['id'] == Auth::user()['id']) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $keys = Key::where('user_id', $user['id'])->get();

        foreach ($keys as $key) {
          $key->delete();
        }

        $user->delete();

        return response()->json(null, 204);
    }
}
