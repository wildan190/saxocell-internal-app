<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('finance.reports.index');
    }

    public function profitAndLoss(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfYear()->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());

        $revenue = Account::where('type', 'revenue')->get();
        $expenses = Account::where('type', 'expense')->get();

        $totalRevenue = $revenue->sum('current_balance');
        $totalExpense = $expenses->sum('current_balance');
        $netProfit = $totalRevenue - $totalExpense;

        return view('finance.reports.pl', compact('revenue', 'expenses', 'totalRevenue', 'totalExpense', 'netProfit', 'startDate', 'endDate'));
    }

    public function balanceSheet(Request $request)
    {
        $date = $request->get('date', now()->toDateString());

        $assets = Account::where('type', 'asset')->get();
        $liabilities = Account::where('type', 'liability')->get();
        $equity = Account::where('type', 'equity')->get();

        $totalAssets = $assets->sum('current_balance');
        $totalLiabilities = $liabilities->sum('current_balance');
        $totalEquity = $equity->sum('current_balance');

        return view('finance.reports.bs', compact('assets', 'liabilities', 'equity', 'totalAssets', 'totalLiabilities', 'totalEquity', 'date'));
    }

    public function trialBalance(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $accounts = Account::orderBy('code')->get();
        
        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($accounts as $account) {
            if ($account->type === 'asset' || $account->type === 'expense') {
                $totalDebit += $account->current_balance;
            } else {
                $totalCredit += $account->current_balance;
            }
        }

        return view('finance.reports.tb', compact('accounts', 'totalDebit', 'totalCredit', 'date'));
    }

    public function cashflow(Request $request)
    {
        // Simple Cashflow: Starting Balance + Cash In - Cash Out
        $cashAccounts = Account::whereIn('category', ['cash', 'bank'])->get();
        $totalCash = $cashAccounts->sum('current_balance');
        
        // This is a placeholder for a more complex cashflow forecast
        return view('finance.reports.cashflow', compact('cashAccounts', 'totalCash'));
    }

    public function payablesAging()
    {
        $invoices = \App\Models\Invoice::where('payment_status', '!=', 'paid')
            ->where('status', 'approved')
            ->with('supplier')
            ->get();

        $aging = [
            'current' => ['label' => 'Current (Not Overdue)', 'total' => 0, 'count' => 0],
            '1_30' => ['label' => '1 - 30 Days Overdue', 'total' => 0, 'count' => 0],
            '31_60' => ['label' => '31 - 60 Days Overdue', 'total' => 0, 'count' => 0],
            '61_90' => ['label' => '61 - 90 Days Overdue', 'total' => 0, 'count' => 0],
            'over_90' => ['label' => '90+ Days Overdue', 'total' => 0, 'count' => 0],
        ];

        foreach ($invoices as $inv) {
            $daysOverdue = 0;
            if ($inv->due_date && $inv->due_date->isPast()) {
                $daysOverdue = $inv->due_date->diffInDays(now());
            }

            if ($daysOverdue == 0) {
                $aging['current']['total'] += $inv->total_amount;
                $aging['current']['count']++;
            } elseif ($daysOverdue <= 30) {
                $aging['1_30']['total'] += $inv->total_amount;
                $aging['1_30']['count']++;
            } elseif ($daysOverdue <= 60) {
                $aging['31_60']['total'] += $inv->total_amount;
                $aging['31_60']['count']++;
            } elseif ($daysOverdue <= 90) {
                $aging['61_90']['total'] += $inv->total_amount;
                $aging['61_90']['count']++;
            } else {
                $aging['over_90']['total'] += $inv->total_amount;
                $aging['over_90']['count']++;
            }
        }

        return view('finance.reports.aging', compact('aging', 'invoices'));
    }
}
