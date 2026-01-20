<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'po_number',
        'supplier_id',
        'status',
        'order_date',
        'expected_delivery_date',
        'subtotal',
        'tax_amount',
        'shipping_cost',
        'total_amount',
        'notes',
        'approved_by',
        'approved_at',
        'created_by',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_delivery_date' => 'date',
        'approved_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function deliveryOrders(): HasMany
    {
        return $this->hasMany(DeliveryOrder::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($po) {
            if (empty($po->po_number)) {
                $po->po_number = self::generatePoNumber();
            }
        });
    }

    public static function generatePoNumber(): string
    {
        $latest = self::latest('id')->first();
        $number = $latest ? intval(substr($latest->po_number, 3)) + 1 : 1;
        return 'PO-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Check if there are any pending replacements for rejected items.
     */
    public function hasPendingReplacements(): bool
    {
        return RejectedItem::whereHas('deliveryOrder', function ($query) {
            $query->where('purchase_order_id', $this->id);
        })->where('resolution_type', 'replacement')
          ->whereColumn('replacement_received_quantity', '<', 'quantity_rejected')
          ->exists();
    }

    /**
     * Check if all items in the PO are fully resolved (received or refunded).
     */
    public function isFullyResolved(): bool
    {
        foreach ($this->items as $item) {
            $receivedCount = $item->quantity_received;
            
            // Get refund count for this item
            $refundCount = RejectedItem::where('purchase_order_item_id', $item->id)
                ->where('resolution_type', 'refund')
                ->sum('quantity_rejected');

            if (($receivedCount + $refundCount) < $item->quantity_ordered) {
                return false;
            }
        }

        // Also check if any replacement is still pending
        if ($this->hasPendingReplacements()) {
            return false;
        }

        return true;
    }
}
