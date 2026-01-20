@extends('layouts.app')

@section('title', 'PO Details: ' . $po->po_number)

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <a href="{{ route('home') }}">Home</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item">
        <a href="{{ route('purchase-orders.index') }}">Purchase Orders</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">{{ $po->po_number }}</div>
</nav>
@endsection

@section('content')
<div class="p-4 md:p-8 max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">{{ $po->po_number }}</h1>
            <p class="text-slate-500 mt-2 flex items-center gap-2">
                <span class="flex items-center gap-1.5 font-medium text-slate-700">
                    <i data-feather="user" class="w-4 h-4"></i>
                    {{ $po->creator->name }}
                </span>
                <span class="text-slate-300">•</span>
                <span class="flex items-center gap-1.5">
                    <i data-feather="calendar" class="w-4 h-4"></i>
                    Issued on {{ $po->order_date->format('M d, Y') }}
                </span>
            </p>
        </div>
        
        <div class="flex flex-wrap gap-3">
            @if(in_array($po->status, ['draft', 'submitted']))
                <form action="{{ route('purchase-orders.approve', $po->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-200 transition-all active:scale-95">
                        <i data-feather="check-square" class="w-4 h-4"></i> Approve Order
                    </button>
                </form>
            @endif
            <button class="flex items-center gap-2 px-6 py-2.5 bg-white border border-slate-200 hover:border-slate-300 text-slate-700 rounded-xl font-bold shadow-sm transition-all active:scale-95" onclick="window.print()">
                <i data-feather="printer" class="w-4 h-4"></i> Print Version
            </button>
            <a href="{{ route('purchase-orders.index') }}" class="flex items-center gap-2 px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-all active:scale-95">
                <i data-feather="arrow-left" class="w-4 h-4"></i> Back
            </a>
        </div>
    </div>

    <!-- Main Detail Card -->
    <div class="bg-white/95 backdrop-blur-xl rounded-[2rem] border border-slate-200/60 shadow-xl overflow-hidden">
        <!-- Status & Goal Header -->
        <div class="p-8 md:p-10 border-bottom border-slate-100 bg-slate-50/30 flex flex-col md:flex-row gap-8 items-start">
            <div class="w-24 h-24 bg-blue-50 text-blue-500 rounded-3xl flex items-center justify-center shrink-0 shadow-inner">
                <i data-feather="shopping-cart" class="w-12 h-12"></i>
            </div>

            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    @php
                        $statusMap = [
                            'draft' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'label' => 'DRAFT'],
                            'submitted' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600', 'label' => 'SUBMITTED'],
                            'approved' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-600', 'label' => 'APPROVED'],
                            'partial' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-600', 'label' => 'PARTIAL'],
                            'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-600', 'label' => 'COMPLETED'],
                            'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-600', 'label' => 'CANCELLED'],
                        ];
                        $st = $statusMap[$po->status] ?? $statusMap['draft'];
                    @endphp
                    <span class="px-4 py-1.5 {{ $st['bg'] }} {{ $st['text'] }} rounded-full text-xs font-black tracking-widest leading-none">
                        {{ $st['label'] }}
                    </span>
                    <span class="px-4 py-1.5 bg-blue-600 text-white rounded-full text-xs font-black tracking-widest leading-none">
                        OFFICIAL PROCUREMENT
                    </span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Procurement Goal</h3>
                <p class="text-slate-600 leading-relaxed max-w-3xl">
                    {{ $po->notes ?: 'Standard replenishment order for internal stock requirements. No specific additional notes provided.' }}
                </p>
            </div>
        </div>

        <!-- Supplier & Logistics Grid -->
        <div class="p-8 md:p-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 bg-white border-y border-slate-100">
            <div>
                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 text-center md:text-left">Supplier Partner</span>
                <span class="block text-base font-bold text-slate-800 text-center md:text-left">{{ $po->supplier->name }}</span>
            </div>
            <div>
                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 text-center md:text-left">Primary Contact</span>
                <span class="block text-base font-bold text-slate-800 text-center md:text-left">{{ $po->supplier->contact_person }}</span>
            </div>
            <div>
                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 text-center md:text-left">Expected Arrival</span>
                <span class="block text-base font-bold text-slate-800 text-center md:text-left">
                    {{ $po->expected_delivery_date ? $po->expected_delivery_date->format('M d, Y') : 'Not Scheduled' }}
                </span>
            </div>
            <div>
                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 text-center md:text-left">Authorization</span>
                <span class="block text-base font-bold text-slate-800 text-center md:text-left">
                    {{ $po->approver ? $po->approver->name : 'Awaiting Review' }}
                </span>
            </div>
        </div>

        <!-- Items Table Container -->
        <div class="p-8 md:p-10">
            <h3 class="text-xl font-extrabold text-slate-900 mb-6 flex items-center gap-3">
                <span class="p-2 bg-slate-100 rounded-lg"><i data-feather="list" class="w-5 h-5"></i></span>
                Order Line Items
            </h3>
            
            <div class="overflow-x-auto rounded-2xl border border-slate-100">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest">Specification</th>
                            <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-center">Qty</th>
                            <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-center">Fulfillment</th>
                            <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-right">Unit Price</th>
                            <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($po->items as $item)
                        <tr class="hover:bg-slate-50/30 transition-colors">
                            <td class="px-6 py-5">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-800 leading-tight">
                                        {{ $item->product ? $item->product->name : $item->item_name }}
                                    </span>
                                    @if($item->description)
                                    <span class="text-[11px] font-medium text-slate-500 mt-1">
                                        {{ $item->description }}
                                    </span>
                                    @endif
                                    @if($item->variant)
                                    <span class="text-[11px] font-medium text-slate-400 mt-1 uppercase tracking-wider">
                                        {{ $item->variant->attributes_summary }}
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center font-bold text-slate-700">{{ $item->quantity_ordered }}</td>
                            <td class="px-6 py-5">
                                <div class="flex justify-center">
                                    @if($item->quantity_received >= $item->quantity_ordered)
                                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-md text-[10px] font-black border border-emerald-100">FULL</span>
                                    @elseif($item->quantity_received > 0)
                                        <span class="px-2.5 py-1 bg-amber-50 text-amber-600 rounded-md text-[10px] font-black border border-amber-100">PARTIAL ({{ $item->quantity_received }})</span>
                                    @else
                                        <span class="text-[10px] font-black text-slate-300 tracking-widest uppercase">PENDING</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5 text-right font-medium text-slate-500">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td class="px-6 py-5 text-right font-bold text-slate-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Financial Summary Bar -->
        <div class="bg-slate-50/50 p-8 md:p-10 border-t border-slate-100">
            <div class="flex flex-col md:flex-row items-end justify-end gap-8 text-right">
                <div class="flex flex-col gap-1">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Subtotal Amount</span>
                    <span class="text-xl font-bold text-slate-700">Rp {{ number_format($po->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tax Aggregation</span>
                    <span class="text-xl font-bold text-slate-700">Rp {{ number_format($po->tax_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex flex-col gap-1 md:pl-8 md:border-l border-slate-200">
                    <span class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Grand Total</span>
                    <span class="text-4xl font-black text-blue-600 tracking-tight">Rp {{ number_format($po->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Deliveries Section -->
    @if($po->deliveryOrders->count() > 0)
    <div class="mt-12">
        <h3 class="text-2xl font-black text-slate-900 mb-8 flex items-center gap-3">
            <span class="p-2 bg-emerald-50 text-emerald-600 rounded-xl shadow-sm shadow-emerald-100"><i data-feather="package" class="w-6 h-6"></i></span>
            Related Receipts
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($po->deliveryOrders as $do)
            <a href="{{ route('delivery-orders.show', $do->id) }}" class="group bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm hover:shadow-xl hover:border-blue-200 transition-all duration-300 active:scale-[0.98]">
                <div class="flex items-center gap-4">
                    <div class="p-4 bg-emerald-50 group-hover:bg-blue-50 text-emerald-500 group-hover:text-blue-500 rounded-2xl transition-colors">
                        <i data-feather="file-text" class="w-6 h-6"></i>
                    </div>
                    <div class="flex-1">
                        <div class="text-lg font-black text-slate-800 group-hover:text-blue-600 transition-colors">{{ $do->do_number }}</div>
                        <div class="text-xs font-bold text-slate-400 mt-1 flex items-center gap-2 uppercase tracking-tight">
                            <i data-feather="clock" class="w-3 h-3"></i>
                            {{ $do->delivery_date->format('M d, Y') }}
                        </div>
                    </div>
                    <i data-feather="chevron-right" class="w-5 h-5 text-slate-300 group-hover:text-blue-400 transition-all group-hover:translate-x-1"></i>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    @php
        $pendingResolutions = \App\Models\RejectedItem::whereHas('deliveryOrder', function($q) use ($po) {
            $q->where('purchase_order_id', $po->id);
        })->where(function($q) {
            $q->where('resolution_type', 'replacement')
              ->whereColumn('replacement_received_quantity', '<', 'quantity_rejected')
              ->orWhere('resolution_type', 'refund');
        })->get();
    @endphp

    @if($pendingResolutions->count() > 0)
    <div class="mt-12 p-8 bg-amber-50/50 border border-amber-100 rounded-[2rem]">
        <h3 class="text-xl font-extrabold text-amber-900 mb-6 flex items-center gap-3">
            <span class="p-2 bg-amber-100 rounded-lg text-amber-600"><i data-feather="alert-triangle" class="w-5 h-5"></i></span>
            Pending Resolutions
        </h3>
        
        <div class="grid grid-cols-1 gap-4">
            @foreach($pendingResolutions as $resolution)
            <div class="bg-white p-6 rounded-2xl border border-amber-200/60 shadow-sm flex items-center justify-between">
                <div>
                    <div class="font-bold text-slate-800">
                        {{ $resolution->purchaseOrderItem->product ? $resolution->purchaseOrderItem->product->name : $resolution->purchaseOrderItem->item_name }}
                    </div>
                    <div class="text-xs text-slate-500 mt-1">
                        From DO: <strong>{{ $resolution->deliveryOrder->do_number }}</strong> • 
                        Reason: <i>{{ $resolution->rejection_reason ?: 'No specific reason given' }}</i>
                    </div>
                </div>
                <div class="text-right">
                    @if($resolution->resolution_type == 'replacement')
                        @php $remaining = $resolution->quantity_rejected - $resolution->replacement_received_quantity; @endphp
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-[10px] font-black uppercase tracking-widest">
                            Replacement: {{ $remaining }} / {{ $resolution->quantity_rejected }} units pending
                        </span>
                    @else
                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-[10px] font-black uppercase tracking-widest">
                            Refund Requested: {{ $resolution->quantity_rejected }} units
                        </span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>
@endpush

<style>
    @media print {
        .breadcrumb, .action-bar, .btn, .related-section, form { display: none !important; }
        body { background: white !important; }
        .max-w-7xl { max-width: 100% !important; padding: 0 !important; }
        .rounded-\[2rem\] { border-radius: 0 !important; border: none !important; box-shadow: none !important; }
    }
</style>
@endsection
