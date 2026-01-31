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
        Schema::create('salary_components', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->enum('type', ['allowance', 'deduction']);
            $table->decimal('default_amount', 20, 2)->default(0.00);
            $table->boolean('is_fixed')->default(true);
            $table->timestamps();
        });

        Schema::create('employee_salary_components', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignUuid('salary_component_id')->constrained('salary_components')->onDelete('cascade');
            $table->decimal('amount', 20, 2);
            $table->timestamps();
        });

        Schema::create('overtime_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('employee_id')->constrained('employees')->onDelete('cascade');
            $table->date('date');
            $table->decimal('hours', 5, 2);
            $table->decimal('rate_per_hour', 20, 2);
            $table->decimal('total_amount', 20, 2);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('overtime_records');
        Schema::dropIfExists('employee_salary_components');
        Schema::dropIfExists('salary_components');
    }
};
