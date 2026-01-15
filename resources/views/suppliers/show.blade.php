@extends('layouts.app')

@section('title', 'Supplier Profile: ' . $supplier->name)

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <a href="{{ route('home') }}">
            <i data-feather="home"></i>
            Home
        </a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item">
        <a href="{{ route('suppliers.index') }}">Suppliers</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">{{ $supplier->name }}</div>
</nav>
@endsection

@section('content')
<div class="p-4 md:p-8 max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div class="flex items-center gap-6">
            <div class="w-20 h-20 md:w-24 md:h-24 bg-blue-600 text-white rounded-3xl flex items-center justify-center text-3xl font-black shadow-lg shadow-blue-200">
                {{ strtoupper(substr($supplier->name, 0, 2)) }}
            </div>
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">{{ $supplier->name }}</h1>
                <p class="text-slate-500 mt-2 flex items-center gap-2">
                    <span class="flex items-center gap-1.5 font-medium text-slate-700">
                        <i data-feather="award" class="w-4 h-4"></i>
                        Certified Partner
                    </span>
                    <span class="text-slate-300">•</span>
                    <span class="flex items-center gap-1.5">
                        <i data-feather="calendar" class="w-4 h-4"></i>
                        Member since {{ $supplier->created_at->format('M Y') }}
                    </span>
                </p>
            </div>
        </div>
        
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('suppliers.edit', $supplier) }}" class="flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-200 transition-all active:scale-95">
                <i data-feather="edit-3" class="w-4 h-4"></i> Edit Partner
            </a>
            <a href="{{ route('suppliers.index') }}" class="flex items-center gap-2 px-6 py-2.5 bg-white border border-slate-200 hover:border-slate-300 text-slate-700 rounded-xl font-bold shadow-sm transition-all active:scale-95">
                <i data-feather="arrow-left" class="w-4 h-4"></i> Back
            </a>
        </div>
    </div>

    <!-- Contact Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Primary Contact</span>
            <span class="block text-base font-bold text-slate-800">{{ $supplier->contact_person }}</span>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Phone Number</span>
            <a href="tel:{{ $supplier->phone }}" class="block text-base font-bold text-blue-600 hover:text-blue-700">{{ $supplier->phone }}</a>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm truncate">
            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Business Email</span>
            <span class="block text-base font-bold text-slate-800">{{ $supplier->email ?: 'Not registered' }}</span>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Partner Status</span>
            <span class="flex items-center gap-2 text-base font-bold text-emerald-600">
                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                ACTIVE
            </span>
        </div>
    </div>

    <!-- Main Detail Card -->
    <div class="bg-white/95 backdrop-blur-xl rounded-[2rem] border border-slate-200/60 shadow-xl overflow-hidden mb-8">
        <!-- Address Section -->
        <div class="p-8 md:p-10 bg-slate-50/30 border-b border-slate-100 text-center md:text-left">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Registered Business Address</h3>
            <p class="text-lg font-medium text-slate-700 leading-relaxed max-w-2xl">
                {{ $supplier->address ?: 'No business address registered for this partner.' }}
            </p>
        </div>

        <!-- Transaction History -->
        <div class="p-8 md:p-10">
            <h3 class="text-xl font-extrabold text-slate-900 mb-6 flex items-center gap-3">
                <span class="p-2 bg-slate-100 rounded-lg text-slate-600"><i data-feather="activity" class="w-5 h-5"></i></span>
                Supply Engagement History
            </h3>
            
            @if($supplier->inventoryTransactions->count() > 0)
            <div class="overflow-x-auto rounded-2xl border border-slate-100">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest">Entry Date</th>
                            <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest">Description</th>
                            <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-center">Movement</th>
                            <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-right">Quantity</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($supplier->inventoryTransactions->take(10) as $transaction)
                        <tr class="hover:bg-slate-50/30 transition-colors">
                            <td class="px-6 py-5 text-sm font-medium text-slate-500">
                                {{ $transaction->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-800 leading-tight">{{ $transaction->product->name }}</span>
                                    @if($transaction->productVariant)
                                    <span class="text-[11px] font-medium text-slate-400 mt-1 uppercase tracking-wider">
                                        {{ $transaction->productVariant->name }}
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                @php
                                    $typeMap = [
                                        'in' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'label' => 'PROCURED'],
                                        'out' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'DISPATCH'],
                                        'adjustment' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'label' => 'AUDITED'],
                                    ];
                                    $t = $typeMap[$transaction->type] ?? ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'label' => 'OTHER'];
                                @endphp
                                <span class="inline-flex px-3 py-1 {{ $t['bg'] }} {{ $t['text'] }} rounded-lg text-[10px] font-black tracking-widest uppercase">
                                    {{ $t['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-right font-black text-slate-900">{{ number_format($transaction->quantity) }} UNITS</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-20 bg-slate-50/50 rounded-3xl border border-dashed border-slate-200">
                <div class="w-16 h-16 bg-slate-100 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-feather="inbox" class="w-8 h-8"></i>
                </div>
                <p class="text-slate-500 font-medium">No recorded supply movements for this partner.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Danger Zone Card -->
    <div class="bg-red-50/50 border border-red-100 rounded-[2rem] p-8 md:p-10">
        <div class="flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="text-center md:text-left">
                <h4 class="text-xl font-extrabold text-red-900 mb-2">Archival Protocol</h4>
                <p class="text-red-600/80 font-medium text-sm max-w-xl">
                    Once archived, this supplier's profile will be deactivated. History will remain for accounting, but no new procurement orders can be issued.
                </p>
            </div>
            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="flex items-center gap-2 px-8 py-3 bg-white border border-red-200 text-red-600 hover:bg-red-50 rounded-xl font-bold transition-all shadow-sm active:scale-95" onclick="return confirm('Initiate archival process?')">
                    <i data-feather="trash-2" class="w-4 h-4"></i> Archive Partner Profile
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
