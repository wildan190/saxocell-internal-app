@extends('layouts.app')

@section('title', 'Record Payment')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20 p-8 md:p-12">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-[3rem] border border-slate-200 shadow-2xl overflow-hidden">
            <div class="p-12 bg-emerald-600 text-white relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                <h1 class="text-4xl font-black italic tracking-tighter">Settlement Voucher</h1>
                <p class="mt-4 font-bold text-emerald-100 uppercase tracking-widest text-xs">Payment for Invoice #{{ $invoice->invoice_number }} • {{ $invoice->supplier->name }}</p>
            </div>

            <form action="{{ route('finance.payments.store') }}" method="POST" class="p-12 space-y-10">
                @csrf
                <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Payment Date</label>
                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" class="w-full px-8 py-5 bg-slate-50 border-2 border-slate-100 focus:border-emerald-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none" required>
                    </div>
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Amount to Pay</label>
                        <input type="number" step="0.01" name="amount" value="{{ $invoice->total_amount }}" class="w-full px-8 py-5 bg-slate-50 border-2 border-slate-100 focus:border-emerald-500 focus:bg-white rounded-2xl font-black text-slate-800 transition-all outline-none" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Paid From (Source Account)</label>
                        <select name="account_id" class="w-full px-8 py-5 bg-slate-50 border-2 border-slate-100 focus:border-emerald-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none appearance-none" required>
                            @foreach($cashAccounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->name }} (Available: RP {{ number_format($acc->current_balance, 0, ',', '.') }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Payment Method</label>
                        <select name="payment_method" class="w-full px-8 py-5 bg-slate-50 border-2 border-slate-100 focus:border-emerald-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none appearance-none" required>
                            <option value="transfer">Bank Transfer</option>
                            <option value="cash">Cash</option>
                            <option value="cheque">Cheque</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Transaction Link / Memo</label>
                    <input type="text" name="notes" placeholder="e.g. Settlement for electronics PO" class="w-full px-8 py-5 bg-slate-50 border-2 border-slate-100 focus:border-emerald-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none">
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full py-6 bg-slate-900 hover:bg-black text-white rounded-[2.5rem] font-black text-xl shadow-2xl transition-all active:scale-[0.98] uppercase tracking-tighter italic">
                        Confirm & Record Transaction
                    </button>
                    <p class="text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-6">This action will automatically generate journal entries in the General Ledger</p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
