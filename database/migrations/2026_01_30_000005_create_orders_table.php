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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('order_number')->unique();
            $table->integer('total_amount');
            $table->enum('status', ['pending', 'confirmed', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->enum('payment_method', ['transfer', 'cash'])->default('transfer');
            $table->enum('payment_status', ['unpaid', 'paid', 'verified'])->default('unpaid');
            $table->string('payment_proof')->nullable();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('customer_address')->nullable();
            $table->text('notes')->nullable();
            $table->text('whatsapp_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};