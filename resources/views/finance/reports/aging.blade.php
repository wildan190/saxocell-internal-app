@extends('layouts.app')

@section('title', 'Accounts Payable Aging')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20 p-12 italic">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-16">
            <h1 class="text-6xl font-black text-slate-900 tracking-tighter uppercase">Payables Aging Audit</h1>
            <p class="text-slate-500 font-bold text-lg mt-4 tracking-wider uppercase flex items-center gap-3">
                <span class="w-12 h-1 bg-rose-500"></span> Debt Risk Assessment • Real-time Distribution
            </p>
        </div>

        <!-- Summary Boxes -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-16">
            @foreach($aging as $key => $data)
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl transition-all group">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 block">{{ $data['label'] }}</span>
                <p class="text-2xl font-black text-slate-900 mb-2">RP {{ number_format($data['total'], 0, ',', '.') }}</p>
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold text-slate-500">{{ $data['count'] }} Invoices</span>
                    <span class="w-2 h-2 rounded-full {{ $data['total'] > 0 ? ($key == 'current' ? 'bg-emerald-500' : 'bg-rose-500 animate-pulse') : 'bg-slate-200' }}"></span>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Detail Table -->
        <div class="bg-white rounded-[3rem] border border-slate-200 shadow-2xl overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900 text-white">
                        <th class="px-10 py-6 text-xs font-bold uppercase tracking-[0.3em]">Supplier & Reference</th>
                        <th class="px-10 py-6 text-xs font-bold uppercase tracking-[0.3em] text-center">Due Date</th>
                        <th class="px-10 py-6 text-xs font-bold uppercase tracking-[0.3em] text-center">Days Overdue</th>
                        <th class="px-10 py-6 text-xs font-bold uppercase tracking-[0.3em] text-right">Amount Payable</th>
                        <th class="px-10 py-6 text-xs font-bold uppercase tracking-[0.3em] text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($invoices as $inv)
                    @php
                        $days = $inv->due_date && $inv->due_date->isPast() ? $inv->due_date->diffInDays(now()) : 0;
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-10 py-6">
                            <div class="flex flex-col">
                                <span class="font-black text-slate-900 text-lg">{{ $inv->supplier->name }}</span>
                                <span class="font-bold text-blue-600 text-xs">{{ $inv->invoice_number }}</span>
                            </div>
                        </td>
                        <td class="px-10 py-6 text-center">
                            <span class="font-bold text-slate-500">{{ $inv->due_date ? $inv->due_date->format('M d, Y') : 'Immediately' }}</span>
                        </td>
                        <td class="px-10 py-6 text-center">
                            @if($days > 0)
                                <span class="px-4 py-2 bg-rose-50 text-rose-600 rounded-xl font-black text-sm">{{ $days }} Days</span>
                            @else
                                <span class="font-bold text-slate-300">0</span>
                            @endif
                        </td>
                        <td class="px-10 py-6 text-right font-black text-slate-900 text-2xl">
                            RP {{ number_format($inv->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-10 py-6 text-center">
                            @if($inv->approved_at)
                                <a href="{{ route('finance.payments.create', $inv->id) }}" class="p-3 bg-emerald-50 text-emerald-600 rounded-2xl hover:bg-emerald-600 hover:text-white transition-all inline-block shadow-sm">
                                    <i data-feather="dollar-sign" class="w-5 h-5"></i>
                                </a>
                            @else
                                <span class="text-xs font-bold text-slate-200 uppercase tracking-wider">Pending Post</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-10 py-20 text-center">
                            <p class="text-slate-400 font-bold text-xl italic mb-4">The debt ledger is clean.</p>
                            <span class="px-8 py-3 bg-emerald-50 text-emerald-600 rounded-full font-black text-xs uppercase tracking-[0.3em]">Operational Harmony Verified</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
