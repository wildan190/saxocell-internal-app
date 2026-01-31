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
        Schema::table('warehouse_inventories', function (Blueprint $table) {
            $table->foreignId('product_variant_id')->nullable()->after('product_id')->constrained()->nullOnDelete();
            $table->dropUnique(['warehouse_id', 'product_id']);
            $table->unique(['warehouse_id', 'product_id', 'product_variant_id'], 'warehouse_product_variant_unique');
        });

        Schema::table('store_inventories', function (Blueprint $table) {
            $table->foreignId('product_variant_id')->nullable()->after('product_id')->constrained()->nullOnDelete();
            $table->dropUnique(['store_id', 'product_id']);
            $table->unique(['store_id', 'product_id', 'product_variant_id'], 'store_product_variant_unique');
        });

        Schema::table('stock_opname_items', function (Blueprint $table) {
            $table->foreignId('product_variant_id')->nullable()->after('product_id')->constrained()->nullOnDelete();
        });

        Schema::table('stock_transfer_items', function (Blueprint $table) {
            $table->foreignId('product_variant_id')->nullable()->after('product_id')->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_inventories', function (Blueprint $table) {
            $table->dropUnique('warehouse_product_variant_unique');
            $table->unique(['warehouse_id', 'product_id']);
            $table->dropForeign(['product_variant_id']);
            $table->dropColumn('product_variant_id');
        });

        Schema::table('store_inventories', function (Blueprint $table) {
            $table->dropUnique('store_product_variant_unique');
            $table->unique(['store_id', 'product_id']);
            $table->dropForeign(['product_variant_id']);
            $table->dropColumn('product_variant_id');
        });

        Schema::table('stock_opname_items', function (Blueprint $table) {
            $table->dropForeign(['product_variant_id']);
            $table->dropColumn('product_variant_id');
        });

        Schema::table('stock_transfer_items', function (Blueprint $table) {
            $table->dropForeign(['product_variant_id']);
            $table->dropColumn('product_variant_id');
        });
    }
};
