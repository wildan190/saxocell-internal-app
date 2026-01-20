@extends('layouts.app')

@section('title', ($transfer->status == 'requested' ? 'Stock Request #' : 'Stock Transfer #') . $transfer->reference_number)

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <a href="{{ route('home') }}">Home</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item">
        <a href="{{ route('stock-transfers.index') }}">Logistics</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">{{ $transfer->reference_number }}</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="px-3 py-1 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-lg">
                    {{ $transfer->status == 'requested' ? 'STOCK REQUEST' : 'INTERNAL TRANSFER' }}
                </span>
                <span class="text-slate-400 font-mono text-sm">#{{ $transfer->reference_number }}</span>
            </div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">Logistics Workflow</h1>
        </div>
        
        <div class="flex flex-wrap gap-3">
            @if($transfer->status == 'requested')
                <form action="{{ route('stock-transfers.approve', $transfer->id) }}" method="POST" onsubmit="return confirm('Approve this request? Stock will be deducted from Warehouse.');">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-200 transition-all active:scale-95">
                        <i data-feather="check-circle" class="w-4 h-4"></i> Approve Request
                    </button>
                </form>
                <form action="{{ route('stock-transfers.reject', $transfer->id) }}" method="POST" onsubmit="return confirm('Reject and cancel this request?');">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-6 py-2.5 bg-white border border-red-200 text-red-600 hover:bg-red-50 rounded-xl font-bold transition-all active:scale-95">
                        <i data-feather="x-circle" class="w-4 h-4"></i> Reject
                    </button>
                </form>
            @endif
            <a href="{{ route('stock-transfers.index') }}" class="flex items-center gap-2 px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-all active:scale-95">
                <i data-feather="arrow-left" class="w-4 h-4"></i> Back
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3 animate-in fade-in slide-in-from-top-4 duration-500">
            <i data-feather="check-circle" class="w-5 h-5"></i>
            <span class="text-sm font-bold">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-8 p-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl flex items-center gap-3 animate-in fade-in slide-in-from-top-4 duration-500">
            <i data-feather="alert-circle" class="w-5 h-5"></i>
            <span class="text-sm font-bold">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Main Detail Card -->
    <div class="bg-white/95 backdrop-blur-xl rounded-[2.5rem] border border-slate-200/60 shadow-xl overflow-hidden">
        <!-- Route Analysis Header -->
        <div class="p-8 md:p-10 border-b border-slate-100 bg-slate-50/20 flex flex-col md:flex-row gap-10 items-center">
            <div class="w-24 h-24 bg-white border-2 border-slate-100 text-slate-400 rounded-3xl flex items-center justify-center shrink-0 shadow-inner">
                <i data-feather="{{ $transfer->status == 'requested' ? 'download-cloud' : 'truck' }}" class="w-12 h-12"></i>
            </div>

            <div class="flex-1 w-full">
                <div class="flex items-center justify-between flex-wrap gap-4 mb-6">
                    @php
                        $config = match($transfer->status) {
                            'requested' => ['bg' => 'bg-blue-600', 'text' => 'text-white', 'label' => 'NEW REQUEST'],
                            'pending'   => ['bg' => 'bg-amber-500', 'text' => 'text-white', 'label' => 'IN TRANSIT'],
                            'received', 'completed' => ['bg' => 'bg-emerald-600', 'text' => 'text-white', 'label' => 'DELIVERED'],
                            'rejected', 'cancelled' => ['bg' => 'bg-red-600', 'text' => 'text-white', 'label' => 'REJECTED'],
                            default     => ['bg' => 'bg-slate-600', 'text' => 'text-white', 'label' => strtoupper($transfer->status)]
                        };
                    @endphp
                    <span class="px-4 py-1.5 {{ $config['bg'] }} {{ $config['text'] }} rounded-full text-[10px] font-black tracking-widest leading-none">
                        {{ $config['label'] }}
                    </span>
                    <span class="text-xs font-bold text-slate-400 flex items-center gap-1.5">
                        <i data-feather="clock" class="w-3.5 h-3.5"></i>
                        Created {{ $transfer->created_at->format('M d, Y @ H:i') }}
                    </span>
                </div>

                <div class="flex items-center justify-center md:justify-start gap-8">
                    <div>
                        <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 leading-none">Shipped From</span>
                        <div class="flex items-center gap-2">
                            <i data-feather="archive" class="w-4 h-4 text-blue-500"></i>
                            <span class="text-lg font-black text-slate-800">{{ $transfer->sourceWarehouse->name }}</span>
                        </div>
                    </div>
                    <div class="p-3 bg-slate-100 rounded-2xl text-slate-300">
                        <i data-feather="arrow-right" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 leading-none">Destination</span>
                        <div class="flex items-center gap-2">
                            <i data-feather="shopping-cart" class="w-4 h-4 text-emerald-500"></i>
                            <span class="text-lg font-black text-slate-800">{{ $transfer->destinationStore->name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-8 md:p-10">
            <h3 class="text-xl font-extrabold text-slate-900 mb-6 flex items-center gap-3">
                <span class="p-2 bg-slate-100 rounded-lg"><i data-feather="package" class="w-5 h-5 text-slate-600"></i></span>
                Manifest Details
            </h3>
            
            <form action="{{ route('stock-transfers.receive', $transfer->id) }}" method="POST">
                @csrf
                <div class="overflow-x-auto rounded-3xl border border-slate-100">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-8 py-5 text-[11px] font-black text-slate-500 uppercase tracking-widest">Product Information</th>
                                <th class="px-8 py-5 text-[11px] font-black text-slate-500 uppercase tracking-widest text-center">Reference SKU</th>
                                <th class="px-8 py-5 text-[11px] font-black text-slate-500 uppercase tracking-widest text-center">
                                    {{ $transfer->status == 'requested' ? 'Units Requested' : 'Units Sent' }}
                                </th>
                                <th class="px-8 py-5 text-[11px] font-black text-slate-500 uppercase tracking-widest text-right">
                                    {{ $transfer->status == 'requested' ? 'Action' : 'Units Received' }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($transfer->items as $item)
                            <tr class="hover:bg-slate-50/30 transition-colors">
                                <td class="px-8 py-6">
                                    <span class="font-bold text-slate-800 leading-tight block">{{ $item->product->name }}</span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <span class="font-mono text-[11px] px-2 py-1 bg-slate-100 text-slate-500 rounded-md">{{ $item->product->sku }}</span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <span class="text-lg font-black text-slate-700">{{ $item->quantity_sent }}</span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    @if($transfer->status == 'pending')
                                        <div class="flex justify-end">
                                            <input type="number" 
                                                   name="items[{{ $item->id }}]" 
                                                   class="w-32 px-4 py-2 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-xl font-black text-slate-800 text-right transition-all outline-none" 
                                                   value="{{ old("items.{$item->id}", $item->quantity_sent) }}" 
                                                   min="0"
                                                   max="{{ $item->quantity_sent }}"
                                                   required>
                                        </div>
                                    @else
                                        @if($transfer->status == 'requested')
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Awaiting Approval</span>
                                        @else
                                            <div class="flex items-center justify-end gap-2">
                                                @if($item->quantity_received < $item->quantity_sent)
                                                    <span class="px-2 py-0.5 bg-red-100 text-red-600 rounded-md text-[10px] font-black">-{{ $item->quantity_sent - $item->quantity_received }} MISMATCH</span>
                                                @endif
                                                <span class="text-lg font-black {{ $item->quantity_received < $item->quantity_sent ? 'text-amber-500' : 'text-emerald-600' }}">
                                                    {{ $item->quantity_received }}
                                                </span>
                                            </div>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($transfer->status == 'pending')
                <div class="mt-10 flex justify-end">
                    <button type="submit" class="flex items-center gap-3 px-10 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black shadow-xl shadow-emerald-100 transition-all active:scale-95 text-lg">
                        <i data-feather="check-square" class="w-6 h-6"></i> Confirm Final Receipt
                    </button>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush
@endsection
