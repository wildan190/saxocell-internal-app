<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalItem;
use App\Models\Store;
use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WarehouseFinanceController extends Controller
{
    // Income
    public function createIncome(Warehouse $warehouse)
    {
        $warehouse->load('accounts');
        $revenueAccounts = Account::where('type', 'revenue')->orWhere('type', 'income')->get();
        
        return view('warehouses.finance.income', compact('warehouse', 'revenueAccounts'));
    }

    public function storeIncome(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'income_date' => 'required|date',
            'description' => 'required|string',
            'revenue_account_id' => 'required|exists:accounts,id',
            'deposit_account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        if(!$warehouse->accounts->contains($request->deposit_account_id)) {
            return back()->with('error', 'The selected deposit account does not belong to this warehouse.')->withInput();
        }

        try {
            DB::transaction(function () use ($request, $warehouse) {
                $journalEntry = JournalEntry::create([
                    'entry_date' => $request->income_date,
                    'reference_number' => 'INC-WHS-' . time(),
                    'description' => $request->description,
                    'source_type' => 'warehouse_income',
                    'created_by' => auth()->id(),
                ]);

                // Debit (Increase Asset)
                JournalItem::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $request->deposit_account_id,
                    'debit' => $request->amount,
                    'credit' => 0,
                    'description' => 'Warehouse Income Deposit',
                ]);
                
                $depositAccount = Account::find($request->deposit_account_id);
                $depositAccount->increment('current_balance', $request->amount);

                // Credit (Revenue)
                JournalItem::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $request->revenue_account_id,
                    'debit' => 0,
                    'credit' => $request->amount,
                    'description' => 'Revenue Recognition (Warehouse: ' . $warehouse->name . ')',
                ]);
            });

            return redirect()->route('warehouses.show', $warehouse)->with('success', 'Income recorded successfully.');
        } catch (\Exception $e) {
            Log::error('Warehouse Income Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to record income: ' . $e->getMessage())->withInput();
        }
    }

    // Transfer
    public function createTransfer(Warehouse $warehouse)
    {
        $warehouse->load('accounts');
        $stores = Store::with('accounts')->get();
        $warehouses = Warehouse::where('id', '!=', $warehouse->id)->with('accounts')->get();
        
        return view('warehouses.finance.transfer', compact('warehouse', 'stores', 'warehouses'));
    }

    public function storeTransfer(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'transfer_date' => 'required|date',
            'description' => 'nullable|string',
            'source_account_id' => 'required|exists:accounts,id',
            'destination_type' => 'required|in:store,warehouse',
            'destination_id' => 'required', 
            'destination_account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        if(!$warehouse->accounts->contains($request->source_account_id)) {
            return back()->with('error', 'Source account does not belong to this warehouse.')->withInput();
        }

        try {
            DB::transaction(function () use ($request, $warehouse) {
                $amount = $request->amount;
                
                $journalEntry = JournalEntry::create([
                    'entry_date' => $request->transfer_date,
                    'reference_number' => 'TRF-WHS-' . time(),
                    'description' => $request->description ?? 'Warehouse Transfer Out',
                    'source_type' => 'warehouse_transfer',
                    'created_by' => auth()->id(),
                ]);

                // Credit Source
                JournalItem::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $request->source_account_id,
                    'credit' => $amount,
                    'debit' => 0,
                    'description' => 'Transfer Out to ' . ucfirst($request->destination_type),
                ]);

                $sourceAccount = Account::find($request->source_account_id);
                $sourceAccount->decrement('current_balance', $amount);

                // Debit Destination
                JournalItem::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $request->destination_account_id,
                    'debit' => $amount,
                    'credit' => 0,
                    'description' => 'Transfer In from ' . $warehouse->name,
                ]);

                $destAccount = Account::find($request->destination_account_id);
                $destAccount->increment('current_balance', $amount);
            });

            return redirect()->route('warehouses.show', $warehouse)->with('success', 'Transfer sent successfully.');
        } catch (\Exception $e) {
            Log::error('Warehouse Transfer Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to transfer: ' . $e->getMessage())->withInput();
        }
    }

    // Supplier Payment
    public function paySupplier(Warehouse $warehouse)
    {
        $warehouse->load('accounts');
        $invoices = Invoice::where('warehouse_id', $warehouse->id)
            ->where('payment_status', '!=', 'paid')
            ->whereIn('status', ['approved', 'matched'])
            ->with('supplier')
            ->get();
            
        $cashAccounts = $warehouse->accounts()->whereIn('category', ['cash', 'bank'])->get();
        
        return view('warehouses.finance.pay_supplier', compact('warehouse', 'invoices', 'cashAccounts'));
    }

    public function storePayment(Request $request, Warehouse $warehouse)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'account_id' => 'required|exists:accounts,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required',
            'reference_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $invoice = Invoice::where('warehouse_id', $warehouse->id)->findOrFail($validated['invoice_id']);
        
        if(!$warehouse->accounts->contains($validated['account_id'])) {
            return back()->with('error', 'The selected account does not belong to this warehouse.')->withInput();
        }

        try {
            DB::transaction(function() use ($validated, $invoice, $warehouse) {
                $payment = Payment::create([
                    'invoice_id' => $invoice->id,
                    'account_id' => $validated['account_id'],
                    'payment_date' => $validated['payment_date'],
                    'amount' => $validated['amount'],
                    'payment_method' => $validated['payment_method'],
                    'reference_number' => $validated['reference_number'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                    'created_by' => auth()->id() ?? User::first()->id,
                ]);

                // Create Journal Entry
                // Debit: Accounts Payable (Liability decrease)
                // Credit: Warehouse Cash/Bank (Asset decrease)
                $apAccount = Account::where('category', 'payable')->first();
                $cashAccount = Account::find($validated['account_id']);

                $entry = JournalEntry::create([
                    'entry_date' => $validated['payment_date'],
                    'description' => "Warehouse Payment for Invoice #{$invoice->invoice_number}",
                    'source_type' => 'warehouse_supplier_payment',
                    'source_id' => $payment->id,
                    'created_by' => auth()->id(),
                ]);

                JournalItem::create([
                    'journal_entry_id' => $entry->id,
                    'account_id' => $apAccount->id,
                    'debit' => $validated['amount'],
                    'credit' => 0,
                    'description' => $entry->description,
                ]);

                JournalItem::create([
                    'journal_entry_id' => $entry->id,
                    'account_id' => $cashAccount->id,
                    'debit' => 0,
                    'credit' => $validated['amount'],
                    'description' => $entry->description,
                ]);

                // Update Balances
                $apAccount->increment('current_balance', -$validated['amount']); // AP is Liability, decrease = debit
                $cashAccount->decrement('current_balance', $validated['amount']);

                // Update Invoice Status
                if ($validated['amount'] >= $invoice->total_amount) {
                    $invoice->payment_status = 'paid';
                } else {
                    $invoice->payment_status = 'partial';
                }
                $invoice->save();
            });

            return redirect()->route('warehouses.show', $warehouse)->with('success', 'Supplier payment recorded successfully.');
        } catch (\Exception $e) {
            Log::error('Warehouse Supplier Payment Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to record payment: ' . $e->getMessage())->withInput();
        }
    }
}
