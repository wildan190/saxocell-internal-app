<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalItem;
use App\Models\Store;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StoreFinanceController extends Controller
{
    // Income
    public function createIncome(Store $store)
    {
        $store->load('accounts');
        // Revenue accounts for categorization
        $revenueAccounts = Account::where('type', 'revenue')->orWhere('type', 'income')->get();
        
        return view('stores.finance.income', compact('store', 'revenueAccounts'));
    }

    public function storeIncome(Request $request, Store $store)
    {
        $request->validate([
            'income_date' => 'required|date',
            'description' => 'required|string',
            'revenue_account_id' => 'required|exists:accounts,id',
            'deposit_account_id' => 'required|exists:accounts,id', // Account belonging to THIS store
            'amount' => 'required|numeric|min:0.01',
        ]);

        // Verify deposit account belongs to store
        if(!$store->accounts->contains($request->deposit_account_id)) {
            return back()->with('error', 'The selected deposit account does not belong to this store.')->withInput();
        }

        try {
            DB::transaction(function () use ($request, $store) {
                // Create Journal Entry
                $journalEntry = JournalEntry::create([
                    'entry_date' => $request->income_date,
                    'reference_number' => 'INC-STR-' . time(),
                    'description' => $request->description,
                    'source_type' => 'store_income',
                    'created_by' => auth()->id(),
                ]);

                // Debit (Increase Asset/Cash)
                JournalItem::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $request->deposit_account_id,
                    'debit' => $request->amount,
                    'credit' => 0,
                    'description' => 'Store Income Deposit',
                ]);
                
                $depositAccount = Account::find($request->deposit_account_id);
                $depositAccount->increment('current_balance', $request->amount);

                // Credit (Revenue)
                JournalItem::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $request->revenue_account_id,
                    'debit' => 0,
                    'credit' => $request->amount,
                    'description' => 'Revenue Recognition (Store: ' . $store->name . ')',
                ]);
                
                // Update Revenue Account (Optional tracking)
                // $revenueAccount = Account::find($request->revenue_account_id);
                // $revenueAccount->increment('current_balance', $request->amount); 
            });

            return redirect()->route('stores.show', $store)->with('success', 'Income recorded successfully.');
        } catch (\Exception $e) {
            Log::error('Store Income Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to record income: ' . $e->getMessage())->withInput();
        }
    }

    // Transfer
    public function createTransfer(Store $store)
    {
        $store->load('accounts');
        // Destination options
        $stores = Store::where('id', '!=', $store->id)->with('accounts')->get(); // Other stores
        $warehouses = Warehouse::with('accounts')->get();
        
        return view('stores.finance.transfer', compact('store', 'stores', 'warehouses'));
    }

    public function storeTransfer(Request $request, Store $store)
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

        // Verify source account belongs to store
        if(!$store->accounts->contains($request->source_account_id)) {
            return back()->with('error', 'Source account does not belong to this store.')->withInput();
        }

        try {
            DB::transaction(function () use ($request, $store) {
                $amount = $request->amount;
                
                $journalEntry = JournalEntry::create([
                    'entry_date' => $request->transfer_date,
                    'reference_number' => 'TRF-STR-' . time(),
                    'description' => $request->description ?? 'Store Transfer Out',
                    'source_type' => 'store_transfer',
                    'created_by' => auth()->id(),
                ]);

                // Credit Source (Decrease Store Balance)
                JournalItem::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $request->source_account_id,
                    'credit' => $amount,
                    'debit' => 0,
                    'description' => 'Transfer Out to ' . ucfirst($request->destination_type),
                ]);

                $sourceAccount = Account::find($request->source_account_id);
                $sourceAccount->decrement('current_balance', $amount);

                // Debit Destination (Increase Other Balance)
                JournalItem::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $request->destination_account_id,
                    'debit' => $amount,
                    'credit' => 0,
                    'description' => 'Transfer In from ' . $store->name,
                ]);

                $destAccount = Account::find($request->destination_account_id);
                $destAccount->increment('current_balance', $amount);
            });

            return redirect()->route('stores.show', $store)->with('success', 'Transfer sent successfully.');
        } catch (\Exception $e) {
            Log::error('Store Transfer Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to transfer: ' . $e->getMessage())->withInput();
        }
    }
}
