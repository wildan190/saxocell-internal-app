<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\JournalItem;
use Illuminate\Http\Request;

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
        ]);

        Account::create($validated);

        return redirect()->route('finance.accounts.index')
            ->with('success', 'Account created successfully.');
    }
}
