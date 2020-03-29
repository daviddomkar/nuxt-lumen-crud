<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User::truncate();

        $password = Hash::make('test1234');

        User::create([
            'first_name' => 'František',
            'last_name' => 'Netěsný',
            'position' => 'ředitel',
            'salary' => 65000,
            'room_id' => 1,
            'username' => 'franta',
            'password' => $password,
            'admin' => true,
        ]);

        User::create([
            'first_name' => 'Alena',
            'last_name' => 'Netěsná',
            'position' => 'ekonomka',
            'salary' => 42000,
            'room_id' => 5,
            'username' => 'alena',
            'password' => $password,
            'admin' => false,
        ]);

        User::create([
            'first_name' => 'Jiřina',
            'last_name' => 'Hamáčková',
            'position' => 'ekonomka',
            'salary' => 32000,
            'room_id' => 5,
            'username' => 'jirina',
            'password' => $password,
            'admin' => false,
        ]);

        User::create([
            'first_name' => 'Stanislav',
            'last_name' => 'Lorenc',
            'position' => 'skladník',
            'salary' => 14000,
            'room_id' => 8,
            'username' => 'stanislav',
            'password' => $password,
            'admin' => false,
        ]);

        User::create([
            'first_name' => 'Martina',
            'last_name' => 'Marková',
            'position' => 'skladnice',
            'salary' => 14500,
            'room_id' => 8,
            'username' => 'martina',
            'password' => $password,
            'admin' => false,
        ]);

        User::create([
            'first_name' => 'Tomáš',
            'last_name' => 'Kalousek',
            'position' => 'technik',
            'salary' => 23000,
            'room_id' => 7,
            'username' => 'tomas',
            'password' => $password,
            'admin' => false,
        ]);
    }
}
