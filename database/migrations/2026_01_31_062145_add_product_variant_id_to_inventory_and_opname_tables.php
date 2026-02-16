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
        });

        // Remove duplicates before re-adding unique constraint
        if (Schema::hasTable('warehouse_inventories')) {
             \Illuminate\Support\Facades\DB::statement("
                DELETE FROM warehouse_inventories 
                WHERE id IN (
                    SELECT id FROM (
                        SELECT id, 
                        ROW_NUMBER() OVER (partition BY warehouse_id, product_id ORDER BY id) as rnum 
                        FROM warehouse_inventories
                    ) t 
                    WHERE t.rnum > 1
                )
            ");
        }

        Schema::table('warehouse_inventories', function (Blueprint $table) {
            $table->unique(['warehouse_id', 'product_id']);
            $table->dropForeign(['product_variant_id']);
            $table->dropColumn('product_variant_id');
        });

        Schema::table('store_inventories', function (Blueprint $table) {
            $table->dropUnique('store_product_variant_unique');
        });

        if (Schema::hasTable('store_inventories')) {
             \Illuminate\Support\Facades\DB::statement("
                DELETE FROM store_inventories 
                WHERE id IN (
                    SELECT id FROM (
                        SELECT id, 
                        ROW_NUMBER() OVER (partition BY store_id, product_id ORDER BY id) as rnum 
                        FROM store_inventories
                    ) t 
                    WHERE t.rnum > 1
                )
            ");
        }

        Schema::table('store_inventories', function (Blueprint $table) {
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
