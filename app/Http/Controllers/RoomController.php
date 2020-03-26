<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Room;

class RoomController extends Controller
{
    public function index()
    {
        return Room::all();
    }

    public function show(Room $room)
    {
        return $room;
    }

    public function store(Request $request)
    {
        $room = Room::create($request->all());

        return response()->json($room, 201);
    }

    public function update(Request $request, Room $room)
    {
        $room->update($request->all());

        return response()->json($room, 200);
    }

    public function delete(Room $room)
    {
        $room->delete();

        return response()->json(null, 204);
    }
}
