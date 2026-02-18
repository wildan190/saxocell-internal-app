@extends('layouts.app')

@section('title', 'New Account')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20 p-8 md:p-12">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-[3rem] border border-slate-200 shadow-2xl overflow-hidden">
            <div class="p-12 bg-blue-600 text-white relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                <h1 class="text-4xl font-black italic tracking-tighter">New Ledger Node</h1>
                <p class="mt-4 font-bold text-blue-100 uppercase tracking-wider text-xs tracking-wider">Architecture • Chart of Accounts Expansion</p>
            </div>

            <form action="{{ route('finance.accounts.store') }}" method="POST" class="p-12 space-y-10">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Account Code</label>
                        <input type="text" name="code" placeholder="e.g. 1000-005" class="w-full px-8 py-5 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-black text-slate-800 transition-all outline-none" required>
                    </div>
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Account Name</label>
                        <input type="text" name="name" placeholder="e.g. Petty Cash" class="w-full px-8 py-5 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Account Type</label>
                        <select name="type" class="w-full px-8 py-5 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none appearance-none" required>
                            <option value="asset">Asset</option>
                            <option value="liability">Liability</option>
                            <option value="equity">Equity</option>
                            <option value="revenue">Revenue</option>
                            <option value="expense">Expense</option>
                        </select>
                    </div>
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Category (Optional)</label>
                        <select name="category" class="w-full px-8 py-5 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none appearance-none">
                            <option value="">None</option>
                            <option value="cash">Cash</option>
                            <option value="bank">Bank</option>
                            <option value="receivable">Account Receivable</option>
                            <option value="payable">Account Payable</option>
                            <option value="inventory">Inventory</option>
                            <option value="current_asset">Current Asset</option>
                            <option value="fixed_asset">Fixed Asset</option>
                            <option value="current_liability">Current Liability</option>
                            <option value="tax">Tax</option>
                            <option value="operating_revenue">Operating Revenue</option>
                            <option value="operating_expense">Operating Expense</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-3">
                         <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Initial Balance (Optional)</label>
                        <input type="number" name="initial_balance" step="0.01" min="0" placeholder="0.00" class="w-full px-8 py-5 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-black text-slate-800 transition-all outline-none">
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full py-6 bg-slate-900 hover:bg-black text-white rounded-[2.5rem] font-black text-xl shadow-2xl transition-all active:scale-[0.98] uppercase tracking-tighter italic">
                        Initialize Account
                    </button>
                    <div class="text-center mt-6">
                        <a href="{{ route('finance.accounts.index') }}" class="text-xs font-bold text-slate-400 uppercase tracking-[0.3em] hover:text-slate-600 transition-colors">Discard & Return to List</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
