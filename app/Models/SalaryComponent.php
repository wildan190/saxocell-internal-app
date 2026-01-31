<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SalaryComponent extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'type',
        'default_amount',
        'is_fixed',
    ];

    protected $casts = [
        'default_amount' => 'decimal:2',
        'is_fixed' => 'boolean',
    ];

    public function employeeComponents()
    {
        return $this->hasMany(EmployeeSalaryComponent::class);
    }
}
