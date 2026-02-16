<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\BankReconciliation;
use App\Models\JournalItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankReconciliationController extends Controller
{
    public function index()
    {
        $reconciliations = BankReconciliation::with('account', 'creator')
            ->latest()
            ->paginate(15);
        return view('finance.reconciliations.index', compact('reconciliations'));
    }

    public function create()
    {
        $bankAccounts = Account::where('category', 'bank')->get();
        return view('finance.reconciliations.create', compact('bankAccounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'statement_date' => 'required|date',
            'closing_balance' => 'required|numeric',
        ]);

        // Get opening balance from last completed reconciliation or account's opening balance
        $lastReconciliation = BankReconciliation::where('account_id', $validated['account_id'])
            ->where('status', 'completed')
            ->latest('statement_date')
            ->first();

        $openingBalance = $lastReconciliation ? $lastReconciliation->closing_balance : 0;

        $reconciliation = BankReconciliation::create([
            'account_id' => $validated['account_id'],
            'statement_date' => $validated['statement_date'],
            'opening_balance' => $openingBalance,
            'closing_balance' => $validated['closing_balance'],
            'reconciled_balance' => $openingBalance,
            'status' => 'draft',
            'created_by' => auth()->id() ?? \App\Models\User::first()->id,
        ]);

        return redirect()->route('finance.reconciliations.show', $reconciliation)
            ->with('success', 'Reconciliation started.');
    }

    public function show(BankReconciliation $reconciliation)
    {
        $reconciliation->load('account');

        // Get unreconciled items for this account
        $unreconciledItems = JournalItem::with('journalEntry')
            ->where('account_id', $reconciliation->account_id)
            ->whereNull('bank_reconciliation_id')
            ->orderBy('created_at')
            ->get();

        // Get currently linked items
        $linkedItems = JournalItem::with('journalEntry')
            ->where('bank_reconciliation_id', $reconciliation->id)
            ->get();

        return view('finance.reconciliations.show', compact('reconciliation', 'unreconciledItems', 'linkedItems'));
    }

    public function updateItems(Request $request, BankReconciliation $reconciliation)
    {
        if ($reconciliation->status !== 'draft') {
            return response()->json(['error' => 'Reconciliation is already finalized.'], 403);
        }

        $itemIds = $request->input('item_ids', []);
        
        DB::transaction(function() use ($reconciliation, $itemIds) {
            // Unlink all currently linked items
            JournalItem::where('bank_reconciliation_id', $reconciliation->id)
                ->update(['bank_reconciliation_id' => null, 'reconciled_at' => null]);

            if (!empty($itemIds)) {
                // Link selected items
                JournalItem::whereIn('id', $itemIds)
                    ->update(['bank_reconciliation_id' => $reconciliation->id, 'reconciled_at' => now()]);
            }

            // Recalculate reconciled balance
            $totalDebit = JournalItem::where('bank_reconciliation_id', $reconciliation->id)->sum('debit');
            $totalCredit = JournalItem::where('bank_reconciliation_id', $reconciliation->id)->sum('credit');
            
            // Assets (Bank): Balance = Opening + Debit - Credit
            $reconciliation->reconciled_balance = $reconciliation->opening_balance + $totalDebit - $totalCredit;
            $reconciliation->save();
        });

        return response()->json([
            'success' => true,
            'reconciled_balance' => $reconciliation->reconciled_balance,
            'difference' => $reconciliation->closing_balance - $reconciliation->reconciled_balance
        ]);
    }

    public function finalize(BankReconciliation $reconciliation)
    {
        if (abs($reconciliation->closing_balance - $reconciliation->reconciled_balance) > 0.01) {
            return back()->with('error', 'Cannot finalize. There is still a mismatch between bank statement and books.');
        }

        $reconciliation->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return redirect()->route('finance.reconciliations.index')
            ->with('success', 'Reconciliation finalized successfully.');
    }
    public function edit(BankReconciliation $reconciliation)
    {
        if ($reconciliation->status !== 'draft') {
            return redirect()->route('finance.reconciliations.show', $reconciliation)
                ->with('error', 'Cannot edit a finalized reconciliation.');
        }
        
        return view('finance.reconciliations.edit', compact('reconciliation'));
    }

    public function update(Request $request, BankReconciliation $reconciliation)
    {
        if ($reconciliation->status !== 'draft') {
            return back()->with('error', 'Cannot update a finalized reconciliation.');
        }

        $validated = $request->validate([
            'statement_date' => 'required|date',
            'closing_balance' => 'required|numeric',
        ]);

        $reconciliation->update($validated);

        return redirect()->route('finance.reconciliations.show', $reconciliation)
            ->with('success', 'Reconciliation details updated.');
    }

    public function destroy(BankReconciliation $reconciliation)
    {
        if ($reconciliation->status !== 'draft') {
            return back()->with('error', 'Cannot delete a finalized reconciliation.');
        }

        // Unlink any items
        JournalItem::where('bank_reconciliation_id', $reconciliation->id)
            ->update(['bank_reconciliation_id' => null, 'reconciled_at' => null]);

        $reconciliation->delete();

        return redirect()->route('finance.reconciliations.index')
            ->with('success', 'Reconciliation deleted.');
    }
}
