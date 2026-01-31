@extends('layouts.app')

@section('title', 'General Ledger')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div>
            <h1 class="text-5xl font-black text-slate-900 tracking-tight">General Ledger</h1>
            <p class="text-slate-500 mt-3 font-medium text-lg">Comprehensive history of all financial transactions.</p>
        </div>
        
        <div class="flex gap-4">
            <a href="{{ route('finance.journals.create') }}" class="flex items-center gap-3 px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-[2rem] font-black transition-all active:scale-95 shadow-xl shadow-blue-200">
                <i data-feather="plus" class="w-5 h-5"></i> Create Entry
            </a>
        </div>
    </div>

    <!-- Journal Entries List -->
    <div class="space-y-8">
        @forelse($entries as $entry)
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden hover:shadow-xl transition-all group">
            <div class="p-8 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
                <div class="flex items-center gap-6">
                    <div class="p-4 bg-white rounded-2xl shadow-sm text-blue-600">
                        <i data-feather="book" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-800">{{ $entry->description }}</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">
                            Ref: JRNL-{{ substr($entry->id, 0, 8) }} • {{ $entry->entry_date->format('M d, Y') }} • by {{ $entry->creator->name ?? 'System' }}
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-500">
                        {{ strtoupper($entry->source_type) }}
                    </span>
                </div>
            </div>
            <div class="p-0 overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/10">
                            <th class="px-10 py-4 text-[10px] font-black text-slate-300 uppercase tracking-widest">Account</th>
                            <th class="px-10 py-4 text-[10px] font-black text-slate-300 uppercase tracking-widest text-right whitespace-nowrap">Debit</th>
                            <th class="px-10 py-4 text-[10px] font-black text-slate-300 uppercase tracking-widest text-right whitespace-nowrap">Credit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50/50">
                        @foreach($entry->items as $item)
                        <tr class="transition-colors">
                            <td class="px-10 py-4">
                                <span class="font-bold text-slate-600">{{ $item->account->code }} - {{ $item->account->name }}</span>
                            </td>
                            <td class="px-10 py-4 text-right">
                                @if($item->debit > 0)
                                    <span class="font-black text-emerald-600">RP {{ number_format($item->debit, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-slate-100">-</span>
                                @endif
                            </td>
                            <td class="px-10 py-4 text-right">
                                @if($item->credit > 0)
                                    <span class="font-black text-rose-600">RP {{ number_format($item->credit, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-slate-100">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div class="p-20 text-center bg-white rounded-[3rem] border border-dashed border-slate-200">
            <i data-feather="database" class="w-12 h-12 text-slate-200 mx-auto mb-6"></i>
            <p class="text-slate-400 font-bold italic text-lg">The general ledger is currently empty.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
