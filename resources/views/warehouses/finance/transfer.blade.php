@extends('layouts.app')

@section('title', 'Transfer Funds: ' . $warehouse->name)

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div>
            <a href="{{ route('warehouses.show', $warehouse) }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-600 transition-colors mb-2 text-xs font-bold uppercase tracking-wider">
                <i data-feather="arrow-left" class="w-4 h-4"></i> Back to Warehouse
            </a>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Transfer Funds</h1>
            <p class="text-slate-500 mt-2 font-medium text-sm">Transfer from {{ $warehouse->name }} to another entity.</p>
        </div>
    </div>

    <div class="max-w-3xl mx-auto">
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

        <form action="{{ route('warehouses.transfer.store', $warehouse) }}" method="POST" class="space-y-6">
            @csrf

            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl space-y-6">
                
                <!-- Date & Description -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Date</label>
                        <input type="date" name="transfer_date" value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Description</label>
                        <input type="text" name="description" placeholder="e.g. Replenish Store Float" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors">
                    </div>
                </div>

                <hr class="border-slate-100">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Source (Fixed) -->
                    <div class="space-y-4">
                        <h3 class="font-bold text-slate-900 flex items-center gap-2">
                            <i data-feather="upload" class="w-4 h-4 text-rose-500"></i> From (This Warehouse)
                        </h3>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Source Account</label>
                            <select name="source_account_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors">
                                @foreach($warehouse->accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }} (Bal: {{ number_format($account->current_balance) }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Destination (Dynamic) -->
                    <div class="space-y-4">
                         <h3 class="font-bold text-slate-900 flex items-center gap-2">
                            <i data-feather="download" class="w-4 h-4 text-emerald-500"></i> To (Destination)
                        </h3>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Destination Type</label>
                            <select id="destType" name="destination_type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors mb-3">
                                <option value="store">Store</option>
                                <option value="warehouse">Other Warehouse</option>
                            </select>
                            
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Destination Entity</label>
                            <select id="destId" name="destination_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors mb-3">
                                <option value="">Select...</option>
                            </select>

                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Destination Account</label>
                            <select id="destAccountId" name="destination_account_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors disabled:opacity-50" disabled>
                                <option value="">Select Entity First...</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Amount (RP)</label>
                    <input type="number" name="amount" step="0.01" min="0" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-black text-xl text-slate-900 focus:outline-none focus:border-blue-500 transition-colors" placeholder="0">
                </div>

            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('warehouses.show', $warehouse) }}" class="px-6 py-3 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl font-bold text-sm transition-all">Cancel</a>
                <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm transition-all shadow-lg shadow-blue-200">
                    Process Transfer
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const stores = @json($stores);
    const warehouses = @json($warehouses);
    
    // Elements
    const destTypeSelect = document.getElementById('destType');
    const destIdSelect = document.getElementById('destId');
    const destAccountIdSelect = document.getElementById('destAccountId');

    function populateEntitySelect() {
        const type = destTypeSelect.value;
        const list = type === 'store' ? stores : warehouses;
        
        destIdSelect.innerHTML = '<option value="">Select ' + (type === 'store' ? 'Store' : 'Warehouse') + '...</option>';
        
        list.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.id;
            opt.textContent = item.name;
            destIdSelect.appendChild(opt);
        });
        
        // Reset Account Select
        destAccountIdSelect.innerHTML = '<option value="">Select Entity First...</option>';
        destAccountIdSelect.disabled = true;
    }

    function populateAccountSelect() {
        const type = destTypeSelect.value;
        const id = destIdSelect.value;
        
        if (!id) {
            destAccountIdSelect.innerHTML = '<option value="">Select Entity First...</option>';
            destAccountIdSelect.disabled = true;
            return;
        }

        const list = type === 'store' ? stores : warehouses;
        const entity = list.find(item => item.id == id);

        destAccountIdSelect.innerHTML = '<option value="">Select Account...</option>';
        
        if (entity && entity.accounts) {
            entity.accounts.forEach(acc => {
                const opt = document.createElement('option');
                opt.value = acc.id;
                opt.textContent = acc.name;
                destAccountIdSelect.appendChild(opt);
            });
            destAccountIdSelect.disabled = false;
        } else {
             destAccountIdSelect.innerHTML = '<option value="">No Accounts Found</option>';
        }
    }

    destTypeSelect.addEventListener('change', populateEntitySelect);
    destIdSelect.addEventListener('change', populateAccountSelect);

    // Init
    populateEntitySelect();
    
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>
@endpush
@endsection
