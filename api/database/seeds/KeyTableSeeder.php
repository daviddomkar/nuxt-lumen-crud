<?php

use Illuminate\Database\Seeder;
use App\Key;

class KeysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Key::truncate();

        Key::create([
            'user_id' => 1,
            'room_id' => 1,
        ]);
    }
}
