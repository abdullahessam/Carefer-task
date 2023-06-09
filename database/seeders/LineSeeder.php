<?php

namespace Database\Seeders;

use App\Models\Line;
use Illuminate\Database\Seeder;

class LineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //create lines as described in the task
        Line::create([
            'start_station_id' => 1, //cairo
            'end_station_id' => 2, //alex
            'bus_id' => 1, //bus-1
            'distance' => 90, //short trip,
            'price' => 50,
        ]);
        Line::create([
            'start_station_id' => 1, //cairo
            'end_station_id' => 3, //aswan
            'bus_id' => 2, //bus-2
            'distance' => 1000, //long trip,
            'price' => 100,
        ]);
    }
}
