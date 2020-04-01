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
    public function index(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'user_id' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $rooms = array_key_exists('user_id', $data) ? [Room::findOrFail(User::findOrFail($data['user_id'])['room_id'])] : Room::all();

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
            'number' => 'required|string|unique:rooms|digits:3',
            'name' => 'required|string|min:2|max:255',
            'phone' => ['string', 'unique:rooms', 'regex:/^\d{4}|^$/'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if (array_key_exists('phone', $data) && strlen($data['phone']) === 0) {
            $data['phone'] = null;
        }

        $room = Room::create($data);

        return response()->json($room, 201);
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $data = $request->all();

        $validator = Validator::make($data, [
            'number' => ['string', 'digits:3', Rule::unique('rooms')->ignore($room)],
            'name' => 'string|min:2|max:255',
            'phone' => ['string', 'regex:/^\d{4}|^$/', Rule::unique('rooms')->ignore($room)],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if (array_key_exists('phone', $data) && strlen($data['phone']) === 0) {
            $data['phone'] = null;
        }

        $room->update($data);

        return response()->json($room, 200);
    }

    public function delete($id)
    {
        $room = Room::findOrFail($id);

        $users = User::where('room_id', $room['id'])->get();

        if ($users->isNotEmpty()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $keys = Key::where('room_id', $room['id'])->get();

        foreach ($keys as $key) {
            $key->delete();
        }

        $room->delete();

        return response()->json(null, 204);
    }
}
