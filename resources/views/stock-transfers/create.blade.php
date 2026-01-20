@extends('layouts.app')

@section('title', 'Draft New Transfer Dispatch')

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
    <div class="breadcrumb-item active">New Transfer</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
        <div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">Internal Dispatch</h1>
            <p class="text-slate-500 mt-2 font-medium">Fulfill store inventory by transferring stock directly from a warehouse.</p>
        </div>
        
        <div class="flex justify-end">
            <a href="{{ route('stock-transfers.index') }}" class="flex items-center gap-2 px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl font-black transition-all active:scale-95">
                <i data-feather="arrow-left" class="w-4 h-4"></i> Back
            </a>
        </div>
    </div>

    @if ($errors->any() || session('error'))
        <div class="mb-8 p-6 bg-red-50 border-2 border-red-100 rounded-[2rem] text-red-700 animate-in fade-in slide-in-from-top-4 duration-500">
            <h4 class="font-black text-sm uppercase tracking-widest mb-3 flex items-center gap-2">
                <i data-feather="alert-octagon" class="w-4 h-4"></i> Transfer Issues
            </h4>
            <ul class="text-sm font-bold flex flex-col gap-1">
                @if(session('error')) <li>• {{ session('error') }}</li> @endif
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="space-y-8">
        @if(!$sourceWarehouseId)
        <!-- Step 1: Warehouse Selection -->
        <div class="bg-white/95 backdrop-blur-xl rounded-[3rem] border border-slate-200/60 shadow-2xl p-10 text-center flex flex-col items-center">
            <div class="w-24 h-24 bg-blue-50 text-blue-500 rounded-[2rem] flex items-center justify-center mb-8 shadow-inner">
                <i data-feather="archive" class="w-12 h-12"></i>
            </div>
            <h2 class="text-2xl font-black text-slate-900 mb-2">Select Source Warehouse</h2>
            <p class="text-slate-500 mb-10 max-w-md mx-auto">To start a transfer, please select the warehouse that currently holds the stock.</p>
            
            <form action="{{ route('stock-transfers.create') }}" method="GET" class="w-full max-w-md">
                <select name="source_warehouse_id" class="w-full px-8 py-5 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-[2rem] font-black text-slate-800 transition-all outline-none appearance-none cursor-pointer text-center" onchange="this.form.submit()">
                    <option value="">-- Click to Choose Warehouse --</option>
                    @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                    @endforeach
                </select>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-6">Logistics Sequence • Phase 01</p>
            </form>
        </div>
        @else
        <!-- Step 2: Full Form -->
        <form action="{{ route('stock-transfers.store') }}" method="POST">
            @csrf
            <input type="hidden" name="source_warehouse_id" value="{{ $sourceWarehouseId }}">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Left: Metadata -->
                <div class="lg:col-span-12">
                    <div class="bg-white/95 backdrop-blur-xl rounded-[2.5rem] border border-slate-200/60 shadow-xl p-8 flex flex-col md:flex-row items-center justify-between gap-8">
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center shadow-inner">
                                <i data-feather="archive" class="w-8 h-8"></i>
                            </div>
                            <div>
                                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Source Facility</span>
                                <span class="text-2xl font-black text-slate-800 leading-none">
                                    {{ $warehouses->find($sourceWarehouseId)->name }}
                                </span>
                                <a href="{{ route('stock-transfers.create') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 ml-4">Switch Warehouse</a>
                            </div>
                        </div>

                        <div class="p-3 bg-slate-100 rounded-2xl text-slate-300 hidden md:block">
                            <i data-feather="arrow-right" class="w-6 h-6"></i>
                        </div>

                        <div class="flex items-center gap-6 w-full md:w-auto">
                            <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shadow-inner">
                                <i data-feather="shopping-cart" class="w-8 h-8"></i>
                            </div>
                            <div class="flex-1">
                                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Destination Outlet</span>
                                <select name="destination_store_id" class="px-4 py-2 bg-slate-50 border-2 border-slate-100 focus:border-emerald-500 rounded-xl font-bold text-slate-800 outline-none w-full md:w-64" required>
                                    <option value="">-- Choose Store --</option>
                                    @foreach($stores as $store)
                                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Section: Item Selection -->
                <div class="lg:col-span-12">
                    <div class="bg-white/95 backdrop-blur-xl rounded-[2.5rem] border border-slate-200/60 shadow-xl overflow-hidden">
                        <div class="p-8 border-b border-slate-100 bg-slate-50/30 flex justify-between items-center">
                            <h3 class="text-xl font-black text-slate-900 flex items-center gap-3">
                                <span class="p-2 bg-white rounded-xl shadow-sm"><i data-feather="package" class="w-5 h-5 text-slate-500"></i></span>
                                Inventory Selection
                            </h3>
                            <div class="relative">
                                <i data-feather="search" class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                                <input type="text" id="inventorySearch" placeholder="Filter items..." class="pl-12 pr-6 py-2 bg-white border border-slate-200 rounded-full text-sm font-medium focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse" id="inventoryTable">
                                <thead>
                                    <tr class="bg-slate-50/50">
                                        <th class="px-8 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest">Select</th>
                                        <th class="px-8 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest">Product Information</th>
                                        <th class="px-8 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-center">Available Stock</th>
                                        <th class="px-8 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-right">Transfer Quantity</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse($inventory as $index => $item)
                                    <tr class="group hover:bg-slate-50/30 transition-all duration-200">
                                        <td class="px-8 py-5">
                                            <label class="relative flex items-center cursor-pointer">
                                                <input type="checkbox" name="items[{{ $index }}][selected]" class="sr-only peer item-select" data-index="{{ $index }}">
                                                <div class="w-12 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600 transition-colors"></div>
                                                <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $item->product_id }}" disabled class="item-input-{{ $index }}">
                                            </label>
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="flex flex-col">
                                                <span class="font-black text-slate-800 leading-tight">{{ $item->product->name }}</span>
                                                <span class="text-[10px] font-mono text-slate-400 font-bold uppercase tracking-widest mt-1">{{ $item->product->sku }}</span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5 text-center">
                                            <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-sm font-black">{{ $item->quantity }} units</span>
                                        </td>
                                        <td class="px-8 py-5 text-right">
                                            <div class="flex justify-end">
                                                <input type="number" 
                                                       name="items[{{ $index }}][quantity]" 
                                                       class="w-32 px-4 py-2 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-xl font-black text-slate-800 text-right transition-all outline-none disabled:opacity-30 disabled:cursor-not-allowed item-qty item-input-{{ $index }}" 
                                                       min="1" 
                                                       max="{{ $item->quantity }}" 
                                                       required
                                                       disabled
                                                       placeholder="0">
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-8 py-20 text-center">
                                            <div class="flex flex-col items-center">
                                                <div class="w-16 h-16 bg-slate-50 text-slate-200 rounded-2xl flex items-center justify-center mb-4">
                                                    <i data-feather="package" class="w-8 h-8"></i>
                                                </div>
                                                <h4 class="text-lg font-bold text-slate-400">Warehouse Empty</h4>
                                                <p class="text-slate-300 text-sm mt-1">No items available at this source facility.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="p-8 bg-slate-50 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6">
                            <div id="selectionSummary" class="text-sm font-bold text-slate-400 italic">
                                No items selected for dispatch
                            </div>
                            <div class="flex gap-4 w-full md:w-auto">
                                <button type="submit" id="submitBtn" class="w-full md:w-auto flex items-center justify-center gap-3 px-10 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-[2rem] font-black shadow-xl shadow-blue-200 transition-all active:scale-95 text-lg disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                    <i data-feather="send" class="w-6 h-6"></i> Commit Dispatch
                                </button>
                                <a href="{{ route('stock-transfers.index') }}" class="w-full md:w-auto flex items-center justify-center gap-3 px-10 py-4 bg-white border-2 border-slate-200 text-slate-500 hover:bg-slate-50 rounded-[2rem] font-bold transition-all text-lg">
                                    <i data-feather="x" class="w-6 h-6"></i> Discard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.item-select');
    const submitBtn = document.getElementById('submitBtn');
    const summaryText = document.getElementById('selectionSummary');
    const searchInput = document.getElementById('inventorySearch');
    const tableRows = document.querySelectorAll('#inventoryTable tbody tr');

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            const index = this.dataset.index;
            const row = this.closest('tr');
            const inputs = document.querySelectorAll('.item-input-' + index);
            
            if (this.checked) {
                row.classList.add('bg-blue-50/50', 'border-blue-100');
            } else {
                row.classList.remove('bg-blue-50/50', 'border-blue-100');
            }

            inputs.forEach(input => {
                input.disabled = !this.checked;
                if (!this.checked && input.classList.contains('item-qty')) {
                    input.value = '';
                }
            });
            
            updateSummary();
        });
    });

    function updateSummary() {
        const checkedCount = document.querySelectorAll('.item-select:checked').length;
        if (submitBtn) submitBtn.disabled = checkedCount === 0;
        
        if (summaryText) {
            if (checkedCount === 0) {
                summaryText.textContent = 'No items selected for dispatch';
                summaryText.classList.replace('text-blue-600', 'text-slate-400');
            } else {
                summaryText.textContent = `${checkedCount} item(s) staged for movement`;
                summaryText.classList.replace('text-slate-400', 'text-blue-600');
            }
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    }

    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
</script>
@endpush
@endsection
