<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Models\Invoice;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\ThreeWayMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Cache::tags(['invoices'])->remember('invoices:all:' . request('page', 1), 3600, function () {
            return Invoice::with('purchaseOrder', 'supplier', 'threeWayMatch')->latest()->paginate(15);
        });
        return view('procurement.invoices.index', compact('invoices'));
    }

    public function create(Request $request)
    {
        $selectedPo = null;
        if ($request->filled('po_id')) {
            $selectedPo = PurchaseOrder::with('items.product', 'items.variant', 'supplier', 'deliveryOrders.items')
                ->findOrFail($request->po_id);
        }

        $purchaseOrders = PurchaseOrder::whereIn('status', ['approved', 'partial', 'completed'])->get();
        return view('procurement.invoices.create', compact('purchaseOrders', 'selectedPo'));
    }

    public function store(StoreInvoiceRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $po = PurchaseOrder::findOrFail($data['purchase_order_id']);
            
            // Calculate totals
            $subtotal = 0;
            $taxAmount = 0;
            foreach ($data['items'] as $item) {
                $itemSubtotal = $item['quantity'] * $item['unit_price'];
                $itemTax = $itemSubtotal * ($item['tax_rate'] / 100);
                $subtotal += $itemSubtotal;
                $taxAmount += $itemTax;
            }
            
            // Create Invoice
            $invoice = Invoice::create([
                'invoice_number' => $data['invoice_number'],
                'purchase_order_id' => $po->id,
                'supplier_id' => $po->supplier_id,
                'invoice_date' => $data['invoice_date'],
                'due_date' => $data['due_date'] ?? null,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $subtotal + $taxAmount,
                'notes' => $data['notes'] ?? null,
                'status' => 'pending',
                'payment_status' => 'unpaid',
            ]);
            
            // Create Invoice Items
            foreach ($data['items'] as $itemData) {
                $poItem = PurchaseOrderItem::findOrFail($itemData['purchase_order_item_id']);
                $invoice->items()->create([
                    'purchase_order_item_id' => $poItem->id,
                    'product_id' => $poItem->product_id,
                    'product_variant_id' => $poItem->product_variant_id,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'tax_rate' => $itemData['tax_rate'],
                    'subtotal' => $itemData['quantity'] * $itemData['unit_price'],
                ]);
            }
            
            // Perform 3-Way Matching
            $this->performThreeWayMatch($invoice);
            
            DB::commit();
            Cache::tags(['invoices'])->flush();
            return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice recorded and matched.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to record invoice: ' . $e->getMessage())->withInput();
        }
    }

    protected function performThreeWayMatch(Invoice $invoice)
    {
        $po = $invoice->purchaseOrder;
        
        // 1. Quantity Match: Total received across all DOs vs Invoiced Qty
        $quantityMatch = true;
        foreach ($invoice->items as $invItem) {
            $poItem = $invItem->purchaseOrderItem;
            if ($invItem->quantity != $poItem->quantity_received) {
                $quantityMatch = false;
                break;
            }
        }
        
        // 2. Price Match: Invoiced unit price vs PO unit price
        $priceMatch = true;
        foreach ($invoice->items as $invItem) {
            $poItem = $invItem->purchaseOrderItem;
            if (abs($invItem->unit_price - $poItem->unit_price) > 0.01) {
                $priceMatch = false;
                break;
            }
        }
        
        // 3. Total Match: Invoice total vs PO total (if applicable)
        $totalMatch = abs($invoice->total_amount - $po->total_amount) < 0.01;
        
        $matchStatus = ($quantityMatch && $priceMatch && $totalMatch) ? 'matched' : 'discrepancy';
        
        ThreeWayMatch::create([
            'purchase_order_id' => $po->id,
            'delivery_order_id' => $po->deliveryOrders()->latest()->first()?->id, // Simplified for now
            'invoice_id' => $invoice->id,
            'status' => $matchStatus,
            'quantity_match' => $quantityMatch,
            'price_match' => $priceMatch,
            'total_match' => $totalMatch,
        ]);
        
        $invoice->update([
            'status' => $matchStatus,
            'matched_at' => now(),
        ]);

        Cache::tags(['invoices'])->flush();
    }

    public function show($id)
    {
        $invoice = Cache::tags(['invoices'])->remember("invoices:show:{$id}", 3600, function () use ($id) {
            return Invoice::with('items.product', 'items.variant', 'purchaseOrder', 'supplier', 'threeWayMatch')->findOrFail($id);
        });
        return view('procurement.invoices.show', compact('invoice'));
    }
}
