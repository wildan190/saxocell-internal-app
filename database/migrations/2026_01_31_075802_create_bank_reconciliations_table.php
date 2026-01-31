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
        Schema::create('bank_reconciliations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');
            $table->date('statement_date');
            $table->decimal('opening_balance', 20, 2)->default(0.00);
            $table->decimal('closing_balance', 20, 2)->default(0.00);
            $table->decimal('reconciled_balance', 20, 2)->default(0.00);
            $table->enum('status', ['draft', 'completed'])->default('draft');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::table('journal_items', function (Blueprint $table) {
            $table->foreignUuid('bank_reconciliation_id')->nullable()->constrained('bank_reconciliations')->onDelete('set null');
            $table->timestamp('reconciled_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('journal_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('bank_reconciliation_id');
            $table->dropColumn('reconciled_at');
        });
        Schema::dropIfExists('bank_reconciliations');
    }
};
