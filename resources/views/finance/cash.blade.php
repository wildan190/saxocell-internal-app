@extends('layouts.app')

@section('title', 'Cash & Bank Management')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div>
            <span class="text-blue-600 font-bold text-xs uppercase tracking-wider mb-2 block">Liquidity & Reserves</span>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Cash Management</h1>
            <p class="text-slate-500 mt-2 font-medium text-sm">Monitor and manage your bank accounts and cash on hand.</p>
        </div>
        
        <div class="flex gap-4">
            <a href="{{ route('finance.transfers.create') }}" class="flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm transition-all active:scale-95 shadow-lg shadow-blue-200/50">
                <i data-feather="repeat" class="w-4 h-4"></i> Transfer Funds
            </a>
        </div>
    </div>

    <!-- Liquid Assets Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        @php
            $totalBank = $bankAccounts->sum('current_balance');
            $totalCash = $cashAccounts->sum('current_balance');
        @endphp
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <h3 class="text-slate-400 font-bold text-[10px] uppercase tracking-widest mb-1">Total Liquid Assets</h3>
            <p class="text-3xl font-black text-slate-900 italic">RP {{ number_format($totalBank + $totalCash, 0, ',', '.') }}</p>
        </div>
        <div class="bg-blue-600 p-8 rounded-[2.5rem] text-white shadow-xl shadow-blue-100">
            <h3 class="text-blue-100 font-bold text-[10px] uppercase tracking-widest mb-1 text-white/60">Bank Balances</h3>
            <p class="text-3xl font-black italic">RP {{ number_format($totalBank, 0, ',', '.') }}</p>
        </div>
        <div class="bg-emerald-600 p-8 rounded-[2.5rem] text-white shadow-xl shadow-emerald-100">
            <h3 class="text-emerald-100 font-bold text-[10px] uppercase tracking-widest mb-1 text-white/60">Cash on Hand</h3>
            <p class="text-3xl font-black italic">RP {{ number_format($totalCash, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Bank Accounts -->
        <div class="space-y-6">
            <div class="flex items-center justify-between mb-2 px-4">
                <h3 class="text-lg font-black text-slate-800 uppercase tracking-tighter">Bank Accounts</h3>
                <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[10px] font-black uppercase">{{ $bankAccounts->count() }} Accounts</span>
            </div>
            
            <div class="grid grid-cols-1 gap-4">
                @forelse($bankAccounts as $account)
                <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm hover:shadow-xl transition-all group">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all">
                                <i data-feather="briefcase" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h4 class="font-black text-slate-900 text-lg">{{ $account->name }}</h4>
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">{{ $account->bankAccount->bank_name ?? 'Commercial Bank' }} • {{ $account->bankAccount->account_number ?? $account->code }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                             <p class="text-xl font-black text-slate-900 italic">RP {{ number_format($account->current_balance, 0, ',', '.') }}</p>
                             <a href="{{ route('finance.accounts.ledger', $account->id) }}" class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline mt-1 inline-block">View Ledger</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-[2rem] p-12 text-center border-2 border-dashed border-slate-100">
                    <p class="text-slate-400 font-bold italic">No bank accounts registered</p>
                    <a href="{{ route('finance.accounts.create') }}" class="text-blue-600 font-black text-xs uppercase tracking-widest mt-4 inline-block hover:underline">Add First Bank Account</a>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Cash Accounts -->
        <div class="space-y-6">
            <div class="flex items-center justify-between mb-2 px-4">
                <h3 class="text-lg font-black text-slate-800 uppercase tracking-tighter">Cash on Hand</h3>
                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black uppercase">{{ $cashAccounts->count() }} Accounts</span>
            </div>

            <div class="grid grid-cols-1 gap-4">
                @forelse($cashAccounts as $account)
                <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm hover:shadow-xl transition-all group">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                                <i data-feather="dollar-sign" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h4 class="font-black text-slate-900 text-lg">{{ $account->name }}</h4>
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Physical Cash • {{ $account->code }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                             <p class="text-xl font-black text-slate-900 italic">RP {{ number_format($account->current_balance, 0, ',', '.') }}</p>
                             <a href="{{ route('finance.accounts.ledger', $account->id) }}" class="text-[10px] font-black text-emerald-600 uppercase tracking-widest hover:underline mt-1 inline-block">View Ledger</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-[2rem] p-12 text-center border-2 border-dashed border-slate-100">
                    <p class="text-slate-400 font-bold italic">No cash accounts registered</p>
                    <a href="{{ route('finance.accounts.create') }}" class="text-blue-600 font-black text-xs uppercase tracking-widest mt-4 inline-block hover:underline">Add Cash Account</a>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
