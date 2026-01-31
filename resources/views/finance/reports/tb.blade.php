@extends('layouts.app')

@section('title', 'Trial Balance')

@section('content')
<div class="content-wrapper bg-white min-h-screen pb-20 p-12">
    <!-- Header -->
    <div class="max-w-4xl mx-auto mb-16 border-b-4 border-slate-900 pb-12">
        <h1 class="text-6xl font-black text-slate-900 tracking-tighter uppercase italic">Trial Balance</h1>
        <p class="text-slate-400 font-black text-lg mt-4 tracking-widest uppercase ml-1">Universal Consistency Check • As of {{ $date }}</p>
    </div>

    <!-- Table -->
    <div class="max-w-4xl mx-auto bg-white rounded-[3rem] border border-slate-200 shadow-xl overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-900 text-white">
                    <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.3em]">Account Code & Name</th>
                    <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.3em] text-right">Debit</th>
                    <th class="px-10 py-6 text-[10px] font-black uppercase tracking-[0.3em] text-right">Credit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 italic">
                @foreach($accounts as $account)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-10 py-6">
                        <div class="flex flex-col">
                            <span class="font-black text-slate-400 text-xs">{{ $account->code }}</span>
                            <span class="font-bold text-slate-800 text-lg">{{ $account->name }}</span>
                        </div>
                    </td>
                    <td class="px-10 py-6 text-right">
                        @if($account->type === 'asset' || $account->type === 'expense')
                            <span class="font-black text-slate-900 text-xl">RP {{ number_format($account->current_balance, 0, ',', '.') }}</span>
                        @else
                            <span class="text-slate-200">-</span>
                        @endif
                    </td>
                    <td class="px-10 py-6 text-right">
                        @if($account->type !== 'asset' && $account->type !== 'expense')
                            <span class="font-black text-slate-900 text-xl">RP {{ number_format($account->current_balance, 0, ',', '.') }}</span>
                        @else
                            <span class="text-slate-200">-</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-slate-50 border-t-4 border-slate-900">
                    <td class="px-10 py-8 font-black text-2xl uppercase tracking-tighter italic text-slate-900">Aggregate Totals</td>
                    <td class="px-10 py-8 text-right font-black text-3xl text-slate-900">RP {{ number_format($totalDebit, 0, ',', '.') }}</td>
                    <td class="px-10 py-8 text-right font-black text-3xl text-slate-900">RP {{ number_format($totalCredit, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    @if(abs($totalDebit - $totalCredit) < 0.01)
    <div class="max-w-4xl mx-auto mt-12 flex items-center justify-center gap-4 py-8 bg-emerald-50 rounded-[2.5rem] border border-emerald-100 shadow-xl shadow-emerald-50">
        <div class="p-3 bg-white rounded-full text-emerald-500 shadow-sm animate-bounce">
            <i data-feather="check" class="w-6 h-6"></i>
        </div>
        <span class="text-emerald-700 font-extrabold text-lg uppercase tracking-widest italic">Ledger Integrity Verified: Perfectly Balanced</span>
    </div>
    @else
    <div class="max-w-4xl mx-auto mt-12 flex items-center justify-center gap-4 py-8 bg-rose-50 rounded-[2.5rem] border border-rose-100 shadow-xl shadow-rose-50">
        <div class="p-3 bg-white rounded-full text-rose-500 shadow-sm">
            <i data-feather="alert-triangle" class="w-6 h-6"></i>
        </div>
        <span class="text-rose-700 font-extrabold text-lg uppercase tracking-widest italic">Discrepancy Detected: System Imbalance of RP {{ number_format(abs($totalDebit - $totalCredit), 0, ',', '.') }}</span>
    </div>
    @endif
</div>
@endsection
