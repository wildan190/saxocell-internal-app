<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function payables()
    {
        $invoices = Invoice::where('payment_status', '!=', 'paid')
            ->whereIn('status', ['approved', 'matched'])
            ->with('supplier')
            ->get();
            
        return view('finance.payables.index', compact('invoices'));
    }

    public function create(Invoice $invoice)
    {
        $cashAccounts = Account::whereIn('category', ['cash', 'bank'])->get();
        return view('finance.payables.create', compact('invoice', 'cashAccounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'account_id' => 'required|exists:accounts,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required',
            'reference_number' => 'nullable',
            'notes' => 'nullable',
        ]);

        $invoice = Invoice::findOrFail($validated['invoice_id']);

        DB::transaction(function() use ($validated, $invoice) {
            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'account_id' => $validated['account_id'],
                'payment_date' => $validated['payment_date'],
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'reference_number' => $validated['reference_number'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id() ?? User::first()->id,
            ]);

            // Create Journal Entry
            // Debit: Accounts Payable (Liability decrease)
            // Credit: Cash/Bank (Asset decrease)
            $apAccount = Account::where('category', 'payable')->first();
            
            if (!$apAccount) {
                throw new \Exception('Accounts Payable system account is not configured. Please check your Chart of Accounts.');
            }

            $cashAccount = Account::find($validated['account_id']);

            $entry = JournalEntry::create([
                'entry_date' => $validated['payment_date'],
                'description' => "Payment for Invoice #{$invoice->invoice_number}",
                'source_type' => 'payment',
                'source_id' => $payment->id,
                'created_by' => $payment->created_by,
            ]);

            JournalItem::create([
                'journal_entry_id' => $entry->id,
                'account_id' => $apAccount->id,
                'debit' => $validated['amount'],
                'credit' => 0,
                'description' => $entry->description,
            ]);

            JournalItem::create([
                'journal_entry_id' => $entry->id,
                'account_id' => $cashAccount->id,
                'debit' => 0,
                'credit' => $validated['amount'],
                'description' => $entry->description,
            ]);

            // Update Account Balances
            $apAccount->current_balance = $apAccount->calculateBalance();
            $apAccount->save();
            $cashAccount->current_balance = $cashAccount->calculateBalance();
            $cashAccount->save();

            // Update Invoice Status
            $invoice->payment_status = 'paid'; // Simple logic for now, could be partial
            $invoice->save();
        });

        return redirect()->route('finance.payables')->with('success', 'Payment recorded successfully.');
    }
}
