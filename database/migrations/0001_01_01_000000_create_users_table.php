<?php

use App\Enums\UserEnums\DriverState;
use App\Enums\UserEnums\UserType;
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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->string('email')->unique();
            $table->double('points')->default(0);
            $table->string('password');
            $table->integer('user_type')->default(UserType::Customer());
            $table->integer('driver_state')->default(DriverState::Ready());
            $table->boolean('is_active')->default(true);
            $table->double('last_location_latitude')->nullable();
            $table->double('last_location_longitude')->nullable();
            $table->timestamp('mail_code_verified_at')->nullable();
            $table->string('mail_verify_code')->nullable();
            $table->tinyInteger('mail_code_attempts_left')->default(0);
            $table->timestamp('mail_code_last_attempt_date')->nullable();
            $table->timestamp('mail_verify_code_sent_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->SoftDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
