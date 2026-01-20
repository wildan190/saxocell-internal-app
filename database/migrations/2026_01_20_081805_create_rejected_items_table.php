<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rejected_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('delivery_order_id');
            $table->foreign('delivery_order_id')->references('id')->on('delivery_orders')->onDelete('cascade');
            $table->uuid('purchase_order_item_id');
            $table->foreign('purchase_order_item_id')->references('id')->on('purchase_order_items')->onDelete('cascade');
            $table->integer('quantity_rejected');
            $table->text('rejection_reason')->nullable();
            $table->enum('resolution_type', ['refund', 'replacement', 'pending'])->default('pending');
            $table->integer('replacement_received_quantity')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rejected_items');
    }
};
