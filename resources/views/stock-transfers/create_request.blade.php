@extends('layouts.app')

@section('title', 'Request Inventory Replenishment')

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
    <div class="breadcrumb-item active">Request Stock</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
        <div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight text-center md:text-left">Stock Replenishment</h1>
            <p class="text-slate-500 mt-2 font-medium text-center md:text-left">Request items from central warehouses to fulfill your store's requirements.</p>
        </div>
        
        <div class="flex justify-center md:justify-end">
            <a href="{{ route('stock-transfers.index') }}" class="flex items-center gap-2 px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl font-black transition-all active:scale-95">
                <i data-feather="arrow-left" class="w-4 h-4"></i> Back to Fleet
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-8 p-6 bg-red-50 border-2 border-red-100 rounded-[2rem] text-red-700 animate-in fade-in slide-in-from-top-4 duration-500">
            <h4 class="font-black text-sm uppercase tracking-widest mb-3 flex items-center gap-2">
                <i data-feather="alert-octagon" class="w-4 h-4"></i> Validation Errors
            </h4>
            <ul class="text-sm font-bold flex flex-col gap-1">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('stock-transfers.store-request') }}" method="POST">
        @csrf
        
        <div class="space-y-8">
            <!-- Route Section -->
            <div class="bg-white/95 backdrop-blur-xl rounded-[2.5rem] border border-slate-200/60 shadow-xl overflow-hidden p-8 md:p-10">
                <h3 class="text-xl font-black text-slate-900 mb-8 flex items-center gap-3">
                    <span class="p-2 bg-blue-50 text-blue-600 rounded-xl"><i data-feather="map-pin" class="w-5 h-5"></i></span>
                    Logistics Route
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Ship to Store (Destination)</label>
                        <select name="destination_store_id" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none" required>
                            <option value="">-- Choose Store --</option>
                            @foreach($stores as $store)
                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Fulfillment Center (Source)</label>
                        <select name="source_warehouse_id" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none" required>
                            <option value="">-- Choose Warehouse --</option>
                            @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Items Section -->
            <div class="bg-white/95 backdrop-blur-xl rounded-[2.5rem] border border-slate-200/60 shadow-xl overflow-hidden">
                <div class="p-8 md:p-10 border-b border-slate-100 bg-slate-50/30 flex justify-between items-center">
                    <h3 class="text-xl font-black text-slate-900 flex items-center gap-3">
                        <span class="p-2 bg-emerald-50 text-emerald-600 rounded-xl"><i data-feather="list" class="w-5 h-5"></i></span>
                        Inventory Checklist
                    </h3>
                    <button type="button" id="add-item-btn" class="flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-black text-xs uppercase tracking-widest transition-all active:scale-95 shadow-lg shadow-emerald-100">
                        <i data-feather="plus" class="w-4 h-4"></i> Add Item
                    </button>
                </div>

                <div id="items-container" class="p-8 md:p-10 space-y-4">
                    <div class="item-row group flex flex-col md:flex-row gap-4 p-6 bg-slate-50 hover:bg-white hover:shadow-lg border border-transparent hover:border-slate-100 rounded-3xl transition-all duration-300">
                        <div class="flex-1 space-y-2">
                            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Product Specifications</label>
                            <select name="items[0][product_id]" class="w-full px-5 py-3.5 bg-white border-2 border-slate-100 focus:border-emerald-500 rounded-xl font-bold text-slate-800 outline-none" required>
                                <option value="">-- Choose a Product --</option>
                                @foreach(\App\Models\Product::all() as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:w-32 space-y-2">
                            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Quantity</label>
                            <input type="number" name="items[0][quantity]" class="w-full px-5 py-3.5 bg-white border-2 border-slate-100 focus:border-emerald-500 rounded-xl font-bold text-slate-800 outline-none" placeholder="0" min="1" required>
                        </div>
                        <div class="md:flex items-end hidden">
                            <button type="button" class="remove-row p-3.5 bg-red-100 hover:bg-red-500 text-red-500 hover:text-white rounded-xl transition-all duration-300 opacity-0 group-hover:opacity-100">
                                <i data-feather="trash-2" class="w-5 h-5"></i>
                            </button>
                        </div>
                        <!-- Responsive Remove for Mobile -->
                        <div class="md:hidden">
                            <button type="button" class="remove-row w-full py-3 bg-red-50 text-red-500 font-bold rounded-xl flex items-center justify-center gap-2">
                                <i data-feather="trash-2" class="w-4 h-4"></i> Remove Item
                            </button>
                        </div>
                    </div>
                </div>

                <div class="p-8 md:p-10 bg-slate-50 border-t border-slate-100 flex flex-col md:flex-row gap-4">
                    <button type="submit" class="w-full md:w-auto flex items-center justify-center gap-3 px-10 py-5 bg-blue-600 hover:bg-blue-700 text-white rounded-[2rem] font-black shadow-xl shadow-blue-200 transition-all active:scale-95 text-lg">
                        <i data-feather="send" class="w-6 h-6"></i> Dispatch Request
                    </button>
                    <a href="{{ route('stock-transfers.index') }}" class="w-full md:w-auto flex items-center justify-center gap-3 px-10 py-5 bg-white border-2 border-slate-200 text-slate-500 hover:bg-slate-50 rounded-[2rem] font-bold transition-all text-lg">
                        <i data-feather="x" class="w-6 h-6"></i> Discard
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = 1;
    const container = document.getElementById('items-container');
    const addBtn = document.getElementById('add-item-btn');

    addBtn.addEventListener('click', function() {
        const firstRow = container.querySelector('.item-row');
        const newRow = firstRow.cloneNode(true);
        
        // Reset values
        newRow.querySelector('select').name = `items[${itemIndex}][product_id]`;
        newRow.querySelector('select').value = "";
        newRow.querySelector('input').name = `items[${itemIndex}][quantity]`;
        newRow.querySelector('input').value = "";
        
        // Show remove buttons if multiple rows exist
        newRow.querySelectorAll('.remove-row').forEach(btn => {
            btn.parentElement.classList.remove('hidden');
        });
        
        container.appendChild(newRow);
        itemIndex++;
        
        attachRemoveListeners();
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });

    function attachRemoveListeners() {
        document.querySelectorAll('.remove-row').forEach(btn => {
            btn.onclick = function() {
                if (container.querySelectorAll('.item-row').length > 1) {
                    this.closest('.item-row').remove();
                } else {
                    alert('You must request at least one item.');
                }
            };
        });
    }
    
    attachRemoveListeners();
});
</script>
@endpush
@endsection
