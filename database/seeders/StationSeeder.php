<?php

namespace Database\Seeders;

use App\Models\Station;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //create stations as described in the task
        Station::create(['name'=>'cairo']);
        Station::create(['name'=>'alex']);
        Station::create(['name'=>'aswan']);
    }
}
