@extends('layouts.app')

@section('title', 'DO Details: ' . $do->do_number)

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <a href="{{ route('home') }}">Home</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item">
        <a href="{{ route('delivery-orders.index') }}">Delivery Orders</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">{{ $do->do_number }}</div>
</nav>
@endsection

@section('content')
<div class="p-4 md:p-8 max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">{{ $do->do_number }}</h1>
            <p class="text-slate-500 mt-2 flex items-center gap-2">
                <span class="flex items-center gap-1.5 font-medium text-slate-700">
                    <i data-feather="truck" class="w-4 h-4"></i>
                    {{ $do->supplier->name }}
                </span>
                <span class="text-slate-300">•</span>
                <span class="flex items-center gap-1.5">
                    <i data-feather="calendar" class="w-4 h-4"></i>
                    Received on {{ $do->delivery_date->format('M d, Y') }}
                </span>
            </p>
        </div>
        
        <div class="flex flex-wrap gap-3">
            @php
                $hasInvoice = \App\Models\Invoice::where('purchase_order_id', $do->purchase_order_id)->exists();
            @endphp
            
            @if(!$hasInvoice)
                <a href="{{ route('invoices.create', ['po_id' => $do->purchase_order_id]) }}" class="flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-200 transition-all active:scale-95">
                    <i data-feather="send" class="w-4 h-4"></i> Send to Finance
                </a>
            @else
                <a href="{{ route('invoices.show', \App\Models\Invoice::where('purchase_order_id', $do->purchase_order_id)->first()->id) }}" class="flex items-center gap-2 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold shadow-lg shadow-emerald-200 transition-all active:scale-95">
                    <i data-feather="file-text" class="w-4 h-4"></i> View Invoice
                </a>
            @endif

            <button class="flex items-center gap-2 px-6 py-2.5 bg-white border border-slate-200 hover:border-slate-300 text-slate-700 rounded-xl font-bold shadow-sm transition-all active:scale-95" onclick="window.print()">
                <i data-feather="printer" class="w-4 h-4"></i> Print Receipt
            </button>
            <a href="{{ route('delivery-orders.index') }}" class="flex items-center gap-2 px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-all active:scale-95">
                <i data-feather="arrow-left" class="w-4 h-4"></i> Back
            </a>
        </div>
    </div>

    <!-- Main Detail Card -->
    <div class="bg-white/95 backdrop-blur-xl rounded-[2rem] border border-slate-200/60 shadow-xl overflow-hidden">
        <!-- Status & Impact Header -->
        <div class="p-8 md:p-10 border-bottom border-slate-100 bg-emerald-50/20 flex flex-col md:flex-row gap-8 items-start">
            <div class="w-24 h-24 bg-emerald-100 text-emerald-600 rounded-3xl flex items-center justify-center shrink-0 shadow-inner">
                <i data-feather="package" class="w-12 h-12"></i>
            </div>

            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    <span class="px-4 py-1.5 bg-emerald-600 text-white rounded-full text-xs font-black tracking-widest leading-none">
                        FULLY RECORDED
                    </span>
                    <span class="px-4 py-1.5 bg-slate-100 text-slate-600 rounded-full text-xs font-black tracking-widest leading-none border border-slate-200">
                        {{ strtoupper($do->status) }}
                    </span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Inventory Impact Analysis</h3>
                <p class="text-slate-600 leading-relaxed max-w-3xl">
                    Stock levels for accepted items have been automatically adjusted in the primary warehouse. Fulfillment status for associated Purchase Order has also been recalculated.
                </p>
            </div>
        </div>

        <!-- Metadata Grid -->
        <div class="p-8 md:p-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 bg-white border-y border-slate-100">
            <div>
                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Verifier Name</span>
                <span class="block text-base font-bold text-slate-800">{{ $do->receiver->name }}</span>
            </div>
            <div>
                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Reference PO</span>
                <a href="{{ route('purchase-orders.show', $do->purchase_order_id) }}" class="text-base font-bold text-blue-600 hover:text-blue-700 transition-colors">
                    {{ $do->purchaseOrder->po_number }}
                </a>
            </div>
            <div>
                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Arrival Date</span>
                <span class="block text-base font-bold text-slate-800">{{ $do->delivery_date->format('M d, Y') }}</span>
            </div>
            <div>
                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Supplier Partner</span>
                <span class="block text-base font-bold text-slate-800">{{ $do->supplier->name }}</span>
            </div>
        </div>

        <!-- Items Table Container -->
        <div class="p-8 md:p-10">
            <h3 class="text-xl font-extrabold text-slate-900 mb-6 flex items-center gap-3">
                <span class="p-2 bg-slate-100 rounded-lg"><i data-feather="check-circle" class="w-5 h-5"></i></span>
                Receipt Specifications
            </h3>
            
            <div class="overflow-x-auto rounded-2xl border border-slate-100">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest">Specification</th>
                            <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-center">Delivered</th>
                            <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-center">Accepted</th>
                            <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-center">Rejected</th>
                            <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest">Condition Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($do->items as $item)
                        <tr class="hover:bg-slate-50/30 transition-colors">
                            <td class="px-6 py-5">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-800 leading-tight">
                                        {{ $item->product ? $item->product->name : ($item->purchaseOrderItem->item_name ?? 'Unidentified Item') }}
                                    </span>
                                    @if($item->purchaseOrderItem && $item->purchaseOrderItem->description)
                                    <span class="text-[11px] font-medium text-slate-500 mt-1">
                                        {{ $item->purchaseOrderItem->description }}
                                    </span>
                                    @endif
                                    @if($item->variant)
                                    <span class="text-[11px] font-medium text-slate-400 mt-1 uppercase tracking-wider">
                                        {{ $item->variant->attributes_summary }}
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center font-bold text-slate-400">{{ $item->quantity_delivered }}</td>
                            <td class="px-6 py-5 text-center font-black text-emerald-600">{{ $item->quantity_accepted }}</td>
                            <td class="px-6 py-5 text-center font-black {{ $item->quantity_rejected > 0 ? 'text-red-600' : 'text-slate-300' }}">
                                {{ $item->quantity_rejected }}
                            </td>
                            <td class="px-6 py-5">
                                <span class="text-xs font-medium text-slate-500">
                                    {{ $item->rejection_reason ?: ($item->condition_notes ?: 'Nominal condition verified') }}
                                </span>
                                @if($item->quantity_rejected > 0 && $item->resolution_type)
                                    <div class="mt-2">
                                        @if($item->resolution_type == 'refund')
                                            <span class="px-2 py-0.5 bg-red-100 text-red-600 rounded-full text-[10px] font-black uppercase tracking-tighter">Refund Requested</span>
                                        @elseif($item->resolution_type == 'replacement')
                                            <span class="px-2 py-0.5 bg-blue-100 text-blue-600 rounded-full text-[10px] font-black uppercase tracking-tighter">Replacement Expected</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($do->notes)
    <div class="mt-8 p-8 bg-emerald-50/30 border border-emerald-100 rounded-[2rem]">
        <h4 class="text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-3">Verifier Remarks</h4>
        <p class="text-slate-600 text-sm leading-relaxed italic">"{{ $do->notes }}"</p>
    </div>
    @endif
</div>
@endsection
