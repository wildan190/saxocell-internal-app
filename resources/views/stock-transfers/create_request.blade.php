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
                    <div>
                        <h3 class="text-xl font-black text-slate-900 flex items-center gap-3">
                            <span class="p-2 bg-emerald-50 text-emerald-600 rounded-xl"><i data-feather="list" class="w-5 h-5"></i></span>
                            Inventory Checklist
                        </h3>
                        <p class="text-xs text-slate-400 font-bold uppercase mt-2 ml-12">Select items and quantities needed</p>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" onclick="clearAllRows()" class="hidden md:flex items-center gap-2 px-5 py-2.5 bg-white border-2 border-slate-200 hover:border-red-200 text-slate-400 hover:text-red-500 rounded-xl font-black text-xs uppercase tracking-widest transition-all active:scale-95">
                            <i data-feather="trash-2" class="w-4 h-4"></i> Clear
                        </button>
                        <button type="button" onclick="startRequestScan()" class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-black text-xs uppercase tracking-widest transition-all active:scale-95 shadow-lg shadow-blue-100">
                            <i data-feather="maximize" class="w-4 h-4"></i> Scan & Add
                        </button>
                        <button type="button" id="add-item-btn" class="flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-black text-xs uppercase tracking-widest transition-all active:scale-95 shadow-lg shadow-emerald-100">
                            <i data-feather="plus" class="w-4 h-4"></i> Add Row
                        </button>
                    </div>
                </div>

                <div id="items-container" class="p-8 md:p-10 space-y-4">
                    <!-- Row Template / First Row -->
                    <div class="item-row group flex flex-col md:flex-row gap-6 p-6 bg-slate-50/50 hover:bg-white hover:shadow-2xl hover:shadow-slate-200/50 border-2 border-transparent hover:border-blue-100 rounded-[2rem] transition-all duration-500 relative">
                        <div class="flex-1 space-y-3">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 flex items-center gap-2">
                                <i data-feather="package" class="w-3 h-3 text-blue-400"></i> Product Specifications
                            </label>
                            <div class="relative">
                                <select name="items[0][product_id]" class="w-full px-6 py-4 bg-white border-2 border-slate-100 focus:border-blue-500 rounded-2xl font-bold text-slate-800 outline-none product-select appearance-none transition-all shadow-sm" required>
                                    <option value="">-- Choose a Product --</option>
                                    @foreach($products as $product)
                                        @if($product->variants->count() > 0)
                                            @foreach($product->variants as $variant)
                                                <option value="{{ $product->id }}" data-variant-id="{{ $variant->id }}" data-sku="{{ $variant->sku }}">
                                                    {{ $product->name }} ({{ $variant->name }}) - {{ $variant->sku }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="{{ $product->id }}" data-variant-id="" data-sku="{{ $product->sku }}">
                                                {{ $product->name }} - {{ $product->sku }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-300">
                                    <i data-feather="chevron-down" class="w-5 h-5"></i>
                                </div>
                            </div>
                            <input type="hidden" name="items[0][product_variant_id]" class="variant-id-input">
                        </div>
                        
                        <div class="md:w-40 space-y-3">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 flex items-center gap-2">
                                <i data-feather="activity" class="w-3 h-3 text-blue-400"></i> Quantity
                            </label>
                            <div class="flex items-center bg-white border-2 border-slate-100 focus-within:border-blue-500 rounded-2xl p-1 shadow-sm transition-all">
                                <button type="button" class="qty-minus w-10 h-10 flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">
                                    <i data-feather="minus" class="w-4 h-4"></i>
                                </button>
                                <input type="number" name="items[0][quantity]" class="w-full bg-transparent border-0 text-center font-black text-slate-800 focus:ring-0 p-0 qty-input" placeholder="0" min="1" required>
                                <button type="button" class="qty-plus w-10 h-10 flex items-center justify-center text-slate-400 hover:text-emerald-500 hover:bg-emerald-50 rounded-xl transition-all">
                                    <i data-feather="plus" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>

                        <div class="md:flex items-end hidden pb-1">
                            <button type="button" class="remove-row p-4 bg-slate-100 hover:bg-red-500 text-slate-400 hover:text-white rounded-2xl transition-all duration-300 opacity-0 group-hover:opacity-100 scale-90 group-hover:scale-100">
                                <i data-feather="trash-2" class="w-5 h-5"></i>
                            </button>
                        </div>
                        
                        <!-- Mobile Remove -->
                        <div class="md:hidden mt-2">
                            <button type="button" class="remove-row w-full py-4 bg-red-50 text-red-500 font-black rounded-2xl flex items-center justify-center gap-2 text-xs uppercase tracking-widest border-2 border-red-100/50">
                                <i data-feather="trash-2" class="w-4 h-4"></i> Remove Item
                            </button>
                        </div>
                    </div>
                </div>

                <div class="p-8 md:p-10 bg-slate-50 border-t border-slate-100 flex flex-col md:flex-row gap-4">
                    <button type="submit" class="w-full md:w-auto flex items-center justify-center gap-3 px-12 py-5 bg-blue-600 hover:bg-blue-700 text-white rounded-[2rem] font-black shadow-xl shadow-blue-200 transition-all active:scale-95 text-lg group">
                        <i data-feather="send" class="w-6 h-6 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i> Dispatch Request
                    </button>
                    <a href="{{ route('stock-transfers.index') }}" class="w-full md:w-auto flex items-center justify-center gap-3 px-10 py-5 bg-white border-2 border-slate-200 text-slate-500 hover:bg-slate-50 rounded-[2rem] font-bold transition-all text-lg min-w-[200px]">
                        Discard
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = 1;
    const container = document.getElementById('items-container');
    const addBtn = document.getElementById('add-item-btn');

    function syncVariantId(row) {
        const select = row.querySelector('.product-select');
        const variantInput = row.querySelector('.variant-id-input');
        const selectedOption = select.options[select.selectedIndex];
        if (selectedOption) {
            variantInput.value = selectedOption.dataset.variantId || "";
        }
    }

    // Event Delegation for row actions
    container.addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select')) {
            syncVariantId(e.target.closest('.item-row'));
        }
    });

    container.addEventListener('click', function(e) {
        const btn = e.target.closest('button');
        if (!btn) return;

        const row = btn.closest('.item-row');
        if (btn.classList.contains('qty-plus')) {
            const input = row.querySelector('.qty-input');
            input.value = (parseInt(input.value) || 0) + 1;
        } else if (btn.classList.contains('qty-minus')) {
            const input = row.querySelector('.qty-input');
            input.value = Math.max(1, (parseInt(input.value) || 0) - 1);
        } else if (btn.classList.contains('remove-row')) {
            if (container.querySelectorAll('.item-row').length > 1) {
                row.style.transform = 'scale(0.9)';
                row.style.opacity = '0';
                setTimeout(() => row.remove(), 300);
            } else {
                row.querySelector('.product-select').value = "";
                row.querySelector('.variant-id-input').value = "";
                row.querySelector('.qty-input').value = "";
            }
        }
    });

    window.clearAllRows = function() {
        if (!confirm('Clear all items from the list?')) return;
        const rows = container.querySelectorAll('.item-row');
        rows.forEach((row, index) => {
            if (index === 0) {
                row.querySelector('.product-select').value = "";
                row.querySelector('.variant-id-input').value = "";
                row.querySelector('.qty-input').value = "";
            } else {
                row.remove();
            }
        });
    };

    addBtn.addEventListener('click', function() {
        addRow();
    });

    function addRow(selectedSku = null) {
        const firstRow = container.querySelector('.item-row');
        const newRow = firstRow.cloneNode(true);
        const index = itemIndex++;
        
        // Reset and Update Names
        const select = newRow.querySelector('.product-select');
        select.name = `items[${index}][product_id]`;
        select.value = "";
        
        const variantInput = newRow.querySelector('.variant-id-input');
        variantInput.name = `items[${index}][product_variant_id]`;
        variantInput.value = "";

        const qtyInput = newRow.querySelector('.qty-input');
        qtyInput.name = `items[${index}][quantity]`;
        qtyInput.value = selectedSku ? 1 : "";
        
        if (selectedSku) {
            for (let i = 0; i < select.options.length; i++) {
                if (select.options[i].dataset.sku === selectedSku) {
                    select.selectedIndex = i;
                    variantInput.value = select.options[i].dataset.variantId || "";
                    break;
                }
            }
        }

        // Animation
        newRow.style.opacity = '0';
        newRow.style.transform = 'translateY(10px)';
        container.appendChild(newRow);
        
        setTimeout(() => {
            newRow.style.opacity = '1';
            newRow.style.transform = 'translateY(0)';
        }, 10);

        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        return newRow;
    }

    window.startRequestScan = function() {
        window.openQRScanner((sku) => {
            let found = false;
            const rows = container.querySelectorAll('.item-row');
            
            rows.forEach(row => {
                const select = row.querySelector('.product-select');
                const selectedOption = select.options[select.selectedIndex];
                if (selectedOption && selectedOption.dataset.sku === sku) {
                    const qtyInput = row.querySelector('.qty-input');
                    qtyInput.value = (parseInt(qtyInput.value) || 0) + 1;
                    
                    highlightRow(row);
                    found = true;
                }
            });

            if (!found) {
                // Check if SKU exists in catalog
                const firstSelect = container.querySelector('.product-select');
                let foundMatch = false;
                for (let i = 0; i < firstSelect.options.length; i++) {
                    if (firstSelect.options[i].dataset.sku === sku) {
                        foundMatch = true;
                        break;
                    }
                }

                if (foundMatch) {
                    const newRow = addRow(sku);
                    highlightRow(newRow);
                } else {
                    alert("SKU '" + sku + "' not found in product database.");
                }
            }

            setTimeout(() => startRequestScan(), 800);
        });
    };

    function highlightRow(row) {
        row.scrollIntoView({ behavior: 'smooth', block: 'center' });
        row.classList.add('border-blue-500', 'bg-blue-50/50');
        row.style.transform = 'scale(1.02)';
        
        setTimeout(() => {
            row.classList.remove('border-blue-500', 'bg-blue-50/50');
            row.style.transform = '';
        }, 800);
    }
    
    // Initial sync for first row
    syncVariantId(container.querySelector('.item-row'));
});
</script>
@endpush
