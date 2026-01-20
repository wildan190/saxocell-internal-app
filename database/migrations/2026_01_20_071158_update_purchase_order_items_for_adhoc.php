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
        Schema::table('purchase_order_items', function (Blueprint $table) {
            // Drop foreign key first to allow modifying the column
            // We need to guess the index name or use array syntax which Laravel auto-resolves
            $table->dropForeign(['product_id']);
            
            // Make product_id nullable
            $table->foreignId('product_id')->nullable()->change();
            
            // Add constraint back
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            
            // Add new columns
            $table->string('item_name')->nullable()->after('product_variant_id'); 
            $table->text('description')->nullable()->after('item_name');
        });
    }

    public function down(): void
    {
        Schema::table('purchase_order_items', function (Blueprint $table) {
            // Drop the new custom columns
            $table->dropColumn(['item_name', 'description']);
            
            // Revert product_id to not nullable
            // Note: This might fail if there are NULLs.
            $table->dropForeign(['product_id']);
            $table->foreignId('product_id')->nullable(false)->change();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }
};
