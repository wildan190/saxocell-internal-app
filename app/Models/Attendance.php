<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'employee_id', 'date', 'clock_in', 'clock_out', 
        'lat_in', 'long_in', 'lat_out', 'long_out', 'status', 'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'clock_in' => 'datetime:H:i',
        'clock_out' => 'datetime:H:i',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    protected static function getActivityDescription(\Illuminate\Database\Eloquent\Model $model, string $action): string
    {
        if ($action === 'created') {
            return "Clocked In at " . $model->clock_in->format('H:i');
        }
        if ($action === 'updated' && $model->clock_out) {
            return "Clocked Out at " . $model->clock_out->format('H:i');
        }
        return "Attendance record {$action}";
    }
}
