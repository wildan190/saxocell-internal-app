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
use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalItem;
use App\Models\Payment;
use App\Models\User;

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
        
        $accounts = \App\Models\Account::whereIn('category', ['cash', 'bank'])->get();
        return view('procurement.invoices.show', compact('invoice', 'accounts'));
    }

    public function approve(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        
        if ($invoice->status !== 'matched') {
            return back()->with('error', 'Only matched invoices can be posted to the ledger.');
        }

        if ($invoice->approved_at) {
            return back()->with('error', 'Invoice already posted to ledger.');
        }

        DB::transaction(function() use ($invoice) {
            // Create Journal Entry
            // Debit: Inventory Asset (Asset increase)
            // Credit: Accounts Payable (Liability increase)
            
            $inventoryAccount = \App\Models\Account::where('category', 'inventory')->first();
            $apAccount = \App\Models\Account::where('category', 'payable')->first();

            $entry = \App\Models\JournalEntry::create([
                'entry_date' => now(),
                'description' => "Purchase of goods via Invoice #{$invoice->invoice_number}",
                'source_type' => 'invoice',
                'source_id' => $invoice->id,
                'created_by' => auth()->id() ?? \App\Models\User::first()->id,
            ]);

            \App\Models\JournalItem::create([
                'journal_entry_id' => $entry->id,
                'account_id' => $inventoryAccount->id,
                'debit' => $invoice->total_amount,
                'credit' => 0,
                'description' => "Inventory replenishment from {$invoice->supplier->name}",
            ]);

            \App\Models\JournalItem::create([
                'journal_entry_id' => $entry->id,
                'account_id' => $apAccount->id,
                'debit' => 0,
                'credit' => $invoice->total_amount,
                'description' => "Debt to {$invoice->supplier->name}",
            ]);

            // Update Account Balances
            $inventoryAccount->current_balance = $inventoryAccount->calculateBalance();
            $inventoryAccount->save();
            $apAccount->current_balance = $apAccount->calculateBalance();
            $apAccount->save();

            $invoice->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id() ?? \App\Models\User::first()->id,
            ]);
        });

        Cache::tags(['invoices'])->flush();
        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice approved and posted to General Ledger.');
    }
    public function approveAndPay(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        $bankAccountId = $request->input('account_id');

        if (!$bankAccountId) {
            return back()->with('error', 'Please select a bank account for payment.');
        }

        if ($invoice->status !== 'matched') {
            return back()->with('error', 'Only matched invoices can be approved.');
        }

        if ($invoice->approved_at) {
            return back()->with('error', 'Invoice already approved.');
        }

        DB::transaction(function() use ($invoice, $bankAccountId) {
            // 1. APPROVE (Post to Ledger: Inventory vs AP)
            $inventoryAccount = Account::where('category', 'inventory')->first();
            $apAccount = Account::where('category', 'payable')->first();

            $approveEntry = JournalEntry::create([
                'entry_date' => now(),
                'description' => "Purchase of goods via Invoice #{$invoice->invoice_number}",
                'source_type' => 'invoice',
                'source_id' => $invoice->id,
                'created_by' => auth()->id() ?? User::first()->id,
            ]);

            JournalItem::create([
                'journal_entry_id' => $approveEntry->id,
                'account_id' => $inventoryAccount->id,
                'debit' => $invoice->total_amount,
                'credit' => 0,
                'description' => "Inventory replenishment from {$invoice->supplier->name}",
            ]);

            JournalItem::create([
                'journal_entry_id' => $approveEntry->id,
                'account_id' => $apAccount->id,
                'debit' => 0,
                'credit' => $invoice->total_amount,
                'description' => "Debt to {$invoice->supplier->name}",
            ]);

            // 2. PAY (Post to Ledger: AP vs Cash/Bank)
            $cashAccount = Account::findOrFail($bankAccountId);
            
            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'account_id' => $bankAccountId,
                'payment_date' => now(),
                'amount' => $invoice->total_amount,
                'payment_method' => 'transfer', 
                'created_by' => auth()->id() ?? User::first()->id,
            ]);

            $payEntry = JournalEntry::create([
                'entry_date' => now(),
                'description' => "Payment for Invoice #{$invoice->invoice_number}",
                'source_type' => 'payment',
                'source_id' => $payment->id,
                'created_by' => $payment->created_by,
            ]);

            JournalItem::create([
                'journal_entry_id' => $payEntry->id,
                'account_id' => $apAccount->id,
                'debit' => $invoice->total_amount,
                'credit' => 0,
                'description' => $payEntry->description,
            ]);

            JournalItem::create([
                'journal_entry_id' => $payEntry->id,
                'account_id' => $cashAccount->id,
                'debit' => 0,
                'credit' => $invoice->total_amount,
                'description' => $payEntry->description,
            ]);

            // Update All Balances
            $inventoryAccount->current_balance = $inventoryAccount->calculateBalance();
            $inventoryAccount->save();
            $apAccount->current_balance = $apAccount->calculateBalance();
            $apAccount->save();
            $cashAccount->current_balance = $cashAccount->calculateBalance();
            $cashAccount->save();

            // Update Invoice
            $invoice->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id() ?? User::first()->id,
                'payment_status' => 'paid',
            ]);
        });

        Cache::tags(['invoices'])->flush();
        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice approved and payment successfully recorded.');
    }
}
