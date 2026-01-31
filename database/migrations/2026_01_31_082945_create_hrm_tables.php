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
        // Departments
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('description')->nullable();
            $table->foreignId('manager_id')->nullable(); // circular ref handled later
            $table->timestamps();
        });

        // Employees
        Schema::create('employees', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('employee_id')->unique(); // NIK
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('position');
            $table->foreignId('department_id')->constrained('departments');
            $table->date('join_date');
            $table->enum('status', ['active', 'probation', 'resigned', 'terminated'])->default('active');
            $table->decimal('base_salary', 20, 2)->default(0.00);
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_holder')->nullable();
            $table->text('address')->nullable();
            $table->string('profile_picture')->nullable();
            $table->timestamps();
        });

        // Add circular reference for department manager
        Schema::table('departments', function (Blueprint $table) {
            $table->foreign('manager_id')->references('id')->on('users')->onDelete('set null');
        });

        // Attendance
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('employee_id')->constrained('employees')->onDelete('cascade');
            $table->date('date');
            $table->time('clock_in')->nullable();
            $table->time('clock_out')->nullable();
            $table->decimal('lat_in', 10, 8)->nullable();
            $table->decimal('long_in', 11, 8)->nullable();
            $table->decimal('lat_out', 10, 8)->nullable();
            $table->decimal('long_out', 11, 8)->nullable();
            $table->enum('status', ['present', 'late', 'early_leave', 'absent', 'on_leave'])->default('present');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Payrolls
        Schema::create('payrolls', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('employee_id')->constrained('employees')->onDelete('cascade');
            $table->integer('month');
            $table->integer('year');
            $table->decimal('basic_salary', 20, 2);
            $table->decimal('allowances', 20, 2)->default(0.00);
            $table->decimal('deductions', 20, 2)->default(0.00);
            $table->decimal('net_salary', 20, 2);
            $table->enum('status', ['draft', 'approved', 'paid'])->default('draft');
            $table->foreignUuid('journal_entry_id')->nullable()->constrained('journal_entries')->onDelete('set null');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        // Recruitment - Job Postings
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('department_id')->constrained('departments');
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->enum('status', ['draft', 'open', 'closed'])->default('open');
            $table->date('closing_date')->nullable();
            $table->timestamps();
        });

        // Recruitment - Applicants
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_posting_id')->constrained('job_postings')->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('resume_path')->nullable();
            $table->enum('status', ['new', 'shortlisted', 'interviewing', 'offered', 'hired', 'rejected'])->default('new');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // KPIs - Indicators
        Schema::create('kpi_indicators', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('weight'); // Percentage
            $table->string('target')->nullable();
            $table->timestamps();
        });

        // KPIs - Evaluations
        Schema::create('kpi_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('period_name'); // e.g. "2024 - Q1"
            $table->decimal('total_score', 5, 2)->default(0.00);
            $table->text('feedback')->nullable();
            $table->foreignId('evaluator_id')->constrained('users');
            $table->enum('status', ['draft', 'submitted', 'finalized'])->default('draft');
            $table->timestamps();
        });

        // KPI Evaluation Details
        Schema::create('kpi_evaluation_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kpi_evaluation_id')->constrained('kpi_evaluations')->onDelete('cascade');
            $table->foreignId('kpi_indicator_id')->constrained('kpi_indicators');
            $table->decimal('score', 5, 2);
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpi_evaluation_details');
        Schema::dropIfExists('kpi_evaluations');
        Schema::dropIfExists('kpi_indicators');
        Schema::dropIfExists('applicants');
        Schema::dropIfExists('job_postings');
        Schema::dropIfExists('payrolls');
        Schema::dropIfExists('attendances');
        Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
        });
        Schema::dropIfExists('employees');
        Schema::dropIfExists('departments');
    }
};
