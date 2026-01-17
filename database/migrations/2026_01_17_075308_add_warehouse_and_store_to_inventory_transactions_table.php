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
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->foreignUuid('warehouse_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignUuid('store_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignUuid('stock_transfer_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignUuid('stock_opname_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropForeign(['store_id']);
            $table->dropForeign(['stock_transfer_id']);
            $table->dropForeign(['stock_opname_id']);
            $table->dropColumn(['warehouse_id', 'store_id', 'stock_transfer_id', 'stock_opname_id']);
        });
    }
};
