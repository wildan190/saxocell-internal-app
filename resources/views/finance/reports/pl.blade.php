@extends('layouts.app')

@section('title', 'Profit & Loss Statement')

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
    <div class="breadcrumb-item active">Profit & Loss</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Report Header -->
    <div class="card mb-6">
        <div class="p-6 md:p-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex-1">
                    <h1 class="text-2xl md:text-3xl font-bold text-slate-900 mb-2">Profit & Loss Statement</h1>
                    <p class="text-sm text-slate-500 font-medium">
                        <i data-feather="calendar" class="w-4 h-4 inline"></i>
                        Period: {{ $startDate }} to {{ $endDate }}
                    </p>
                </div>
                <div class="bg-slate-900 text-white rounded-2xl p-6 text-center min-w-[200px]">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Net Profit/Loss</p>
                    <p class="text-2xl md:text-3xl font-black {{ $netProfit >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                        RP {{ number_format($netProfit, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Section -->
        <div class="card">
            <div class="border-b border-slate-100 p-6 bg-emerald-50/30">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-900">Revenue</h2>
                    <span class="text-xl font-black text-emerald-600">RP {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @forelse($revenue as $acc)
                    <div class="flex justify-between items-center py-2 hover:bg-slate-50 px-3 rounded-lg transition-colors">
                        <span class="text-sm font-medium text-slate-600">{{ $acc->name }}</span>
                        <span class="text-sm font-bold text-slate-900">RP {{ number_format($acc->current_balance, 0, ',', '.') }}</span>
                    </div>
                    @empty
                    <p class="text-sm text-slate-400 text-center py-4">No revenue accounts</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Expenses Section -->
        <div class="card">
            <div class="border-b border-slate-100 p-6 bg-rose-50/30">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-900">Operating Expenses</h2>
                    <span class="text-xl font-black text-rose-600">RP {{ number_format($totalExpense, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @forelse($expenses as $acc)
                    <div class="flex justify-between items-center py-2 hover:bg-slate-50 px-3 rounded-lg transition-colors">
                        <span class="text-sm font-medium text-slate-600">{{ $acc->name }}</span>
                        <span class="text-sm font-bold text-slate-900">RP {{ number_format($acc->current_balance, 0, ',', '.') }}</span>
                    </div>
                    @empty
                    <p class="text-sm text-slate-400 text-center py-4">No expense accounts</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Net Result -->
    <div class="card mt-6">
        <div class="p-6 md:p-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-6 bg-slate-50 rounded-2xl">
                <div>
                    <h3 class="text-xl md:text-2xl font-bold text-slate-900">Net Profit / Loss</h3>
                    <p class="text-xs text-slate-500 uppercase tracking-wider mt-1">After operating expenses</p>
                </div>
                <div class="text-left md:text-right">
                    <p class="text-2xl md:text-3xl font-black {{ $netProfit >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        RP {{ number_format($netProfit, 0, ',', '.') }}
                    </p>
                    @if($netProfit >= 0)
                        <p class="text-xs text-emerald-600 font-bold mt-1">Profitable Period</p>
                    @else
                        <p class="text-xs text-rose-600 font-bold mt-1">Loss Period</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Print Footer -->
    <div class="mt-8 text-center">
        <p class="text-[10px] text-slate-400 uppercase tracking-widest">Generated by Saxocell Finance System • Confidential</p>
    </div>
</div>
@endsection
