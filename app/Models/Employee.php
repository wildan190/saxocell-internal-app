<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id', 'employee_id', 'first_name', 'last_name', 'email', 'phone', 
        'position', 'department_id', 'join_date', 'status', 'base_salary',
        'bank_name', 'bank_account_number', 'bank_account_holder', 'address', 'profile_picture'
    ];

    protected $casts = [
        'join_date' => 'date',
        'base_salary' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }

    public function kpiEvaluations(): HasMany
    {
        return $this->hasMany(KpiEvaluation::class);
    }

    public function salaryComponents(): HasMany
    {
        return $this->hasMany(EmployeeSalaryComponent::class);
    }

    public function overtimeRecords(): HasMany
    {
        return $this->hasMany(OvertimeRecord::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
