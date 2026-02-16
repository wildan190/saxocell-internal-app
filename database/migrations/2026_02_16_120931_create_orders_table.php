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
            $table->uuid('id')->primary();
            $table->string('invoice_number')->unique();
            $table->foreignUuid('store_id')->constrained()->onDelete('cascade');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->decimal('total_amount', 12, 2);
            $table->enum('status', ['pending_payment', 'pending_confirmation', 'processing', 'completed', 'cancelled'])->default('pending_payment');
            $table->string('payment_proof_path')->nullable();
            $table->string('payment_method')->default('manual_transfer');
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
