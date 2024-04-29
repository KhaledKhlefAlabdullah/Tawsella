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
        Schema::create('offers', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('movement_type_id');
            $table->string('admin_id');
            $table->string('offer');
            $table->string('value_of_discount');
            $table->date('valide_date');
            $table->text('description')->nullable();
            $table->foreign('movement_type_id')->references('id')->on('taxi_movement_types')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->SoftDeletes();        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
