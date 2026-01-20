@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <span>Home</span>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">Dashboard</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Welcome Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Welcome back, {{ Auth::user()->name }}!</h1>
            <p class="page-subtitle">Here's what's happening with your inventory today.</p>
        </div>
        <div class="action-bar-header">
            <span class="text-sm font-medium text-slate-500 bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm">
                <i data-feather="calendar" class="w-4 h-4 inline-block mr-1 mb-0.5"></i>
                {{ now()->format('l, d F Y') }}
            </span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Products -->
        <div class="card p-6 border-b-4 border-b-blue-500">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Products</p>
                    <h3 class="text-2xl font-bold text-slate-800">{{ \App\Models\Product::count() ?? '0' }}</h3>
                </div>
                <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                    <i data-feather="package" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="text-xs text-slate-500">
                Active items in catalog
            </div>
        </div>

        <!-- Total Warehouses -->
        <div class="card p-6 border-b-4 border-b-emerald-500">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Warehouses</p>
                    <h3 class="text-2xl font-bold text-slate-800">{{ \App\Models\Warehouse::count() ?? '0' }}</h3>
                </div>
                <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
                    <i data-feather="archive" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="text-xs text-slate-500">
                Storage locations
            </div>
        </div>

        <!-- Total Stores -->
        <div class="card p-6 border-b-4 border-b-violet-500">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Stores</p>
                    <h3 class="text-2xl font-bold text-slate-800">{{ \App\Models\Store::count() ?? '0' }}</h3>
                </div>
                <div class="p-2 bg-violet-50 text-violet-600 rounded-lg">
                    <i data-feather="shopping-bag" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="text-xs text-slate-500">
                Retail outlets
            </div>
        </div>

        <!-- Pending Requests -->
        <div class="card p-6 border-b-4 border-b-amber-500">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Pending Requests</p>
                    <h3 class="text-2xl font-bold text-slate-800">
                        {{ \App\Models\StockTransfer::where('status', 'requested')->count() ?? '0' }}
                    </h3>
                </div>
                <div class="p-2 bg-amber-50 text-amber-600 rounded-lg">
                    <i data-feather="clock" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="text-xs text-slate-500">
                Awaiting approval
            </div>
        </div>
    </div>

    <!-- Products Needing Review Section -->
    @if(isset($needsReviewCount) && $needsReviewCount > 0)
    <div class="mb-8">
        <div class="card overflow-hidden border-l-4 border-l-amber-500 shadow-md">
            <div class="card-header border-b border-slate-100 p-6 flex justify-between items-center bg-amber-50/10">
                <h3 class="font-bold text-lg text-slate-800 flex items-center gap-2">
                    <i data-feather="alert-triangle" class="w-5 h-5 text-amber-500"></i>
                    Price Review Required
                </h3>
                <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-lg text-xs font-black uppercase tracking-widest">
                    {{ $needsReviewCount }} PENDING
                </span>
            </div>
            <div class="p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="text-[10px] font-black uppercase tracking-widest px-6 py-3">Product Item</th>
                                <th class="text-[10px] font-black uppercase tracking-widest px-6 py-3">Suggested Price</th>
                                <th class="text-[10px] font-black uppercase tracking-widest px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($productsNeedingReview as $product)
                            <tr class="hover:bg-slate-50/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800">{{ $product->name }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $product->sku }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-black text-emerald-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    <div class="text-[9px] text-slate-500 mt-0.5 uppercase tracking-tighter font-bold">COST: Rp {{ number_format($product->cost_price, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary btn-sm px-4 py-2 text-[10px] font-black uppercase tracking-widest rounded-lg">
                                        Adjust Price
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($needsReviewCount > 5)
                <div class="p-4 text-center border-t border-slate-50 bg-slate-50/30">
                    <a href="{{ route('products.needs-review') }}" class="text-xs font-black text-blue-600 hover:text-blue-700 uppercase tracking-widest flex items-center justify-center gap-2">
                        View All {{ $needsReviewCount }} Products <i data-feather="arrow-right" class="w-3 h-3"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Quick Actions -->
        <div class="lg:col-span-2">
            <div class="card h-full">
                <div class="card-header border-b border-slate-100 p-6 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-slate-800 flex items-center gap-2">
                        <i data-feather="zap" class="w-5 h-5 text-amber-500"></i>
                        Quick Actions
                    </h3>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <a href="{{ route('stock-transfers.create') }}" class="group p-4 rounded-xl border border-slate-200 hover:border-blue-500 hover:bg-blue-50 transition-all flex items-start gap-4">
                        <div class="p-3 bg-blue-100 text-blue-600 rounded-lg group-hover:bg-blue-500 group-hover:text-white transition-colors">
                            <i data-feather="truck" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 group-hover:text-blue-700">New Transfer</h4>
                            <p class="text-xs text-slate-500 mt-1">Move stock between locations</p>
                        </div>
                    </a>

                    <a href="{{ route('stock-transfers.create-request') }}" class="group p-4 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50 transition-all flex items-start gap-4">
                        <div class="p-3 bg-emerald-100 text-emerald-600 rounded-lg group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                            <i data-feather="download" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 group-hover:text-emerald-700">Request Stock</h4>
                            <p class="text-xs text-slate-500 mt-1">Resupply your store</p>
                        </div>
                    </a>

                    <a href="{{ route('stock-opnames.create') }}" class="group p-4 rounded-xl border border-slate-200 hover:border-violet-500 hover:bg-violet-50 transition-all flex items-start gap-4">
                        <div class="p-3 bg-violet-100 text-violet-600 rounded-lg group-hover:bg-violet-500 group-hover:text-white transition-colors">
                            <i data-feather="check-square" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 group-hover:text-violet-700">Stock Opname</h4>
                            <p class="text-xs text-slate-500 mt-1">Start stock counting session</p>
                        </div>
                    </a>

                    <a href="{{ route('products.index') }}" class="group p-4 rounded-xl border border-slate-200 hover:border-slate-500 hover:bg-slate-50 transition-all flex items-start gap-4">
                        <div class="p-3 bg-slate-100 text-slate-600 rounded-lg group-hover:bg-slate-500 group-hover:text-white transition-colors">
                            <i data-feather="package" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 group-hover:text-slate-700">Product Catalog</h4>
                            <p class="text-xs text-slate-500 mt-1">Manage items & prices</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activity Feed -->
        <div class="lg:col-span-1">
            <div class="card h-full">
                <div class="card-header border-b border-slate-100 p-6">
                    <h3 class="font-bold text-lg text-slate-800 flex items-center gap-2">
                        <i data-feather="activity" class="w-5 h-5 text-blue-500"></i>
                        Recent Activity
                    </h3>
                </div>
                <div class="p-0">
                    <div class="divide-y divide-slate-50">
                        <div class="p-4 hover:bg-slate-50 transition-colors flex gap-3">
                            <div class="w-2 h-2 mt-2 rounded-full bg-blue-500 shrink-0"></div>
                            <div>
                                <p class="text-sm font-medium text-slate-800">System Activity</p>
                                <p class="text-xs text-slate-500 mt-0.5">Monitoring inventory health...</p>
                                <p class="text-[10px] text-slate-400 mt-1">Online</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 text-center border-t border-slate-50 bg-slate-50/50 rounded-b-2xl">
                        <a href="#" class="text-xs font-bold text-blue-600 hover:text-blue-700 uppercase tracking-widest">View All Logs</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection