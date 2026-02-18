@extends('layouts.app')

@section('title', 'Record Income')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div>
        <div>
            @if(request('prefill_type') == 'store' && request('prefill_id'))
                <a href="{{ route('stores.show', request('prefill_id')) }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-600 transition-colors mb-2 text-xs font-bold uppercase tracking-wider">
                    <i data-feather="arrow-left" class="w-4 h-4"></i> Back to Store
                </a>
            @elseif(request('prefill_type') == 'warehouse' && request('prefill_id'))
                <a href="{{ route('warehouses.show', request('prefill_id')) }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-600 transition-colors mb-2 text-xs font-bold uppercase tracking-wider">
                    <i data-feather="arrow-left" class="w-4 h-4"></i> Back to Warehouse
                </a>
            @else
                <a href="{{ route('finance.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-600 transition-colors mb-2 text-xs font-bold uppercase tracking-wider">
                    <i data-feather="arrow-left" class="w-4 h-4"></i> Back to Finance
                </a>
            @endif
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Record Income</h1>
            <p class="text-slate-500 mt-2 font-medium text-sm">Record sales or other revenue for Stores/Warehouses with split payments.</p>
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
        <form action="{{ route('finance.income.store') }}" method="POST" id="incomeForm" class="space-y-8">
            @csrf
            @if(request('prefill_type') == 'store' && request('prefill_id'))
                <input type="hidden" name="redirect_to" value="{{ route('stores.show', request('prefill_id')) }}">
            @elseif(request('prefill_type') == 'warehouse' && request('prefill_id'))
                <input type="hidden" name="redirect_to" value="{{ route('warehouses.show', request('prefill_id')) }}">
            @endif

            <!-- Main Info -->
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Income Date</label>
                        <input type="date" name="income_date" value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Revenue Category</label>
                        <select name="revenue_account_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors">
                            @foreach($revenueAccounts as $account)
                                <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->code }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                     <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Description</label>
                     <input type="text" name="description" placeholder="e.g. Daily Sales Revenue" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors">
                </div>
            </div>

            <!-- Deposit Items (Splits) -->
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-slate-900">Deposit To (Split Payments)</h3>
                    <button type="button" id="addItemBtn" class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-bold text-xs uppercase tracking-wider transition-colors">
                        <i data-feather="plus" class="w-4 h-4"></i> Add Split
                    </button>
                </div>

                <div id="itemsContainer" class="space-y-4">
                    <!-- Items will be added here via JS -->
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100 flex justify-between items-center">
                    <div class="text-right flex-1">
                        <span class="text-slate-400 font-bold text-xs uppercase tracking-wider mr-2">Total Income</span>
                        <span class="text-2xl font-black text-slate-900" id="totalDisplay">RP 0</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('finance.index') }}" class="px-6 py-3 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl font-bold text-sm transition-all">Cancel</a>
                <button type="submit" class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-sm transition-all shadow-lg shadow-emerald-200" id="submitBtn">
                    Record Income
                </button>
            </div>
        </form>
    </div>
</div>

<template id="itemTemplate">
    <div class="item-row grid grid-cols-1 md:grid-cols-12 gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200 relative group animate-fade-in">
        <div class="md:col-span-4">
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Entity (Store/Warehouse)</label>
            <select class="entity-select w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors">
                <option value="">Select Entity...</option>
                <optgroup label="General Finance">
                    <option value="general-null" data-type="general" data-id="null">General Accounts / Office</option>
                </optgroup>
                <optgroup label="Stores">
                    @foreach($stores as $store)
                        <option value="store-{{ $store->id }}" data-type="store" data-id="{{ $store->id }}">{{ $store->name }}</option>
                    @endforeach
                </optgroup>
                <optgroup label="Warehouses">
                    @foreach($warehouses as $wh)
                        <option value="warehouse-{{ $wh->id }}" data-type="warehouse" data-id="{{ $wh->id }}">{{ $wh->name }}</option>
                    @endforeach
                </optgroup>
            </select>
        </div>
        <div class="md:col-span-4">
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Deposit Account (Cash/Bank)</label>
            <select name="items[INDEX][deposit_account_id]" class="deposit-account-select w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors disabled:opacity-50" disabled>
                <option value="">Select Entity First...</option>
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
    const generalAccounts = @json($generalAccounts);
    let itemCount = 0;
    const prefill = @json($prefill ?? []);
    const itemTemplate = document.getElementById('itemTemplate');
    const itemsContainer = document.getElementById('itemsContainer');
    const addItemBtn = document.getElementById('addItemBtn');
    const totalDisplay = document.getElementById('totalDisplay');

    function initPrefill() {
        if (prefill.type && prefill.id) {
            // Wait for DOM
            setTimeout(() => {
                const firstRow = document.querySelector('.item-row');
                if (firstRow) {
                    const entitySelect = firstRow.querySelector('.entity-select');
                    const val = `${prefill.type}-${prefill.id}`;
                    
                    // Check if option exists
                    const option = entitySelect.querySelector(`option[value="${val}"]`);
                    if (option) {
                        entitySelect.value = val;
                        entitySelect.dispatchEvent(new Event('change'));
                        
                        // Lock it
                        entitySelect.disabled = true;
                        
                        // We need to ensure this value is submitted or handled since disabled selects don't submit.
                        // However, for Multi-row input, usually we want the user to be able to add more rows.
                        // But if we are in "Record Income for Store A" mode, maybe we strictly want to record for Store A?
                        // For now, let's just pre-select and disable to prevent confusion, 
                        // but since it's an array input, we can't easily add hidden inputs for dynamic rows without complex logic.
                        // BETTER APPROACH: Just pre-select and let user change if they really want, OR don't disable.
                        // Let's just key it read-only-ish by not disabling but visual cue? 
                        // Or just pre-select and allow change. pre-select is enough for UX.
                        entitySelect.disabled = false; 
                    }
                }
            }, 100);
        }
    }

    // Add first item by default
    addItem();
    initPrefill();

    function getEntityAccounts(type, id) {
        if (type === 'general') {
            return generalAccounts;
        }
        let entity = null;
        if (type === 'store') {
            entity = stores.find(s => s.id == id);
        } else if (type === 'warehouse') {
            entity = warehouses.find(w => w.id == id);
        }
        return entity ? entity.accounts : [];
    }

    function addItem() {
        const index = itemCount++;
        const template = itemTemplate.content.cloneNode(true);
        const row = template.querySelector('.item-row');
        
        row.innerHTML = row.innerHTML.replace(/INDEX/g, index);
        
        const entitySelect = row.querySelector('.entity-select');
        const accountSelect = row.querySelector('.deposit-account-select');

        // Entity Change Handler
        entitySelect.addEventListener('change', () => {
             const selectedOption = entitySelect.options[entitySelect.selectedIndex];
             const type = selectedOption.getAttribute('data-type');
             const id = selectedOption.getAttribute('data-id');

             accountSelect.innerHTML = '<option value="">Select Account...</option>';

             if (type && id) {
                 const accounts = getEntityAccounts(type, id);
                 accounts.forEach(acc => {
                     const opt = document.createElement('option');
                     opt.value = acc.id;
                     opt.textContent = `${acc.name} (Bal: ${new Intl.NumberFormat('id-ID').format(acc.current_balance)})`;
                     accountSelect.appendChild(opt);
                 });
                 accountSelect.disabled = false;
             } else {
                 accountSelect.innerHTML = '<option value="">Select Entity First...</option>';
                 accountSelect.disabled = true;
             }
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
    
    // Add first item by default
    addItem();

</script>
@endpush
@endsection
