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
        <a href="{{ route('stock-transfers.index') }}">Stock Transfers</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">{{ $transfer->reference_number }}</div>
</nav>
@endsection

@section('content')
<div class="p-4 md:p-8 max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">
                {{ $transfer->status == 'requested' ? 'Stock Request' : 'Stock Transfer' }}
                <span class="text-slate-400">#{{ $transfer->reference_number }}</span>
            </h1>
            <p class="text-slate-500 mt-2 flex items-center gap-2">
                <span class="flex items-center gap-1.5 font-medium text-slate-700">
                    <i data-feather="calendar" class="w-4 h-4"></i>
                    Created on {{ $transfer->created_at->format('M d, Y') }}
                </span>
            </p>
        </div>
        
        <div class="flex flex-wrap gap-3">
            @if($transfer->status == 'requested')
                <form action="{{ route('stock-transfers.approve', $transfer->id) }}" method="POST" onsubmit="return confirm('Approve this request? Stock will be deducted from Warehouse.');">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold shadow-lg shadow-emerald-200 transition-all active:scale-95">
                        <i data-feather="check-circle" class="w-4 h-4"></i> Approve Request
                    </button>
                </form>
                <form action="{{ route('stock-transfers.reject', $transfer->id) }}" method="POST" onsubmit="return confirm('Reject and cancel this request?');">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-6 py-2.5 bg-white border border-red-200 hover:bg-red-50 text-red-600 rounded-xl font-bold shadow-sm transition-all active:scale-95">
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
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3">
            <i data-feather="check-circle" class="w-5 h-5 flex-shrink-0"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl flex items-center gap-3">
            <i data-feather="alert-circle" class="w-5 h-5 flex-shrink-0"></i>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Main Detail Card -->
    <div class="bg-white/95 backdrop-blur-xl rounded-[2rem] border border-slate-200/60 shadow-xl overflow-hidden">
        <!-- Status & Route Header -->
        <div class="p-8 md:p-10 border-bottom border-slate-100 bg-slate-50/30 flex flex-col md:flex-row gap-8 items-start">
            <div class="w-24 h-24 bg-indigo-50 text-indigo-500 rounded-3xl flex items-center justify-center shrink-0 shadow-inner">
                <i data-feather="{{ $transfer->status == 'requested' ? 'download' : 'truck' }}" class="w-12 h-12"></i>
            </div>

            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    @php
                        $statusColors = [
                            'requested' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700'],
                            'pending' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700'],
                            'received' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700'],
                            'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-700'],
                        ];
                        $st = $statusColors[$transfer->status] ?? ['bg' => 'bg-slate-100', 'text' => 'text-slate-700'];
                    @endphp
                    <span class="px-4 py-1.5 {{ $st['bg'] }} {{ $st['text'] }} rounded-full text-xs font-black tracking-widest leading-none uppercase">
                        {{ $transfer->status }}
                    </span>
                    <span class="px-4 py-1.5 bg-slate-800 text-white rounded-full text-xs font-black tracking-widest leading-none">
                        INTERNAL MOVEMENT
                    </span>
                </div>
                
                <div class="flex flex-col md:flex-row gap-8 mt-6">
                    <div class="flex-1">
                        <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">From (Source)</span>
                        <div class="flex items-center gap-3">
                            <i data-feather="archive" class="w-5 h-5 text-slate-400"></i>
                            <span class="text-lg font-bold text-slate-800">{{ $transfer->sourceWarehouse->name }}</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-center text-slate-300">
                        <i data-feather="arrow-right" class="w-6 h-6"></i>
                    </div>
                    <div class="flex-1">
                        <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">To (Destination)</span>
                        <div class="flex items-center gap-3">
                            <i data-feather="shopping-cart" class="w-5 h-5 text-slate-400"></i>
                            <span class="text-lg font-bold text-slate-800">{{ $transfer->destinationStore->name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('stock-transfers.receive', $transfer->id) }}" method="POST">
            @csrf
            
            <!-- Items Table -->
            <div class="p-8 md:p-10">
                <h3 class="text-xl font-extrabold text-slate-900 mb-6 flex items-center gap-3">
                    <span class="p-2 bg-slate-100 rounded-lg"><i data-feather="list" class="w-5 h-5"></i></span>
                    Transfer Items
                </h3>
                
                <div class="overflow-x-auto rounded-2xl border border-slate-100">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest">Product</th>
                                <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest">SKU</th>
                                <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-right">
                                    {{ $transfer->status == 'requested' ? 'Qty Requested' : 'Qty Sent' }}
                                </th>
                                <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-right w-48">
                                    {{ $transfer->status == 'requested' ? 'Pending Action' : 'Qty Received' }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($transfer->items as $item)
                            <tr class="hover:bg-slate-50/30 transition-colors">
                                <td class="px-6 py-5">
                                    <span class="font-bold text-slate-800">{{ $item->product->name }}</span>
                                </td>
                                <td class="px-6 py-5 text-slate-600 font-medium">{{ $item->product->sku }}</td>
                                <td class="px-6 py-5 text-right font-bold text-slate-800">{{ $item->quantity_sent }}</td>
                                <td class="px-6 py-5 text-right">
                                    @if($transfer->status == 'pending')
                                        <input type="number" 
                                               name="items[{{ $item->id }}]" 
                                               class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-right font-bold focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" 
                                               value="{{ old("items.{$item->id}", $item->quantity_sent) }}" 
                                               min="0"
                                               max="{{ $item->quantity_sent }}"
                                               required>
                                    @else
                                        <span class="font-bold {{ $item->quantity_received < $item->quantity_sent ? 'text-amber-600' : 'text-emerald-600' }}">
                                            {{ $item->quantity_received }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($transfer->status == 'pending')
                <div class="mt-8 flex justify-end">
                    <button type="submit" class="flex items-center gap-2 px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-200 transition-all active:scale-95">
                        <i data-feather="check-square" class="w-5 h-5"></i> Confirm Receipt
                    </button>
                </div>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
