<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lines', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Station::class, 'start_station_id')->constrained('stations');
            $table->foreignIdFor(\App\Models\Station::class, 'end_station_id')->constrained('stations');
            $table->foreignIdFor(\App\Models\Bus::class)->constrained();
            $table->unsignedInteger('distance');
            $table->decimal('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lines');
    }
};
