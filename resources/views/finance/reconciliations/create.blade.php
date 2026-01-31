@extends('layouts.app')

@section('title', 'New Bank Reconciliation')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20 p-8 md:p-12">
    <div class="max-w-3xl mx-auto">
        <div class="mb-12">
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">New Reconciliation</h1>
            <p class="text-slate-500 mt-3 font-medium text-lg">Start a new bank statement comparison process.</p>
        </div>

        <div class="bg-white rounded-[3rem] border border-slate-200 shadow-2xl overflow-hidden">
            <div class="p-12 bg-blue-600 text-white relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                <h3 class="text-2xl font-black italic tracking-tighter uppercase tracking-wider text-blue-100/50">Initial Configuration</h3>
            </div>

            <form action="{{ route('finance.reconciliations.store') }}" method="POST" class="p-12 space-y-10">
                @csrf
                
                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Select Bank Account</label>
                    <select name="account_id" class="w-full px-8 py-5 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none appearance-none" required>
                        <option value="">-- Choose Account --</option>
                        @foreach($bankAccounts as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Statement Date</label>
                        <input type="date" name="statement_date" value="{{ date('Y-m-d') }}" class="w-full px-8 py-5 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none" required>
                    </div>
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Statement Ending Balance</label>
                        <input type="number" step="0.01" name="closing_balance" placeholder="0.00" class="w-full px-8 py-5 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-black text-slate-800 transition-all outline-none" required>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full py-6 bg-slate-900 hover:bg-black text-white rounded-[2.5rem] font-black text-xl shadow-2xl transition-all active:scale-[0.98] uppercase tracking-tighter italic flex items-center justify-center gap-3">
                        Start Reconciliation Process <i data-feather="chevron-right"></i>
                    </button>
                    <p class="text-center text-xs font-bold text-slate-400 uppercase tracking-[0.3em] mt-6">You will select book entries to match against this statement in the next step</p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
