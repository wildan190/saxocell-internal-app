@extends('layouts.app')

@section('title', 'Accounts Payable')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Payables Ledger</h1>
            <p class="text-slate-500 mt-2 font-medium text-sm">Manage and settle outstanding supplier invoices.</p>
        </div>
    </div>

    <!-- Payables List -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Supplier</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Invoice #</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Due Date</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Amount Due</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($invoices as $inv)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-6 py-4">
                            <span class="font-semibold text-slate-700 text-sm">{{ $inv->supplier->name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-medium text-blue-600 text-sm">{{ $inv->invoice_number }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $isOverdue = $inv->due_date && $inv->due_date->isPast();
                            @endphp
                            <span class="px-3 py-1 {{ $isOverdue ? 'bg-rose-50 text-rose-500' : 'bg-slate-100 text-slate-500' }} rounded-lg font-semibold text-xs">
                                {{ $inv->due_date ? $inv->due_date->format('M d, Y') : 'Immediately' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-slate-800 text-sm">
                            RP {{ number_format($inv->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($inv->approved_at)
                                <a href="{{ route('finance.payments.create', $inv->id) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-xs transition-all shadow-sm hover:shadow-md">
                                    Record Payment
                                </a>
                            @else
                                <a href="{{ route('invoices.show', $inv->id) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold text-xs transition-all shadow-sm hover:shadow-md">
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
