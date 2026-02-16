@extends('layouts.app')

@section('title', 'New Internal Transfer')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div>
        <div>
            @if(request('prefill_source_type') == 'store' && request('prefill_source_id'))
                <a href="{{ route('stores.show', request('prefill_source_id')) }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-600 transition-colors mb-2 text-xs font-bold uppercase tracking-wider">
                    <i data-feather="arrow-left" class="w-4 h-4"></i> Back to Store
                </a>
            @elseif(request('prefill_source_type') == 'warehouse' && request('prefill_source_id'))
                <a href="{{ route('warehouses.show', request('prefill_source_id')) }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-600 transition-colors mb-2 text-xs font-bold uppercase tracking-wider">
                    <i data-feather="arrow-left" class="w-4 h-4"></i> Back to Warehouse
                </a>
            @else
                <a href="{{ route('finance.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-600 transition-colors mb-2 text-xs font-bold uppercase tracking-wider">
                    <i data-feather="arrow-left" class="w-4 h-4"></i> Back to Finance
                </a>
            @endif
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">New Internal Transfer</h1>
            <p class="text-slate-500 mt-2 font-medium text-sm">Transfer funds between Stores and Warehouses.</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <!-- Error Display -->
        @if ($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-600 p-4 rounded-xl mb-6 shadow-sm">
                <div class="flex items-center gap-2 mb-2">
                    <i data-feather="alert-circle" class="w-4 h-4"></i>
                    <span class="font-bold">Please correct the following errors:</span>
                </div>
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('finance.transfers.store') }}" method="POST" id="transferForm" class="space-y-8">
            @csrf
            @if(request('prefill_source_type') == 'store' && request('prefill_source_id'))
                <input type="hidden" name="redirect_to" value="{{ route('stores.show', request('prefill_source_id')) }}">
            @elseif(request('prefill_source_type') == 'warehouse' && request('prefill_source_id'))
                <input type="hidden" name="redirect_to" value="{{ route('warehouses.show', request('prefill_source_id')) }}">
            @endif

            <!-- Main Info -->
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Transfer Date</label>
                        <input type="date" name="transfer_date" value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Description</label>
                        <input type="text" name="description" placeholder="e.g. Weekly Cash Transfer" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Source Selection -->
                    <div class="space-y-4">
                        <h3 class="font-bold text-slate-900 flex items-center gap-2">
                            <i data-feather="upload" class="w-4 h-4 text-rose-500"></i> From (Source)
                        </h3>
                        <div>
                            <select id="sourceType" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors mb-3">
                                <option value="">Select Source Type...</option>
                                <option value="store">Store</option>
                                <option value="warehouse">Warehouse</option>
                            </select>
                            <select id="sourceId" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors disabled:opacity-50" disabled>
                                <option value="">Select Source...</option>
                            </select>
                        </div>
                    </div>

                    <!-- Destination Selection -->
                    <div class="space-y-4">
                        <h3 class="font-bold text-slate-900 flex items-center gap-2">
                            <i data-feather="download" class="w-4 h-4 text-emerald-500"></i> To (Destination)
                        </h3>
                        <div>
                            <select id="destType" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors mb-3">
                                <option value="">Select Destination Type...</option>
                                <option value="store">Store</option>
                                <option value="warehouse">Warehouse</option>
                            </select>
                            <select id="destId" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors disabled:opacity-50" disabled>
                                <option value="">Select Destination...</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transfer Items (Splits) -->
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-slate-900">Transfer Items</h3>
                    <button type="button" id="addItemBtn" class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-bold text-xs uppercase tracking-wider transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <i data-feather="plus" class="w-4 h-4"></i> Add Item
                    </button>
                </div>

                <div id="itemsContainer" class="space-y-4">
                    <!-- Items will be added here via JS -->
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100 flex justify-between items-center">
                    <div class="text-right flex-1">
                        <span class="text-slate-400 font-bold text-xs uppercase tracking-wider mr-2">Total Transfer</span>
                        <span class="text-2xl font-black text-slate-900" id="totalDisplay">RP 0</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('finance.index') }}" class="px-6 py-3 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl font-bold text-sm transition-all">Cancel</a>
                <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm transition-all shadow-lg shadow-blue-200 disabled:opacity-50 disabled:cursor-not-allowed" id="submitBtn">
                    Process Transfer
                </button>
            </div>
        </form>
    </div>
</div>

<template id="itemTemplate">
    <div class="item-row grid grid-cols-1 md:grid-cols-12 gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200 relative group animate-fade-in">
        <div class="md:col-span-4">
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Source Account</label>
            <select name="items[INDEX][source_account_id]" class="source-account-select w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors">
                <option value="">Select Account...</option>
            </select>
        </div>
        <div class="md:col-span-4">
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Destination Account</label>
            <select name="items[INDEX][destination_account_id]" class="dest-account-select w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors">
                <option value="">Select Account...</option>
            </select>
        </div>
        <div class="md:col-span-3">
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Amount</label>
            <input type="number" name="items[INDEX][amount]" step="0.01" min="0" class="amount-input w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-500 transition-colors" placeholder="0.00">
        </div>
        <div class="md:col-span-1 flex items-end justify-center pb-2">
            <button type="button" class="remove-item-btn text-slate-400 hover:text-rose-500 transition-colors p-2">
                <i data-feather="trash-2" class="w-4 h-4"></i>
            </button>
        </div>
    </div>
</template>

