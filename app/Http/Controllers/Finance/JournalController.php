<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\JournalEntry;
use App\Models\JournalItem;
use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JournalController extends Controller
{
    public function index()
    {
        $entries = JournalEntry::with('items.account')->latest()->get();
        return view('finance.journals.index', compact('entries'));
    }

    public function create()
    {
        $accounts = Account::where('is_active', true)->orderBy('code')->get();
        return view('finance.journals.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'entry_date' => 'required|date',
            'description' => 'required',
            'items' => 'required|array|min:2',
            'items.*.account_id' => 'required|exists:accounts,id',
            'items.*.debit' => 'nullable|numeric|min:0',
            'items.*.credit' => 'nullable|numeric|min:0',
        ]);

        $totalDebit = collect($request->items)->sum('debit');
        $totalCredit = collect($request->items)->sum('credit');

        if (abs($totalDebit - $totalCredit) > 0.01) {
            return back()->withErrors(['items' => 'Journal entry is not balanced. Debits and Credits must be equal.'])->withInput();
        }

        DB::transaction(function() use ($validated, $request) {
            $entry = JournalEntry::create([
                'entry_date' => $validated['entry_date'],
                'description' => $validated['description'],
                'created_by' => auth()->id() ?? User::first()->id, // Fallback if no auth
                'source_type' => 'manual',
            ]);

            foreach ($validated['items'] as $item) {
                if (($item['debit'] ?? 0) == 0 && ($item['credit'] ?? 0) == 0) continue;

                JournalItem::create([
                    'journal_entry_id' => $entry->id,
                    'account_id' => $item['account_id'],
                    'debit' => $item['debit'] ?? 0,
                    'credit' => $item['credit'] ?? 0,
                    'description' => $validated['description'],
                ]);

                // Update account balance
                $account = Account::find($item['account_id']);
                $account->current_balance = $account->calculateBalance();
                $account->save();
            }
        });

        return redirect()->route('finance.journals.index')->with('success', 'Journal entry recorded.');
    }
}
