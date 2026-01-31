@extends('layouts.app')

@section('title', 'Stock Transfers')

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <a href="{{ route('home') }}">Home</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">Stock Transfers</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
        <div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">Stock Logistics</h1>
            <p class="text-slate-500 mt-2 font-medium">Manage and track inventory movements across your supply chain.</p>
        </div>
        
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('stock-transfers.create-request') }}" class="flex items-center gap-2 px-6 py-3 bg-white border-2 border-slate-200 hover:border-blue-500 text-slate-700 hover:text-blue-600 rounded-2xl font-black transition-all active:scale-95 shadow-sm">
                <i data-feather="download" class="w-4 h-4"></i> Request Stock
            </a>
            <a href="{{ route('stock-transfers.create') }}" class="flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-black shadow-lg shadow-blue-200 transition-all active:scale-95">
                <i data-feather="plus" class="w-4 h-4"></i> New Transfer
            </a>
        </div>
    </div>

    <!-- Stats Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-200/60 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-slate-50 text-slate-400 rounded-2xl">
                    <i data-feather="hash" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Total Movement</span>
                    <span class="block text-2xl font-black text-slate-800">{{ $stats['total'] }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-200/60 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-blue-50 text-blue-500 rounded-2xl">
                    <i data-feather="clock" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="block text-[10px] font-black text-blue-400 uppercase tracking-widest mb-0.5">Pending Approval</span>
                    <span class="block text-2xl font-black text-slate-800">{{ $stats['requested'] }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-200/60 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-amber-50 text-amber-500 rounded-2xl">
                    <i data-feather="truck" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="block text-[10px] font-black text-amber-500 uppercase tracking-widest mb-0.5">In Transit</span>
                    <span class="block text-2xl font-black text-slate-800">{{ $stats['pending'] }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-200/60 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-emerald-50 text-emerald-500 rounded-2xl">
                    <i data-feather="check-circle" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="block text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-0.5">Fulfilled</span>
                    <span class="block text-2xl font-black text-slate-800">{{ $stats['received'] }}</span>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3 animate-in fade-in slide-in-from-top-4 duration-500">
            <i data-feather="check-circle" class="w-5 h-5"></i>
            <span class="text-sm font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Transfers Table -->
    <div class="bg-white/95 backdrop-blur-xl rounded-[2.5rem] border border-slate-200/60 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-6 text-[11px] font-black text-slate-500 uppercase tracking-widest">Movement Ref</th>
                        <th class="px-8 py-6 text-[11px] font-black text-slate-500 uppercase tracking-widest text-center">Logistics Route</th>
                        <th class="px-8 py-6 text-[11px] font-black text-slate-500 uppercase tracking-widest text-center">Current Status</th>
                        <th class="px-8 py-6 text-[11px] font-black text-slate-500 uppercase tracking-widest text-right">Verification</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($transfers as $transfer)
                    <tr class="group hover:bg-slate-50/50 transition-all duration-300">
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="font-black text-slate-800 tracking-tight group-hover:text-blue-600 transition-colors">
                                    {{ $transfer->reference_number }}
                                </span>
                                <span class="text-[10px] font-bold text-slate-400 mt-1 uppercase">
                                    <i data-feather="calendar" class="w-3 h-3 inline-block -mt-0.5 mr-1"></i>
                                    Issued {{ $transfer->created_at->format('M d, Y') }}
                                </span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center justify-center gap-4">
                                <div class="text-center">
                                    <span class="block text-xs font-black text-slate-700">{{ $transfer->sourceWarehouse?->name ?? 'Unknown' }}</span>
                                    <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Origin</span>
                                </div>
                                <div class="p-2 bg-slate-100 rounded-xl text-slate-400">
                                    <i data-feather="arrow-right" class="w-4 h-4"></i>
                                </div>
                                <div class="text-center">
                                    <span class="block text-xs font-black text-slate-700">{{ $transfer->destinationStore?->name ?? 'Unknown' }}</span>
                                    <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Destination</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @php
                                $config = match($transfer->status) {
                                    'requested' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600', 'icon' => 'clock', 'label' => 'NEW REQUEST'],
                                    'pending'   => ['bg' => 'bg-amber-100', 'text' => 'text-amber-600', 'icon' => 'truck', 'label' => 'IN TRANSIT'],
                                    'received', 'completed' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-600', 'icon' => 'check-circle', 'label' => 'ARRIVED'],
                                    'rejected', 'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-600', 'icon' => 'x-circle', 'label' => 'DENIED'],
                                    default     => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'icon' => 'help-circle', 'label' => strtoupper($transfer->status)]
                                };
                            @endphp
                            <div class="inline-flex items-center gap-2 px-4 py-1.5 {{ $config['bg'] }} {{ $config['text'] }} rounded-full">
                                <i data-feather="{{ $config['icon'] }}" class="w-3.5 h-3.5"></i>
                                <span class="text-[10px] font-black tracking-widest uppercase">{{ $config['label'] }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <a href="{{ route('stock-transfers.show', $transfer->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 hover:bg-blue-600 text-slate-600 hover:text-white rounded-xl font-bold text-xs transition-all active:scale-95 group-hover:shadow-lg group-hover:shadow-blue-100">
                                <i data-feather="file-text" class="w-4 h-4"></i> Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-slate-50 text-slate-200 rounded-[2rem] flex items-center justify-center mb-6">
                                    <i data-feather="truck" class="w-10 h-10"></i>
                                </div>
                                <h3 class="text-xl font-bold text-slate-400">No Logistics Found</h3>
                                <p class="text-slate-300 text-sm mt-2 font-medium">Start moving inventory by creating a new transfer or request.</p>
                                <a href="{{ route('stock-transfers.create') }}" class="mt-8 text-blue-600 font-black text-xs uppercase tracking-widest hover:text-blue-700 transition-colors flex items-center gap-2">
                                    Create First Transfer <i data-feather="arrow-right" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
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
