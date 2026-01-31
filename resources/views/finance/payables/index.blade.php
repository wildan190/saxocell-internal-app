@extends('layouts.app')

@section('title', 'Accounts Payable')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div>
            <h1 class="text-5xl font-black text-slate-900 tracking-tight">Payables Ledger</h1>
            <p class="text-slate-500 mt-3 font-medium text-lg">Manage and settle outstanding supplier invoices.</p>
        </div>
    </div>

    <!-- Payables List -->
    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Supplier</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Invoice #</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Due Date</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Amount Due</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($invoices as $inv)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-10 py-6">
                            <span class="font-black text-slate-800 text-lg">{{ $inv->supplier->name }}</span>
                        </td>
                        <td class="px-10 py-6">
                            <span class="font-bold text-blue-600">{{ $inv->invoice_number }}</span>
                        </td>
                        <td class="px-10 py-6 text-center">
                            @php
                                $isOverdue = $inv->due_date && $inv->due_date->isPast();
                            @endphp
                            <span class="px-4 py-2 {{ $isOverdue ? 'bg-rose-50 text-rose-500' : 'bg-slate-100 text-slate-500' }} rounded-xl font-black text-xs">
                                {{ $inv->due_date ? $inv->due_date->format('M d, Y') : 'Immediately' }}
                            </span>
                        </td>
                        <td class="px-10 py-6 text-right font-black text-slate-900 text-xl">
                            RP {{ number_format($inv->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-10 py-6 text-center">
                            @if($inv->approved_at)
                                <a href="{{ route('finance.payments.create', $inv->id) }}" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-black text-xs uppercase tracking-widest transition-all">
                                    Record Payment
                                </a>
                            @else
                                <a href="{{ route('invoices.show', $inv->id) }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-black text-xs uppercase tracking-widest transition-all">
                                    Approve & Pay
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-10 py-12 text-center text-slate-400 font-bold italic">No outstanding payables detected</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
