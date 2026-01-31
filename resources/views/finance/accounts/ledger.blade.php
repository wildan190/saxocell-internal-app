@extends('layouts.app')

@section('title', 'General Ledger - ' . $account->name)

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div>
            <div class="flex items-center gap-3 mb-4">
                <a href="{{ route('finance.accounts.index') }}" class="p-3 bg-white border border-slate-100 rounded-2xl text-slate-400 hover:text-blue-600 transition-all">
                    <i data-feather="arrow-left" class="w-5 h-5"></i>
                </a>
                <span class="text-blue-600 font-black text-xs uppercase tracking-[0.3em]">Account Ledger</span>
            </div>
            <h1 class="text-5xl font-black text-slate-900 tracking-tight">{{ $account->name }}</h1>
            <p class="text-slate-500 mt-3 font-medium text-lg">Detailed transaction history for account #{{ $account->code }}.</p>
        </div>
        
        <div class="text-right">
            <h3 class="text-slate-400 font-bold text-xs uppercase tracking-widest mb-1">Current Balance</h3>
            <p class="text-5xl font-black text-slate-900">RP {{ number_format($account->current_balance, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Ledger Items -->
    <div class="bg-white rounded-[3rem] border border-slate-200/60 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Date</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Journal Ref</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Description</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Debit</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Credit</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Flow</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($items as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-10 py-6">
                            <span class="font-bold text-slate-500 text-sm">{{ $item->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="px-10 py-6">
                            <span class="font-black text-blue-600 text-xs">JRNL-{{ substr($item->journal_entry_id, 0, 8) }}</span>
                        </td>
                        <td class="px-10 py-6">
                            <span class="font-black text-slate-800 text-base">{{ $item->description }}</span>
                        </td>
                        <td class="px-10 py-6 text-right">
                            @if($item->debit > 0)
                                <span class="font-black text-slate-900">RP {{ number_format($item->debit, 0, ',', '.') }}</span>
                            @else
                                <span class="text-slate-200">-</span>
                            @endif
                        </td>
                        <td class="px-10 py-6 text-right">
                            @if($item->credit > 0)
                                <span class="font-black text-slate-900">RP {{ number_format($item->credit, 0, ',', '.') }}</span>
                            @else
                                <span class="text-slate-200">-</span>
                            @endif
                        </td>
                        <td class="px-10 py-6 text-right">
                            @php 
                                $isIncrease = ($account->type == 'asset' || $account->type == 'expense') ? $item->debit > 0 : $item->credit > 0;
                            @endphp
                            <span class="p-2 {{ $isIncrease ? 'bg-emerald-50 text-emerald-500' : 'bg-rose-50 text-rose-500' }} rounded-xl">
                                <i data-feather="{{ $isIncrease ? 'trending-up' : 'trending-down' }}" class="w-4 h-4 inline"></i>
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-10 py-12 text-center text-slate-400 font-bold italic">No transactions found for this account</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
