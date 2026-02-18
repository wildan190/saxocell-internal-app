@extends('layouts.app')

@section('title', 'Balance Sheet')

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <a href="{{ route('home') }}">Home</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item">
        <a href="{{ route('finance.index') }}">Finance</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item">
        <a href="{{ route('finance.reports') }}">Reports</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">Balance Sheet</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Report Header -->
    <div class="card mb-6">
        <div class="p-6 md:p-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex-1">
                    <h1 class="text-2xl md:text-3xl font-bold text-slate-900 mb-2">Balance Sheet</h1>
                    <p class="text-sm text-slate-500 font-medium flex items-center gap-2">
                        <i data-feather="calendar" class="w-4 h-4"></i>
                        As of {{ $date }}
                    </p>
                </div>
                <div class="bg-slate-900 text-white rounded-2xl p-6 text-center min-w-[200px]">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Total Assets</p>
                    <p class="text-2xl md:text-3xl font-black text-white">
                        RP {{ number_format($totalAssets, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Assets Section -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="border-b border-slate-100 p-6 bg-blue-50/30">
                    <h2 class="text-lg font-bold text-slate-900">Assets</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            @php
                                $halfCount = ceil($assets->count() / 2);
                                $firstHalf = $assets->take($halfCount);
                                $secondHalf = $assets->slice($halfCount);
                            @endphp
                            
                            @foreach($firstHalf as $acc)
                            <div class="flex justify-between items-center py-2 hover:bg-slate-50 px-3 rounded-lg transition-colors">
                                <span class="text-sm font-medium text-slate-600">{{ $acc->name }}</span>
                                <span class="text-sm font-bold text-slate-900">RP {{ number_format($acc->current_balance, 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="space-y-3">
                            @foreach($secondHalf as $acc)
                            <div class="flex justify-between items-center py-2 hover:bg-slate-50 px-3 rounded-lg transition-colors">
                                <span class="text-sm font-medium text-slate-600">{{ $acc->name }}</span>
                                <span class="text-sm font-bold text-slate-900">RP {{ number_format($acc->current_balance, 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                            
                            @if($assets->count() == 0)
                            <p class="text-sm text-slate-400 text-center py-4 col-span-2">No asset accounts</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-slate-200 bg-slate-50 rounded-xl p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-bold text-slate-600 uppercase tracking-wider">Total Assets</span>
                            <span class="text-xl font-black text-blue-600">RP {{ number_format($totalAssets, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liabilities Section -->
        <div class="card">
            <div class="border-b border-slate-100 p-6 bg-rose-50/30">
                <h2 class="text-lg font-bold text-slate-900">Liabilities</h2>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @forelse($liabilities as $acc)
                    <div class="flex justify-between items-center py-2 hover:bg-slate-50 px-3 rounded-lg transition-colors">
                        <span class="text-sm font-medium text-slate-600">{{ $acc->name }}</span>
                        <span class="text-sm font-bold text-slate-900">RP {{ number_format($acc->current_balance, 0, ',', '.') }}</span>
                    </div>
                    @empty
                    <p class="text-sm text-slate-400 text-center py-4">No liability accounts</p>
                    @endforelse
                </div>
                
                <div class="mt-6 pt-6 border-t border-slate-200 bg-slate-50 rounded-xl p-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-bold text-slate-600 uppercase tracking-wider">Total Liabilities</span>
                        <span class="text-lg font-black text-rose-600">RP {{ number_format($totalLiabilities, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Equity Section -->
        <div class="card">
            <div class="border-b border-slate-100 p-6 bg-emerald-50/30">
                <h2 class="text-lg font-bold text-slate-900">Equity</h2>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @forelse($equity as $acc)
                    <div class="flex justify-between items-center py-2 hover:bg-slate-50 px-3 rounded-lg transition-colors">
                        <span class="text-sm font-medium text-slate-600">{{ $acc->name }}</span>
                        <span class="text-sm font-bold text-slate-900">RP {{ number_format($acc->current_balance, 0, ',', '.') }}</span>
                    </div>
                    @empty
                    <p class="text-sm text-slate-400 text-center py-4">No equity accounts</p>
                    @endforelse
                </div>
                
                <div class="mt-6 pt-6 border-t border-slate-200 bg-slate-50 rounded-xl p-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-bold text-slate-600 uppercase tracking-wider">Total Equity</span>
                        <span class="text-lg font-black text-emerald-600">RP {{ number_format($totalEquity, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Balance Verification -->
    <div class="card mt-6">
        <div class="p-6 md:p-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-6 {{ abs($totalAssets - ($totalLiabilities + $totalEquity)) < 0.01 ? 'bg-emerald-50' : 'bg-rose-50' }} rounded-2xl">
                <div>
                    <h3 class="text-xl md:text-2xl font-bold text-slate-900">Total Liabilities & Equity</h3>
                    <p class="text-xs text-slate-500 uppercase tracking-wider mt-1">Balance Verification</p>
                </div>
                <div class="text-left md:text-right">
                    <p class="text-2xl md:text-3xl font-black text-slate-900">
                        RP {{ number_format($totalLiabilities + $totalEquity, 0, ',', '.') }}
                    </p>
                    @if(abs($totalAssets - ($totalLiabilities + $totalEquity)) < 0.01)
                        <div class="flex items-center gap-2 text-emerald-600 font-bold text-xs uppercase tracking-wider mt-2">
                            <i data-feather="check-circle" class="w-4 h-4"></i>
                            Balanced
                        </div>
                    @else
                        <div class="flex items-center gap-2 text-rose-600 font-bold text-xs uppercase tracking-wider mt-2">
                            <i data-feather="alert-triangle" class="w-4 h-4"></i>
                            Imbalance Detected
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Print Footer -->
    <div class="mt-8 text-center">
        <p class="text-[10px] text-slate-400 uppercase tracking-widest">Saxocell Accounting Ledger • Official Statement</p>
    </div>
</div>
@endsection
