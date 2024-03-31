<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('taxis', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->string('driver_id');
            $table->string('car_name');
            $table->string('lamp_number');
            $table->string('plate_number');
            $table->string('car_detailes');
            $table->double('last_location_latitude')->nullable();
            $table->double('last_location_longitude')->nullable();
            $table->foreign('driver_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxis');
    }
};
