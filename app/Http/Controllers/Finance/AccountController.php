<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalItem;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::orderBy('code')->get();
        return view('finance.accounts.index', compact('accounts'));
    }

    public function show(Account $account)
    {
        return $this->ledger($account);
    }

    public function ledger(Account $account)
    {
        $items = JournalItem::where('account_id', $account->id)
            ->with('journalEntry')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('finance.accounts.ledger', compact('account', 'items'));
    }

    public function create()
    {
        return view('finance.accounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:accounts,code',
            'name' => 'required',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'category' => 'nullable',
            'initial_balance' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $account = Account::create([
                'code' => $validated['code'],
                'name' => $validated['name'],
                'type' => $validated['type'],
                'category' => $validated['category'],
                'current_balance' => $validated['initial_balance'] ?? 0,
            ]);

            if (!empty($validated['initial_balance']) && $validated['initial_balance'] > 0) {
                // Find or create Opening Balance Equity account
                $obe = Account::where('code', '3000-OBE')->first();
                if (!$obe) {
                    $obe = Account::create([
                        'code' => '3000-OBE',
                        'name' => 'Opening Balance Equity',
                        'type' => 'equity',
                        'current_balance' => 0,
                    ]);
                }

                $journalEntry = JournalEntry::create([
                    'entry_date' => now(),
                    'reference_number' => 'OB-' . time(),
                    'description' => 'Opening balance for ' . $account->name,
                    'source_type' => 'system',
                    'created_by' => auth()->id(),
                ]);

                // Determine debit/credit based on account type
                $isAssetOrExpense = in_array($account->type, ['asset', 'expense']);
                
                // Primary Account
                JournalItem::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $account->id,
                    'debit' => $isAssetOrExpense ? $validated['initial_balance'] : 0,
                    'credit' => $isAssetOrExpense ? 0 : $validated['initial_balance'],
                    'description' => 'Opening Balance',
                ]);

                // Offset Account (OBE)
                JournalItem::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $obe->id,
                    'debit' => $isAssetOrExpense ? 0 : $validated['initial_balance'],
                    'credit' => $isAssetOrExpense ? $validated['initial_balance'] : 0,
                    'description' => 'Opening Balance Offset',
                ]);

                // Update OBE balance
                // Equity balance = credits - debits
                $obe->increment('current_balance', $isAssetOrExpense ? $validated['initial_balance'] : -$validated['initial_balance']);
            }
        });

        return redirect()->route('finance.accounts.index')
            ->with('success', 'Account created successfully.');
    }
}
