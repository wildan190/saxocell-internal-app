@extends('layouts.app')

@section('title', 'Store: ' . $store->name)

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
    <div class="breadcrumb-item active">{{ $store->name }}</div>
</nav>
@endsection

@section('content')
<div class="main-content-inner content-wrapper bg-white lg:bg-transparent">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[10px] font-black uppercase tracking-widest mb-4">
                <i data-feather="map-pin" class="w-3 h-3"></i> Store Location
            </div>
            <h1 class="text-3xl md:text-5xl font-extrabold text-slate-900 tracking-tight">{{ $store->name }}</h1>
            <p class="text-slate-500 mt-2 flex items-center gap-2">
                <i data-feather="navigation" class="w-4 h-4"></i>
                {{ $store->address ?: 'No address specified' }}
            </p>
        </div>
        
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('stores.edit', $store) }}" class="flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-200 transition-all active:scale-95 leading-none">
                <i data-feather="edit-2" class="w-4 h-4 text-white/70"></i> Edit Details
            </a>
            <a href="{{ route('stores.index') }}" class="flex items-center gap-2 px-6 py-2.5 bg-white border border-slate-200 hover:border-slate-300 text-slate-700 rounded-xl font-bold shadow-sm transition-all active:scale-95 leading-none">
                <i data-feather="arrow-left" class="w-4 h-4 text-slate-400"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Stats Dashboard -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-200/60 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-slate-50 text-slate-400 rounded-2xl">
                    <i data-feather="box" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Total SKUs</span>
                    <span class="block text-2xl font-black text-slate-800">{{ number_format($stats['total_skus']) }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-200/60 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-emerald-50 text-emerald-500 rounded-2xl">
                    <i data-feather="check-circle" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Active Items</span>
                    <span class="block text-2xl font-black text-emerald-600">{{ number_format($stats['active_products']) }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-200/60 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-amber-50 text-amber-500 rounded-2xl">
                    <i data-feather="alert-triangle" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Low Stock</span>
                    <span class="block text-2xl font-black text-amber-600">{{ number_format($stats['low_stock']) }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-200/60 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-rose-50 text-rose-500 rounded-2xl">
                    <i data-feather="x-circle" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Out of Stock</span>
                    <span class="block text-2xl font-black text-rose-600">{{ number_format($stats['out_of_stock']) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Store Content -->
        <div class="lg:col-span-8">
            <div class="bg-white rounded-[2.5rem] border border-slate-200/60 shadow-xl overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center gap-3">
                        <span class="w-8 h-8 bg-slate-900 text-white rounded-lg flex items-center justify-center">
                            <i data-feather="list" class="w-4 h-4"></i>
                        </span>
                        Inventory Manifest
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Product Information</th>
                                <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                                <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Qty</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right whitespace-nowrap">Control</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($store->inventory as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center shrink-0 group-hover:bg-white transition-colors border border-transparent group-hover:border-slate-200 shadow-sm">
                                            <i data-feather="package" class="w-6 h-6 text-slate-400"></i>
                                        </div>
                                        <div>
                                            <span class="block font-bold text-slate-800 group-hover:text-blue-600 transition-colors">{{ $item->product->name }}</span>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $item->product->sku }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-5">
                                    <div class="flex justify-center">
                                        @if($item->is_active)
                                        <span class="px-2.5 py-1 bg-emerald-100/50 text-emerald-600 rounded-lg text-[10px] font-black uppercase tracking-widest">Active</span>
                                        @else
                                        <span class="px-2.5 py-1 bg-slate-100 text-slate-400 rounded-lg text-[10px] font-black uppercase tracking-widest">Inactive</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-5 text-right font-mono font-bold text-slate-600">
                                    <span class="{{ $item->quantity <= 5 ? 'text-amber-500' : ($item->quantity <= 0 ? 'text-rose-500' : 'text-slate-600') }}">
                                        {{ number_format($item->quantity) }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <form action="{{ route('stores.inventory.toggle-status', [$store->id, $item->id]) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" 
                                            class="w-10 h-10 flex items-center justify-center rounded-xl transition-all {{ $item->is_active ? 'bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white' : 'bg-emerald-50 text-emerald-500 hover:bg-emerald-500 hover:text-white' }}"
                                            title="{{ $item->is_active ? 'Mark as Inactive' : 'Mark as Active' }}">
                                            <i data-feather="{{ $item->is_active ? 'slash' : 'check-circle' }}" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-8 py-20 text-center">
                                    <div class="max-w-xs mx-auto">
                                        <div class="w-16 h-16 bg-slate-50 text-slate-200 rounded-3xl flex items-center justify-center mx-auto mb-6">
                                            <i data-feather="inbox" class="w-8 h-8"></i>
                                        </div>
                                        <h4 class="text-slate-900 font-bold mb-1">No Inventory Records</h4>
                                        <p class="text-slate-400 text-sm">There are currently no products tracked in this store.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="lg:col-span-4 space-y-8">
            <!-- About Store -->
            <div class="bg-white rounded-[2rem] border border-slate-200/60 shadow-sm p-8">
                <h3 class="text-xs font-black text-slate-800 uppercase tracking-[0.2em] mb-6 flex items-center gap-3">
                    <span class="w-6 h-6 bg-slate-900 text-white rounded-md flex items-center justify-center">
                        <i data-feather="info" class="w-3 h-3"></i>
                    </span>
                    About Store
                </h3>
                
                <div class="space-y-6">
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Internal Notes</label>
                        <div class="text-slate-600 leading-relaxed text-sm bg-slate-50/50 p-4 rounded-2xl border border-slate-100">
                            {{ $store->description ?: 'No detailed description available for this store.' }}
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100">
                        <div class="flex items-center gap-4 group">
                            <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i data-feather="clock" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Established</span>
                                <span class="block font-bold text-slate-700 text-sm italic">{{ $store->created_at->format('M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-slate-900 rounded-[2rem] p-8 shadow-xl shadow-slate-200">
                <h3 class="text-xs font-black text-white/50 uppercase tracking-[0.2em] mb-6">Management</h3>
                <div class="grid grid-cols-1 gap-3">
                    <a href="{{ route('stock-transfers.create-request', ['store_id' => $store->id]) }}" class="flex items-center justify-between p-4 bg-white/10 hover:bg-white/20 text-white rounded-2xl transition-all group">
                        <div class="flex items-center gap-3">
                            <i data-feather="corner-up-right" class="w-4 h-4 text-blue-400"></i>
                            <span class="font-bold text-sm">Request Stock</span>
                        </div>
                        <i data-feather="chevron-right" class="w-4 h-4 text-white/30 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>
@endpush
