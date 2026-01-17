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
<div class="p-4 md:p-8 max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">{{ $store->name }}</h1>
            <p class="text-slate-500 mt-2 flex items-center gap-2">
                <i data-feather="map-pin" class="w-4 h-4"></i>
                {{ $store->address ?: 'No address provided' }}
            </p>
        </div>
        
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('stores.edit', $store) }}" class="flex items-center gap-2 px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-bold shadow-lg shadow-amber-200 transition-all active:scale-95">
                <i data-feather="edit-2" class="w-4 h-4"></i> Edit
            </a>
            <a href="{{ route('stores.index') }}" class="flex items-center gap-2 px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-all active:scale-95">
                <i data-feather="arrow-left" class="w-4 h-4"></i> Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Details Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm p-8">
                <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <span class="p-2 bg-blue-50 text-blue-600 rounded-lg"><i data-feather="info" class="w-5 h-5"></i></span>
                    About Store
                </h3>
                
                <div class="space-y-6">
                    <div>
                        <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Description</span>
                        <p class="text-slate-600 leading-relaxed font-medium">
                            {{ $store->description ?: 'No description available for this store.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-xl overflow-hidden">
                <div class="p-8 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span class="p-2 bg-emerald-50 text-emerald-600 rounded-lg"><i data-feather="shopping-bag" class="w-5 h-5"></i></span>
                        Current Inventory
                    </h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest pl-8">Product</th>
                                <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest">SKU</th>
                                <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-center">Status</th>
                                <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-right">Quantity</th>
                                <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-right pr-8">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($store->inventory as $item)
                            <tr class="hover:bg-slate-50/30 transition-colors">
                                <td class="px-6 py-4 pl-8">
                                    <span class="font-bold {{ $item->is_active ? 'text-slate-800' : 'text-slate-400' }}">{{ $item->product->name }}</span>
                                </td>
                                <td class="px-6 py-4 text-slate-500 font-medium">{{ $item->product->sku }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $item->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $item->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="font-bold {{ $item->quantity > 0 ? 'text-slate-800' : 'text-slate-300' }}">{{ $item->quantity }}</span>
                                </td>
                                <td class="px-6 py-4 text-right pr-8">
                                    <form action="{{ route('stores.inventory.toggle-status', [$store->id, $item->id]) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="p-2 rounded-lg transition-colors {{ $item->is_active ? 'text-red-500 hover:bg-red-50' : 'text-emerald-500 hover:bg-emerald-50' }}" 
                                            title="{{ $item->is_active ? 'Mark as Inactive' : 'Mark as Active' }}">
                                            <i data-feather="{{ $item->is_active ? 'slash' : 'check-circle' }}" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-8 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center gap-2">
                                        <i data-feather="inbox" class="w-8 h-8 opacity-50"></i>
                                        <span>No inventory records found in this store.</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
