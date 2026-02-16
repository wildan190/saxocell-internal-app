@extends('layouts.app')

@section('title', 'Online Orders - ' . $store->name)

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <a href="{{ route('home') }}">Home</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item">
        <a href="{{ route('stores.index') }}">Stores</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item">
        <a href="{{ route('stores.show', $store) }}">{{ $store->name }}</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">Online Orders</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Header Section -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Online Orders</h1>
            <p class="page-subtitle">Manage incoming orders from {{ $store->name }}'s marketplace</p>
        </div>
        <div class="action-bar-header">
            <a href="{{ route('marketplace.index', $store->slug) }}" target="_blank" class="btn btn-secondary">
                <i data-feather="external-link"></i> Visit Shop
            </a>
            <a href="{{ route('stores.show', $store) }}" class="btn btn-primary">
                <i data-feather="arrow-left"></i> Back to Store
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i data-feather="check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i data-feather="alert-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Orders Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Invoice #</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Customer</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Total</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Date</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-bold text-slate-700">{{ $order->invoice_number }}</span>
                                    @if($order->payment_proof_path)
                                        <span class="w-2 h-2 rounded-full bg-purple-500" title="Payment Proof Uploaded"></span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-5">
                                <div class="font-bold text-slate-800">{{ $order->customer_name }}</div>
                                <div class="text-xs text-slate-500 font-medium">{{ $order->customer_phone }}</div>
                            </td>
                            <td class="px-4 py-5 text-right font-mono font-bold text-slate-900">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-5 text-center">
                                @php
                                    $statusConfig = [
                                        'pending_payment' => ['class' => 'bg-slate-100 text-slate-600', 'label' => 'Pending Payment'],
                                        'pending_confirmation' => ['class' => 'bg-amber-100/50 text-amber-600', 'label' => 'Pending Confirm'],
                                        'processing' => ['class' => 'bg-blue-100/50 text-blue-600', 'label' => 'Processing'],
                                        'completed' => ['class' => 'bg-emerald-100/50 text-emerald-600', 'label' => 'Completed'],
                                        'cancelled' => ['class' => 'bg-rose-100/50 text-rose-600', 'label' => 'Cancelled'],
                                    ];
                                    $config = $statusConfig[$order->status] ?? ['class' => 'bg-gray-100 text-gray-600', 'label' => ucwords(str_replace('_', ' ', $order->status))];
                                @endphp
                                <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest {{ $config['class'] }}">
                                    {{ $config['label'] }}
                                </span>
                            </td>
                            <td class="px-4 py-5 text-center text-sm text-slate-500 font-medium">
                                {{ $order->created_at->format('d M Y') }}
                                <span class="text-xs text-slate-400 block">{{ $order->created_at->format('H:i') }}</span>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <a href="{{ route('stores.orders.show', ['store' => $store->id, 'order' => $order->id]) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl text-blue-600 hover:bg-blue-50 transition-colors">
                                    <i data-feather="eye" class="w-4 h-4"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <div class="max-w-xs mx-auto">
                                    <div class="w-16 h-16 bg-slate-50 text-slate-200 rounded-3xl flex items-center justify-center mx-auto mb-6">
                                        <i data-feather="shopping-cart" class="w-8 h-8"></i>
                                    </div>
                                    <h4 class="text-slate-900 font-bold mb-1">No Orders Yet</h4>
                                    <p class="text-slate-400 text-sm">Orders from your online marketplace will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($orders->hasPages())
            <div class="px-8 py-6 border-t border-slate-100">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
