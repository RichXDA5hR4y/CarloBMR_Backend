<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('phone')->nullable(); // 👈 TAMBAHKAN BARIS INI
            $table->text('address')->nullable(); // 👈 TAMBAHKAN BARIS INI
            $table->enum('role', ['admin', 'cashier', 'customer'])->default('customer'); // 👈 TAMBAHKAN BARIS INI
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};