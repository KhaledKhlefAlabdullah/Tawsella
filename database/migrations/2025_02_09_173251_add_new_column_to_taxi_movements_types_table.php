<?php

use App\Enums\PaymentTypesEnum;
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
        Schema::table('taxi_movement_types', function (Blueprint $table) {
            $table->float('price2')->nullable();
            $table->integer('payment2')->default(PaymentTypesEnum::TL);
            $table->renameColumn('price', 'price1');
            $table->renameColumn('payment', 'payment1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('taxi_movements_types', function (Blueprint $table) {
            //
        });
    }
};
