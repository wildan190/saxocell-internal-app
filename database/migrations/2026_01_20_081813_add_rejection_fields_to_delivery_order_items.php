<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('delivery_order_items', function (Blueprint $table) {
            $table->text('rejection_reason')->nullable()->after('condition_notes');
            $table->enum('resolution_type', ['refund', 'replacement'])->nullable()->after('rejection_reason');
        });
    }

    public function down(): void
    {
        Schema::table('delivery_order_items', function (Blueprint $table) {
            $table->dropColumn(['rejection_reason', 'resolution_type']);
        });
    }
};
