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
            $table->string('customer_id');
            $table->string('taxi_id')->nullable();
            $table->string('movement_type_id');
            $table->string('my_address')->nullable();
            $table->string('destnation_address')->nullable();
            $table->enum('gender',['male','female']);
            $table->double('start_latitude');
            $table->double('start_longitude');
            $table->double('end_latitude')->nullable();
            $table->double('end_longitude')->nullable();
            $table->boolean('is_don')->default(false);
            $table->boolean('is_completed')->default(false);
            $table->boolean('is_canceled')->default(false);
            $table->enum('request_state',['accepted','rejected','pending'])->default('pending');
            $table->foreign('driver_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
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
