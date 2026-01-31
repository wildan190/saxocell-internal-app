<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JournalEntry extends Model
{
    use HasUuids;

    protected $fillable = [
        'entry_date',
        'reference_number',
        'description',
        'source_type',
        'source_id',
        'created_by',
    ];

    protected $casts = [
        'entry_date' => 'date',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(JournalItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Check if debits equal credits.
     */
    public function isBalanced(): bool
    {
        $debits = $this->items->sum('debit');
        $credits = $this->items->sum('credit');
        return abs($debits - $credits) < 0.001;
    }
}
