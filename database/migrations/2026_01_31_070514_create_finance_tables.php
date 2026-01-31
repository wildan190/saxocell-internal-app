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
        // Chart of Accounts
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('type', ['asset', 'liability', 'equity', 'revenue', 'expense']);
            $table->enum('category', ['cash', 'bank', 'receivable', 'payable', 'inventory', 'fixed_asset', 'current_asset', 'current_liability', 'tax', 'operating_revenue', 'operating_expense', 'other'])->nullable();
            $table->boolean('is_active')->default(true);
            $table->decimal('current_balance', 20, 2)->default(0.00);
            $table->timestamps();
        });

        // Bank Accounts (Cash Management)
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('account_holder');
            $table->string('currency')->default('IDR');
            $table->timestamps();
        });

        // Journal Entries
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('entry_date');
            $table->string('reference_number')->nullable();
            $table->string('description');
            $table->string('source_type')->nullable(); // e.g., 'invoice', 'payment', 'manual'
            $table->uuid('source_id')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // Journal Items (Split)
        Schema::create('journal_items', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('journal_entry_id')->constrained('journal_entries')->onDelete('cascade');
            $table->foreignId('account_id')->constrained('accounts');
            $table->decimal('debit', 20, 2)->default(0.00);
            $table->decimal('credit', 20, 2)->default(0.00);
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Payments (AP/AR)
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('invoice_id')->nullable()->constrained('invoices')->onDelete('cascade');
            $table->foreignId('account_id')->constrained('accounts'); // The Cash/Bank account used
            $table->date('payment_date');
            $table->decimal('amount', 20, 2);
            $table->string('payment_method'); // cash, transfer, cheque
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('journal_items');
        Schema::dropIfExists('journal_entries');
        Schema::dropIfExists('bank_accounts');
        Schema::dropIfExists('accounts');
    }
};
