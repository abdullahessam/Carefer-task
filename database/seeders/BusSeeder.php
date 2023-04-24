<?php

namespace Database\Seeders;

use App\Models\Bus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //create buses as described in the task
        Bus::create([
            'name' => 'bus-1'
        ]);
        Bus::create([
            'name' => 'bus-2'
        ]);
    }
}