@push('scripts')
<script>
    const stores = @json($stores);
    const warehouses = @json($warehouses);
    let itemCount = 0;
    const prefill = @json($prefill ?? []);

    // DOM Elements
    const sourceTypeSelect = document.getElementById('sourceType');
    const sourceIdSelect = document.getElementById('sourceId');
    const destTypeSelect = document.getElementById('destType');
    const destIdSelect = document.getElementById('destId');
    const itemsContainer = document.getElementById('itemsContainer');
    const addItemBtn = document.getElementById('addItemBtn');
    const itemTemplate = document.getElementById('itemTemplate');
    const totalDisplay = document.getElementById('totalDisplay');

    function initPrefill() {
        if (prefill.source_type && prefill.source_id) {
            sourceTypeSelect.value = prefill.source_type;
            sourceTypeSelect.dispatchEvent(new Event('change'));
            
            sourceIdSelect.value = prefill.source_id;
            sourceIdSelect.dispatchEvent(new Event('change'));
            
            // Lock fields if prefilled
            sourceTypeSelect.disabled = true;
            sourceIdSelect.disabled = true;
            
            // Add hidden inputs to ensure values are submitted
            const form = document.getElementById('transferForm');
            
            if (!form.querySelector('input[name="source_type"]')) {
                const typeInput = document.createElement('input');
                typeInput.type = 'hidden';
                typeInput.name = 'source_type';
                typeInput.value = prefill.source_type;
                form.appendChild(typeInput);
            }

            if (!form.querySelector('input[name="source_id"]')) {
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'source_id';
                idInput.value = prefill.source_id;
                form.appendChild(idInput);
            }
        }
        
        if (prefill.dest_type && prefill.dest_id) {
             destTypeSelect.value = prefill.dest_type;
             destTypeSelect.dispatchEvent(new Event('change'));
             
             destIdSelect.value = prefill.dest_id;
             destIdSelect.dispatchEvent(new Event('change'));
        }
    }

    // State
    let currentSource = null;
    let currentDest = null;

    function getEntityList(type) {
        return type === 'store' ? stores : (type === 'warehouse' ? warehouses : []);
    }

    function populateSelect(select, items) {
        const currentVal = select.value;
        select.innerHTML = '<option value="">Select...</option>';
        items.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.name;
            select.appendChild(option);
        });
        // Try to keep selection if valid
        if (items.some(i => i.id == currentVal)) {
            select.value = currentVal;
        }
    }

    // Source Selection Logic
    sourceTypeSelect.addEventListener('change', () => {
        const type = sourceTypeSelect.value;
        if (!type) {
            sourceIdSelect.innerHTML = '<option value="">Select Source...</option>';
            sourceIdSelect.disabled = true;
            currentSource = null;
        } else {
            const list = getEntityList(type);
            populateSelect(sourceIdSelect, list);
            sourceIdSelect.disabled = false;
        }
        resetItems();
        updateAddItemBtn();
    });

    sourceIdSelect.addEventListener('change', () => {
        const id = sourceIdSelect.value;
        const type = sourceTypeSelect.value;
        const list = getEntityList(type);
        currentSource = list.find(show => show.id == id);
        resetItems();
        updateAddItemBtn();
    });

    // Destination Selection Logic
    destTypeSelect.addEventListener('change', () => {
        const type = destTypeSelect.value;
        if (!type) {
            destIdSelect.innerHTML = '<option value="">Select Destination...</option>';
            destIdSelect.disabled = true;
            currentDest = null;
        } else {
            const list = getEntityList(type);
            populateSelect(destIdSelect, list);
            destIdSelect.disabled = false;
        }
        resetItems(); // Clear items as destination accounts changed
        updateAddItemBtn();
    });

    destIdSelect.addEventListener('change', () => {
        const id = destIdSelect.value;
        const type = destTypeSelect.value;
        const list = getEntityList(type);
        currentDest = list.find(d => d.id == id);
        resetItems();
        updateAddItemBtn();
    });

    function updateAddItemBtn() {
        if (currentSource && currentDest) {
            addItemBtn.disabled = false;
            // Optionally auto-add first item if empty
            if (itemsContainer.children.length === 0) {
                addItem();
            }
        } else {
            addItemBtn.disabled = true;
        }
    }

    function resetItems() {
        itemsContainer.innerHTML = '';
        updateTotal();
    }

    function addItem() {
        if (!currentSource || !currentDest) return;

        const index = itemCount++;
        const template = itemTemplate.content.cloneNode(true);
        const row = template.querySelector('.item-row');
        
        // Update names
        row.innerHTML = row.innerHTML.replace(/INDEX/g, index);
        
        // Populate Source Accounts
        const sourceSelect = row.querySelector('.source-account-select');
        currentSource.accounts.forEach(acc => {
            const opt = document.createElement('option');
            opt.value = acc.id;
            opt.textContent = `${acc.name} (Bal: ${new Intl.NumberFormat('id-ID').format(acc.current_balance)})`;
            sourceSelect.appendChild(opt);
        });

        // Populate Destination Accounts
        const destSelect = row.querySelector('.dest-account-select');
        currentDest.accounts.forEach(acc => {
            const opt = document.createElement('option');
            opt.value = acc.id;
            opt.textContent = acc.name;
            destSelect.appendChild(opt);
        });

        // Add event listeners
        row.querySelector('.remove-item-btn').addEventListener('click', () => {
            row.remove();
            updateTotal();
        });

        row.querySelector('.amount-input').addEventListener('input', updateTotal);

        itemsContainer.appendChild(row);
        
        if (window.feather) feather.replace();
    }

    addItemBtn.addEventListener('click', addItem);

    function updateTotal() {
        const inputs = document.querySelectorAll('.amount-input');
        let total = 0;
        inputs.forEach(input => {
            const val = parseFloat(input.value) || 0;
            total += val;
        });
        totalDisplay.textContent = 'RP ' + new Intl.NumberFormat('id-ID').format(total);
    }
    
    // Initialize Prefill AFTER listeners
    initPrefill();

</script>
@endpush
@endsection
