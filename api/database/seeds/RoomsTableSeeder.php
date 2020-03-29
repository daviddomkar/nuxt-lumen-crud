<?php

use Illuminate\Database\Seeder;
use App\Room;

class RoomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Room::truncate();

        Room::create([
            'number' => '101',
            'name' => 'Ředitelna',
            'phone' => '2292',
        ]);

        Room::create([
            'number' => '102',
            'name' => 'Kuchyňka',
            'phone' => '2293',
        ]);

        Room::create([
            'number' => '104',
            'name' => 'Zasedací místnost',
            'phone' => '2294',
        ]);

        Room::create([
            'number' => '201',
            'name' => 'Xerox',
            'phone' => '2296',
        ]);

        Room::create([
            'number' => '202',
            'name' => 'Ekonomické',
            'phone' => '2295',
        ]);

        Room::create([
            'number' => '203',
            'name' => 'Toalety',
            'phone' => NULL,
        ]);

        Room::create([
            'number' => '001',
            'name' => 'Dílna',
            'phone' => '2241',
        ]);

        Room::create([
            'number' => '002',
            'name' => 'Sklad',
            'phone' => '2243',
        ]);
    }
}
