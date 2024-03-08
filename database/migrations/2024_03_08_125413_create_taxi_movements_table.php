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
        Schema::create('taxi_movements', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('driver_id');
            $table->string('customer_id');
            $table->string('taxi_id');
            $table->string('movement_type_id');
            $table->double('start_latitude');
            $table->double('start_longitude');
            $table->double('end_latitude')->nullable();
            $table->double('end_longitude')->nullable();
            $table->boolean('is_completed')->default(false)->nullable();
            $table->boolean('is_canceled')->default(false)->nullable();
            $table->foreign('driver_id')->references('users')->on('id')->onDelete('cascade');
            $table->foreign('customer_id')->references('users')->on('id')->onDelete('cascade');
            $table->foreign('taxi_id')->references('taxis')->on('id')->onDelete('cascade');
            $table->foreign('movement_type_id')->references('taxi_movement_types')->on('id')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxi_movements');
    }
};
