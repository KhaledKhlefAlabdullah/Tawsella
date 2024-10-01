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
            $table->string('driver_id')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('taxi_id')->nullable();
            $table->string('movement_type_id');
            $table->string('start_address')->nullable();
            $table->string('destination_address')->nullable();
            $table->integer('gender')->default(\App\Enums\UserEnums\UserGender::male);
            $table->double('start_latitude');
            $table->double('start_longitude');
            $table->double('end_latitude')->nullable();
            $table->double('end_longitude')->nullable();
            $table->json('path')->nullable();
            $table->boolean('is_redirected')->default(false);
            $table->boolean('is_completed')->default(false);
            $table->boolean('is_canceled')->default(false);
            $table->integer('request_state')->default(\App\Enums\MovementRequestStatus::Pending);
            $table->string('state_message')->nullable();
            $table->foreign('driver_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('taxi_id')->references('id')->on('taxis')->onDelete('cascade');
            $table->foreign('movement_type_id')->references('id')->on('taxi_movement_types')->onDelete('cascade');
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
