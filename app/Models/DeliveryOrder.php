<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryOrder extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'do_number',
        'purchase_order_id',
        'supplier_id',
        'delivery_date',
        'received_by',
        'status',
        'notes',
    ];

    protected $casts = [
        'delivery_date' => 'date',
    ];

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(DeliveryOrderItem::class);
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'received_by');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($do) {
            if (empty($do->do_number)) {
                $do->do_number = self::generateDoNumber();
            }
        });
    }

    public static function generateDoNumber(): string
    {
        $latest = self::latest('id')->first();
        $number = $latest ? intval(substr($latest->do_number, 3)) + 1 : 1;
        return 'DO-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}
