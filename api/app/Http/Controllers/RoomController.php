<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use \Validator;
use App\Room;

class RoomController extends Controller
{
    public function index()
    {
        return Room::all();
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
            'name' => 'required|string|max:255',
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
            'name' => 'required_without_all:number,phone|string|max:255',
            'phone' => ['required_without_all:number,name', 'digits:4', Rule::unique('rooms')->ignore($room)],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $room->update($data);

        return response()->json($room, 200);
    }

    public function delete(Room $room)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return response()->json(null, 204);
    }
}
