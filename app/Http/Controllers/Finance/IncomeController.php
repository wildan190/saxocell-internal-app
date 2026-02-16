<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalItem;
use App\Models\Store;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IncomeController extends Controller
{
    public function create(Request $request)
    {
        $stores = Store::with('accounts')->get();
        $warehouses = Warehouse::with('accounts')->get();
        // Assuming there is a revenue account available, or user can select one.
        // For simplicity, we might just list all 'Revenue' type accounts or allow a generic one.
        $revenueAccounts = Account::where('type', 'revenue')->orWhere('type', 'income')->get(); // Adjust based on Account types
        
        $prefill = [
            'type' => $request->query('prefill_type'),
            'id' => $request->query('prefill_id'),
        ];
        
        return view('finance.income.create', compact('stores', 'warehouses', 'revenueAccounts', 'prefill'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'income_date' => 'required|date',
            'description' => 'required|string',
            'revenue_account_id' => 'required|exists:accounts,id', // Where the income is categorized (Credit)
            'items' => 'required|array|min:1',
            'items.*.deposit_account_id' => 'required|exists:accounts,id', // Where the money goes (Debit: Cash/Bank)
            'items.*.amount' => 'required|numeric|min:0.01',
        ]);

        try {
            DB::transaction(function () use ($request) {
                
                // Create Journal Entry
                $journalEntry = JournalEntry::create([
                    'entry_date' => $request->income_date,
                    'reference_number' => 'INC-' . time(),
                    'description' => $request->description,
                    'source_type' => 'manual_income',
                    'created_by' => auth()->id(),
                ]);

                $totalAmount = 0;

                // Debbie (Assets increase) - Split Payments (Cash/Bank)
                foreach ($request->items as $item) {
                    $amount = $item['amount'];
                    $totalAmount += $amount;

                    JournalItem::create([
                        'journal_entry_id' => $journalEntry->id,
                        'account_id' => $item['deposit_account_id'],
                        'debit' => $amount, // Increase Asset
                        'credit' => 0,
                        'description' => 'Income Deposit',
                    ]);

                    // Update balance
                    $depositAccount = Account::find($item['deposit_account_id']);
                    $depositAccount->increment('current_balance', $amount);
                }

                // Credit (Revenue increases)
                JournalItem::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $request->revenue_account_id,
                    'debit' => 0,
                    'credit' => $totalAmount, // Increase Revenue
                    'description' => 'Revenue Recognition',
                ]);
                
                 // Update Revenue Account balance (Revenue effectively increases with credit, but logic depends on account type)
                 // Revenue accounts usually have Credit balance. Increasing Credit increases "balance".
                $revenueAccount = Account::find($request->revenue_account_id);
                // Simple logic: for revenue, we might just track it. If we want to update a balance field:
                $revenueAccount->increment('current_balance', $totalAmount);

            });

            if ($request->has('redirect_to')) {
                return redirect($request->input('redirect_to'))->with('success', 'Income recorded successfully.');
            }

            return redirect()->route('finance.index')->with('success', 'Income recorded successfully.');
        } catch (\Exception $e) {
            Log::error('Income Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to record income: ' . $e->getMessage())->withInput();
        }
    }
}
