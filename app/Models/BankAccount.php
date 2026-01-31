<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankAccount extends Model
{
    protected $fillable = [
        'account_id',
        'bank_name',
        'account_number',
        'account_holder',
        'currency',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
