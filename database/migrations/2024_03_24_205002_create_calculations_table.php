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
        Schema::create('calculations', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->string('driver_id');
            $table->string('taxi_movement_id');
            $table->float('totalPrice');
            $table->float('way')->nullable();
            $table->boolean('is_bring')->default(false);
            $table->foreign('driver_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('taxi_movement_id')->references('id')->on('taxi_movements')->onDelete('cascade');
            $table->timestamps();
            $table->SoftDeletes();          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calculations');
    }
};
