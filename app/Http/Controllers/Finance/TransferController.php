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

class TransferController extends Controller
{
    public function create(Request $request)
    {
        $stores = Store::with('accounts')->get();
        $warehouses = Warehouse::with('accounts')->get();
        
        $prefill = [
            'source_type' => $request->query('prefill_source_type'),
            'source_id' => $request->query('prefill_source_id'),
            'dest_type' => $request->query('prefill_dest_type'), // Optional, usually we just set source
        ];
        
        return view('finance.transfers.create', compact('stores', 'warehouses', 'prefill'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'transfer_date' => 'required|date',
            'description' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.source_account_id' => 'required|exists:accounts,id',
            'items.*.destination_account_id' => 'required|exists:accounts,id',
            'items.*.amount' => 'required|numeric|min:0.01',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $totalAmount = 0;
                
                // Create Journal Entry
                $journalEntry = JournalEntry::create([
                    'entry_date' => $request->transfer_date,
                    'reference_number' => 'TRF-' . time(),
                    'description' => $request->description ?? 'Internal Transfer',
                    'source_type' => 'manual_transfer', // or null
                    'created_by' => auth()->id(),
                ]);

                foreach ($request->items as $item) {
                    $amount = $item['amount'];
                    
                    // Credit Source (Decrease Balance)
                    JournalItem::create([
                        'journal_entry_id' => $journalEntry->id,
                        'account_id' => $item['source_account_id'],
                        'credit' => $amount,
                        'debit' => 0,
                        'description' => 'Transfer Out',
                    ]);

                    // Debit Destination (Increase Balance)
                    JournalItem::create([
                        'journal_entry_id' => $journalEntry->id,
                        'account_id' => $item['destination_account_id'],
                        'debit' => $amount,
                        'credit' => 0,
                        'description' => 'Transfer In',
                    ]);
                    
                    // Update balances (if not handled by observers/events)
                    $sourceAccount = Account::find($item['source_account_id']);
                    $sourceAccount->decrement('current_balance', $amount);

                    $destAccount = Account::find($item['destination_account_id']);
                    $destAccount->increment('current_balance', $amount);
                }
            });

            if ($request->has('redirect_to')) {
                return redirect($request->input('redirect_to'))->with('success', 'Transfer recorded successfully.');
            }

            return redirect()->route('finance.index')->with('success', 'Transfer recorded successfully.');
        } catch (\Exception $e) {
            Log::error('Transfer Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to record transfer: ' . $e->getMessage())->withInput();
        }
    }
}
