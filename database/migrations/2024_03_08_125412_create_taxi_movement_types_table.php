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
        Schema::create('taxi_movement_types', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('type');
            $table->float('price');
            $table->text('description')->nullable();
            $table->boolean('is_onKM')->default(false);
            $table->enum('payment',['TL','$']);
            $table->timestamps();
            $table->SoftDeletes();        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxi_movement_types');
    }
};
