<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    protected $fillable = [
        'code',
        'name',
        'type',
        'category',
        'is_active',
        'current_balance',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'current_balance' => 'decimal:2',
    ];

    public function journalItems(): HasMany
    {
        return $this->hasMany(JournalItem::class);
    }

    public function bankAccount()
    {
        return $this->hasOne(BankAccount::class);
    }

    /**
     * Calculate balance from journal items.
     */
    public function calculateBalance(): float
    {
        $sum = $this->journalItems()->selectRaw('SUM(debit) as debits, SUM(credit) as credits')->first();
        
        if ($this->type === 'asset' || $this->type === 'expense') {
            return ($sum->debits ?? 0) - ($sum->credits ?? 0);
        }
        
        return ($sum->credits ?? 0) - ($sum->debits ?? 0);
    }
}
