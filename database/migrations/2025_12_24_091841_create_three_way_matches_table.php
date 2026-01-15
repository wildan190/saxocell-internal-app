<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('three_way_matches', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('purchase_order_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('delivery_order_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('invoice_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'matched', 'discrepancy'])->default('pending');
            $table->boolean('quantity_match')->default(false);
            $table->boolean('price_match')->default(false);
            $table->boolean('total_match')->default(false);
            $table->text('discrepancy_notes')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('three_way_matches');
    }
};
