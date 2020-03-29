<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use \Validator;
use App\Room;
use App\Key;
use App\User;

class RoomController extends Controller
{
    public function index()
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'user_id' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::findOrFail($data['user_id']);

        $rooms = array_key_exists('user_id', $data) ? [Room::findOrFail($user['room_id'])] : Room::all();

        return response()->json($rooms, 200);
    }

    public function show($id)
    {
        $room = Room::findOrFail($id);

        return response()->json($room, 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'number' => 'required|unique:rooms|digits:3',
            'name' => 'required|string|min:2|max:255',
            'phone' => 'unique:rooms|digits:4',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $room = Room::create($data);

        return response()->json($room, 201);
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $data = $request->all();

        $validator = Validator::make($data, [
            'number' => ['required_without_all:name,phone', 'digits:3', Rule::unique('rooms')->ignore($room)],
            'name' => 'required_without_all:number,phone|string|min:2|max:255',
            'phone' => ['required_without_all:number,name', 'digits:4', Rule::unique('rooms')->ignore($room)],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $room->update($data);

        return response()->json($room, 200);
    }

    public function delete($id)
    {
        $room = Room::findOrFail($id);

        $users = User::where('room_id', $room['id'])->get();

        if ($users->isNotEmpty()) {
            return response()->json(['error' => 'Method not allowed'], 405);
        }

        $keys = Key::where('room_id', $room['id'])->get();

        foreach ($keys as $key) {
            $key->delete();
        }

        $room->delete();

        return response()->json(null, 204);
    }
}
