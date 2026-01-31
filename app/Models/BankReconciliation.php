<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BankReconciliation extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'account_id',
        'statement_date',
        'opening_balance',
        'closing_balance',
        'reconciled_balance',
        'status',
        'notes',
        'created_by',
        'completed_at',
    ];

    protected $casts = [
        'statement_date' => 'date',
        'opening_balance' => 'decimal:2',
        'closing_balance' => 'decimal:2',
        'reconciled_balance' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function journalItems(): HasMany
    {
        return $this->hasMany(JournalItem::class);
    }
}
