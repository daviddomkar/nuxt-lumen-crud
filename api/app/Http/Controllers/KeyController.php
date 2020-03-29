<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use \Validator;
use App\Key;

class KeyController extends Controller
{
    public function index(Request $request)
    {        
        $data = $request->all();

        $validator = Validator::make($data, [
            'user_id' => 'exists:users,id',
            'room_id' => 'exists:rooms,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if (array_key_exists('user_id', $data) && array_key_exists('room_id', $data)) {
            return response()->json(Key::where('room_id', $data['room_id'])->where('user_id', $data['user_id'])->get(), 200);
        }

        if (array_key_exists('user_id', $data)) {
            return response()->json(Key::where('user_id', $data['user_id'])->get(), 200);
        }

        if (array_key_exists('room_id', $data)) {
            return response()->json(Key::where('room_id', $data['room_id'])->get(), 200);
        }

        return response()->json(Key::all(), 200);
    }

    public function show($id)
    {
        $key = Key::findOrFail($id);

        return response()->json($key, 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'user_id' => 'required|exists:users,id',
            'room_id' => 'required|exists:rooms,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $key = Key::create($data);

        return response()->json($key, 201);
    }

    public function update(Request $request, $id)
    {
        $key = Key::findOrFail($id);

        $data = $request->all();

        $validator = Validator::make($data, [
            'user_id' => 'required_without_all:room_id|exists:users,id',
            'room_id' => 'required_without_all:user_id|exists:rooms,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $key->update($data);

        return response()->json($key, 200);
    }

    public function delete($id)
    {
        $key = Room::findOrFail($id);
        $key->delete();

        return response()->json(null, 204);
    }
}
