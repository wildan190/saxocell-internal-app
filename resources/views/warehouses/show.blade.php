@extends('layouts.app')

@section('title', 'Warehouse: ' . $warehouse->name)

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <a href="{{ route('home') }}">Home</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item">
        <a href="{{ route('warehouses.index') }}">Warehouses</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">{{ $warehouse->name }}</div>
</nav>
@endsection

@section('content')
<div class="main-content-inner content-wrapper bg-white lg:bg-transparent">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-slate-900 text-white rounded-full text-[10px] font-black uppercase tracking-widest mb-4">
                <i data-feather="database" class="w-3 h-3"></i> Central Logistics Hub
            </div>
            <h1 class="text-3xl md:text-5xl font-extrabold text-slate-900 tracking-tight">{{ $warehouse->name }}</h1>
            <p class="text-slate-500 mt-2 flex items-center gap-2">
                <i data-feather="map-pin" class="w-4 h-4 text-slate-400"></i>
                {{ $warehouse->address ?: 'No physical address recorded' }}
            </p>
        </div>
        
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('warehouses.edit', $warehouse) }}" class="flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-200 transition-all active:scale-95 leading-none text-sm">
                <i data-feather="edit-2" class="w-4 h-4 text-white/70"></i> Edit Hub
            </a>
            <a href="{{ route('warehouses.index') }}" class="flex items-center gap-2 px-6 py-2.5 bg-white border border-slate-200 hover:border-slate-300 text-slate-700 rounded-xl font-bold shadow-sm transition-all active:scale-95 leading-none text-sm">
                <i data-feather="arrow-left" class="w-4 h-4 text-slate-400"></i> Back
            </a>
        </div>
    </div>

    <!-- Stats Dashboard -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-200/60 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-slate-50 text-slate-400 rounded-2xl">
                    <i data-feather="hash" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Total SKUs</span>
                    <span class="block text-2xl font-black text-slate-800">{{ number_format($stats['total_skus']) }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-200/60 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-slate-900 text-white rounded-2xl">
                    <i data-feather="archive" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Total Stock Items</span>
                    <span class="block text-2xl font-black text-slate-800">{{ number_format($stats['total_items']) }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-200/60 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-amber-50 text-amber-500 rounded-2xl">
                    <i data-feather="alert-circle" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Low Stock</span>
                    <span class="block text-2xl font-black text-amber-600">{{ number_format($stats['low_stock']) }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-200/60 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-rose-50 text-rose-500 rounded-2xl">
                    <i data-feather="x-circle" class="w-6 h-6"></i>
                </div>
                <div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Critical/Empty</span>
                    <span class="block text-2xl font-black text-rose-600">{{ number_format($stats['out_of_stock']) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Main Inventory Content -->
        <div class="lg:col-span-8 space-y-8">
            <!-- Financial Wallet -->
            <div class="bg-slate-900 rounded-[2.5rem] shadow-xl overflow-hidden relative">
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
                
                <div class="p-8">
                     <div class="flex items-center justify-between mb-6 relative z-10">
                        <h3 class="text-sm font-black text-white uppercase tracking-widest flex items-center gap-3">
                            <span class="w-8 h-8 bg-white/10 text-blue-400 rounded-lg flex items-center justify-center">
                                <i data-feather="briefcase" class="w-4 h-4"></i>
                            </span>
                            Warehouse Wallet
                        </h3>
                         <div class="flex gap-2">
                             <button onclick="document.getElementById('add-account-modal').classList.remove('hidden')" class="px-4 py-2 bg-blue-500 hover:bg-blue-400 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition-all shadow-lg shadow-blue-900/20 flex items-center gap-2">
                                <i data-feather="plus-circle" class="w-3 h-3"></i> Add Account
                            </button>
                             <a href="{{ route('warehouses.income.create', $warehouse) }}" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition-all shadow-lg shadow-emerald-900/20 flex items-center gap-2">
                                <i data-feather="plus" class="w-3 h-3"></i> Income
                            </a>
                            <a href="{{ route('warehouses.transfer.create', $warehouse) }}" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition-all flex items-center gap-2">
                                <i data-feather="arrow-right" class="w-3 h-3"></i> Transfer
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
                        @foreach($warehouse->accounts as $account)
                        <div class="bg-white/5 border border-white/10 p-5 rounded-2xl">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">{{ $account->name }}</p>
                            <p class="text-2xl font-black text-white">RP {{ number_format($account->current_balance, 0, ',', '.') }}</p>
                            <p class="text-[10px] text-slate-500 font-mono mt-2">{{ $account->code }}</p>
                        </div>
                        @endforeach
                         @if($warehouse->accounts->isEmpty())
                        <div class="col-span-2 text-center py-4 text-slate-500 text-sm italic">
                            No accounts linked to this warehouse. Click "Add Account" to get started.
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Add Account Modal -->
            <div id="add-account-modal" class="hidden fixed inset-0 z-[100] overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen px-4">
                    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm shadow-2xl transition-opacity" onclick="document.getElementById('add-account-modal').classList.add('hidden')"></div>
                    
                    <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-2xl transform transition-all sm:max-w-lg sm:w-full relative z-20 border border-slate-100">
                        <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Link New Account</h3>
                            <button onclick="document.getElementById('add-account-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                                <i data-feather="x" class="w-5 h-5"></i>
                            </button>
                        </div>
                        
                        <form action="{{ route('warehouses.accounts.store', $warehouse) }}" method="POST" class="p-8 space-y-5">
                            @csrf
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Account Code</label>
                                <input type="text" name="code" placeholder="e.g. 1101-WHS" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors" required>
                            </div>
                            
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Account Name</label>
                                <input type="text" name="name" placeholder="e.g. Warehouse Petty Cash" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors" required>
                            </div>
                            
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Account Type</label>
                                <select name="type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors" required>
                                    <option value="asset">Asset (Cash/Bank)</option>
                                    <option value="liability">Liability</option>
                                    <option value="equity">Equity</option>
                                    <option value="revenue">Revenue</option>
                                    <option value="expense">Expense</option>
                                </select>
                            </div>
                            
                            <div class="pt-4">
                                <button type="submit" class="w-full py-4 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-blue-600 transition-all shadow-lg shadow-blue-100">
                                    Create & Link Account
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Inventory Assets -->
            <div class="bg-white rounded-[2.5rem] border border-slate-200/60 shadow-xl overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center gap-3">
                        <span class="w-8 h-8 bg-slate-900 text-white rounded-lg flex items-center justify-center">
                            <i data-feather="package" class="w-4 h-4 text-white"></i>
                        </span>
                        Inventory Assets
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Product & Identity</th>
                                <th class="px-4 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Available Qty</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Last Movement</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($warehouse->inventory as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center shrink-0 group-hover:bg-white transition-colors border border-transparent group-hover:border-slate-200 shadow-sm">
                                            <i data-feather="box" class="w-6 h-6 text-slate-400"></i>
                                        </div>
                                        <div>
                                            <span class="block font-bold text-slate-800 group-hover:text-blue-600 transition-colors">{{ $item->product->name }}</span>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $item->product->sku }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-5 text-right font-mono text-lg font-black tracking-tight">
                                    <span class="{{ $item->quantity <= 10 ? 'text-amber-500' : ($item->quantity <= 0 ? 'text-rose-500' : 'text-slate-900') }}">
                                        {{ number_format($item->quantity) }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">{{ $item->updated_at->diffForHumans() }}</span>
                                    <span class="text-xs text-slate-300">{{ $item->updated_at->format('M d, H:i') }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-8 py-20 text-center">
                                    <div class="max-w-xs mx-auto text-center">
                                        <div class="w-16 h-16 bg-slate-50 text-slate-200 rounded-3xl flex items-center justify-center mx-auto mb-6">
                                            <i data-feather="database" class="w-8 h-8 text-slate-100"></i>
                                        </div>
                                        <h4 class="text-slate-900 font-bold mb-1 font-mono uppercase tracking-widest text-sm">Hub Empty</h4>
                                        <p class="text-slate-400 text-sm">No inventory records linked to this hub.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions & Metadata -->
        <div class="lg:col-span-4 space-y-8">
            <!-- Details Card -->
            <div class="bg-white rounded-[2rem] border border-slate-200/60 shadow-sm p-8">
                <h3 class="text-xs font-black text-slate-800 uppercase tracking-[0.2em] mb-6 flex items-center gap-3">
                    <i data-feather="file-text" class="w-4 h-4 text-slate-400"></i> Hub Metadata
                </h3>
                
                <div class="space-y-6">
                    <div>
                        <label class="text-[10px] font-black text-slate-300 uppercase tracking-widest block mb-2">Description</label>
                        <div class="text-slate-600 leading-relaxed text-sm p-4 bg-slate-50 rounded-2xl border border-slate-100 italic">
                            {{ $warehouse->description ?: 'No operational description provided.' }}
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-400 font-medium">Internal Tag</span>
                            <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-[10px] font-black uppercase tracking-widest">WHS-CORE</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Operations Box -->
            <div class="bg-indigo-600 rounded-[2rem] p-8 shadow-xl shadow-indigo-100 relative overflow-hidden group">
                <div class="relative z-10">
                    <h3 class="text-xs font-black text-indigo-200 uppercase tracking-[0.2em] mb-6">Operations Hub</h3>
                    <div class="space-y-3">
                        <a href="{{ route('stock-opnames.create') }}?warehouse_id={{ $warehouse->id }}" class="flex items-center justify-between p-4 bg-white/10 hover:bg-white/20 text-white rounded-2xl transition-all border border-white/10 group/item">
                            <div class="flex items-center gap-3">
                                <i data-feather="check-square" class="w-4 h-4 text-white/50 group-hover/item:text-white transition-colors"></i>
                                <span class="font-bold text-sm">Perform Stock Opname</span>
                            </div>
                            <i data-feather="arrow-right" class="w-4 h-4 text-white/30 group-hover/item:text-white transition-transform duration-300 group-hover/item:translate-x-1"></i>
                        </a>

                        <a href="{{ route('stock-transfers.create') }}" class="flex items-center justify-between p-4 bg-white/10 hover:bg-white/20 text-white rounded-2xl transition-all border border-white/10 group/item">
                            <div class="flex items-center gap-3">
                                <i data-feather="send" class="w-4 h-4 text-white/50 group-hover/item:text-white transition-colors"></i>
                                <span class="font-bold text-sm">Dispatch Inventory</span>
                            </div>
                            <i data-feather="arrow-right" class="w-4 h-4 text-white/30 group-hover/item:text-white transition-transform duration-300 group-hover/item:translate-x-1"></i>
                        </a>
                    </div>
                </div>
                <!-- Decorative Circle -->
                <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
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