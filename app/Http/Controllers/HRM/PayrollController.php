<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalItem;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    public function index()
    {
        $payrolls = Payroll::with('employee.department')->latest()->paginate(15);
        return view('hrm.payroll.index', compact('payrolls'));
    }

    public function generate(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        $employees = Employee::where('status', 'active')->with(['salaryComponents.component', 'overtimeRecords'])->get();

        foreach ($employees as $employee) {
            $existing = Payroll::where('employee_id', $employee->id)
                ->where('month', $month)
                ->where('year', $year)
                ->first();

            if (!$existing) {
                // Calculate components
                $allowances = $employee->salaryComponents
                    ->where('component.type', 'allowance')
                    ->sum('amount');
                
                $deductions = $employee->salaryComponents
                    ->where('component.type', 'deduction')
                    ->sum('amount');

                // Calculate overtime
                $overtime = \App\Models\OvertimeRecord::where('employee_id', $employee->id)
                    ->where('status', 'approved')
                    ->whereMonth('date', $month)
                    ->whereYear('date', $year)
                    ->sum('total_amount');

                $totalAllowances = $allowances + $overtime;
                $netSalary = $employee->base_salary + $totalAllowances - $deductions;

                Payroll::create([
                    'employee_id' => $employee->id,
                    'month' => $month,
                    'year' => $year,
                    'basic_salary' => $employee->base_salary,
                    'allowances' => $totalAllowances,
                    'deductions' => $deductions,
                    'net_salary' => $netSalary,
                    'status' => 'draft',
                    'created_by' => auth()->id() ?? \App\Models\User::first()->id,
                ]);
            }
        }

        return back()->with('success', "Payroll generated for {$month}/{$year}.");
    }

    public function approve(Request $request, Payroll $payroll)
    {
        if ($payroll->status !== 'draft') {
            return back()->with('error', 'Only draft payrolls can be approved.');
        }

        DB::transaction(function() use ($payroll) {
            // Integrate with Finance Module
            // Debit: Operating Expense (Payroll)
            // Credit: Cash/Bank (Payment)

            $expenseAccount = Account::where('category', 'operating_expense')->first();
            $cashAccount = Account::where('category', 'cash')->first();

            if (!$expenseAccount || !$cashAccount) {
                throw new \Exception('Expense or Cash account not properly configured.');
            }

            $entry = JournalEntry::create([
                'entry_date' => now(),
                'description' => "Payroll payment for {$payroll->employee->full_name} - {$payroll->month}/{$payroll->year}",
                'source_type' => 'payroll',
                'source_id' => $payroll->id,
                'created_by' => auth()->id() ?? \App\Models\User::first()->id,
            ]);

            JournalItem::create([
                'journal_entry_id' => $entry->id,
                'account_id' => $expenseAccount->id,
                'debit' => $payroll->net_salary,
                'credit' => 0,
                'description' => $entry->description,
            ]);

            JournalItem::create([
                'journal_entry_id' => $entry->id,
                'account_id' => $cashAccount->id,
                'debit' => 0,
                'credit' => $payroll->net_salary,
                'description' => $entry->description,
            ]);

            // Update balances
            $expenseAccount->current_balance = $expenseAccount->calculateBalance();
            $expenseAccount->save();
            $cashAccount->current_balance = $cashAccount->calculateBalance();
            $cashAccount->save();

            $payroll->update([
                'status' => 'paid',
                'paid_at' => now(),
                'journal_entry_id' => $entry->id
            ]);
        });

        return back()->with('success', 'Payroll approved and payment recorded in General Ledger.');
    }

    public function payslip(Payroll $payroll)
    {
        return view('hrm.payroll.payslip', compact('payroll'));
    }
}
