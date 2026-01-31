<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KpiEvaluationDetail extends Model
{
    use HasFactory;

    protected $fillable = ['kpi_evaluation_id', 'kpi_indicator_id', 'score', 'comments'];

    public function indicator(): BelongsTo
    {
        return $this->belongsTo(KpiIndicator::class, 'kpi_indicator_id');
    }

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(KpiEvaluation::class, 'kpi_evaluation_id');
    }
}
