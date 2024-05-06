<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
            $table->string('password');
            $table->enum('user_type', ['customer', 'taxi driver', 'transport car driver', 'motorcyclist', 'admin'])->default('customer');
            $table->enum('driver_state', ['ready', 'in_break', 'reserved'])->default('ready');
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

        $password= Hash::make('123');
        DB::table('users')->insert([
            'id' => \Illuminate\Support\Str::uuid(),
            'email'=>'admin@email.com',
            'password'=>$password,
            'user_type' => 'admin',
            'mail_code_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
