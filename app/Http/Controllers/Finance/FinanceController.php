<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalItem;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function index()
    {
        $stats = [
            'cash_balance' => Account::where('category', 'cash')->orWhere('category', 'bank')->sum('current_balance'),
            'total_ar' => Account::where('category', 'receivable')->sum('current_balance'),
            'total_ap' => Account::where('category', 'payable')->sum('current_balance'),
            'month_revenue' => JournalItem::whereHas('account', function($q) { $q->where('type', 'revenue'); })
                ->where('created_at', '>=', now()->startOfMonth())
                ->sum('credit'),
            'month_expense' => JournalItem::whereHas('account', function($q) { $q->where('type', 'expense'); })
                ->where('created_at', '>=', now()->startOfMonth())
                ->sum('debit'),
        ];

        $recentEntries = JournalEntry::with('items.account')->latest()->take(10)->get();
        
        $receivables = Invoice::where('payment_status', '!=', 'paid')
            ->where('status', 'approved')
            ->orderBy('due_date')
            ->take(5)
            ->get();

        return view('finance.index', compact('stats', 'recentEntries', 'receivables'));
    }

    public function cashManagement()
    {
        $bankAccounts = Account::where('category', 'bank')->with('bankAccount')->get();
        $cashAccounts = Account::where('category', 'cash')->get();
        
        return view('finance.cash', compact('bankAccounts', 'cashAccounts'));
    }
}
