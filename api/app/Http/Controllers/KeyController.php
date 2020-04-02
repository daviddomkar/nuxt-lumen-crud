<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use \Validator;
use App\Key;

class KeyController extends Controller
{
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

    public function delete(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'user_id' => 'required|exists:users,id',
            'room_id' => 'required|exists:rooms,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $keys = Key::where('room_id', $data['room_id'])->where('user_id', $data['user_id'])->get();

        foreach ($keys as $key) {
            $key->delete();
        }
        return response()->json(null, 204);
    }
}
