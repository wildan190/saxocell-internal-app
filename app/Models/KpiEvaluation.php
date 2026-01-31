<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KpiEvaluation extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'period_name', 'total_score', 'feedback', 'evaluator_id', 'status'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(KpiEvaluationDetail::class);
    }
}
