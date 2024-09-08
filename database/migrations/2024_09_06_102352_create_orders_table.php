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
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->foreignId('plan_id')->constrained('plans')->cascadeOnDelete();
            $table->integer('amount');
            $table->date('expired_plan');
            $table->integer('payment_amount');
            $table->foreignId('payment_id')->constrained('payments')->cascadeOnDelete();
            $table->string('payment_method');
            $table->enum('status', ['pending', 'unpaid', 'paid', 'cancel'])->default('pending');
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
