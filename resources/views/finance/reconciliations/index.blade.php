@extends('layouts.app')

@section('title', 'Bank Reconciliation')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div>
            <h1 class="text-5xl font-black text-slate-900 tracking-tight">Bank Reconciliation</h1>
            <p class="text-slate-500 mt-3 font-medium text-lg">Compare your bank statements with accounting records.</p>
        </div>
        
        <a href="{{ route('finance.reconciliations.create') }}" class="flex items-center gap-3 px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-[2rem] font-black transition-all active:scale-95 shadow-xl shadow-blue-200">
            <i data-feather="plus" class="w-5 h-5"></i> New Reconciliation
        </a>
    </div>

    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Bank Account</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Statement Date</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Closing Balance</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($reconciliations as $rec)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-10 py-6">
                            <span class="font-black text-slate-800 text-lg">{{ $rec->account->name }}</span>
                            <p class="text-[10px] font-bold text-slate-400 uppercase">{{ $rec->account->code }}</p>
                        </td>
                        <td class="px-10 py-6 text-center">
                            <span class="px-4 py-2 bg-slate-100 text-slate-500 rounded-xl font-black text-xs">
                                {{ $rec->statement_date->format('M d, Y') }}
                            </span>
                        </td>
                        <td class="px-10 py-6 text-right font-black text-slate-900 text-xl">
                            RP {{ number_format($rec->closing_balance, 0, ',', '.') }}
                        </td>
                        <td class="px-10 py-6 text-center">
                            @if($rec->status === 'completed')
                                <span class="px-4 py-2 bg-emerald-100 text-emerald-600 rounded-xl font-black text-xs uppercase tracking-widest">Completed</span>
                            @else
                                <span class="px-4 py-2 bg-amber-100 text-amber-600 rounded-xl font-black text-xs uppercase tracking-widest">Draft</span>
                            @endif
                        </td>
                        <td class="px-10 py-6 text-center">
                            <a href="{{ route('finance.reconciliations.show', $rec->id) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-900 hover:bg-black text-white rounded-xl font-black text-xs uppercase tracking-widest transition-all active:scale-95">
                                <i data-feather="{{ $rec->status === 'completed' ? 'eye' : 'edit-3' }}" class="w-4 h-4"></i>
                                {{ $rec->status === 'completed' ? 'View' : 'Continue' }}
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-10 py-12 text-center text-slate-400 font-bold italic">No reconciliations found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reconciliations->hasPages())
        <div class="px-10 py-6 border-t border-slate-50 bg-slate-50/30">
            {{ $reconciliations->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
