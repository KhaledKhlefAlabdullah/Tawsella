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
        Schema::create('movements', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('driver_id')->nullable();
            $table->string('customer_id');
            $table->string('start_address')->nullable();
            $table->string('destination_address')->nullable();
            $table->double('start_latitude');
            $table->double('start_longitude');
            $table->double('end_latitude')->nullable();
            $table->double('end_longitude')->nullable();
            $table->json('path')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->boolean('is_canceled')->default(false);
            $table->enum('request_state',['accepted','rejected','pending'])->default('pending');
            $table->foreign('driver_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->SoftDeletes();
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
