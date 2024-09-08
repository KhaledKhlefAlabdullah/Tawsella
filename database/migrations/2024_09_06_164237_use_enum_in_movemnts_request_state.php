<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \App\Enums\MovementRequestStatus;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('movements', function (Blueprint $table) {
            $table->integer('request_state')->default(MovementRequestStatus::Pending)->change();
        });
    }
};
