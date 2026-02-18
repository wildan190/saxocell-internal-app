@extends('layouts.app')

@section('title', 'Financial Reports')

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
    <div class="breadcrumb-item active">Reports</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">Financial Reports</h1>
            <p class="page-subtitle">Generate professional financial statements and analysis</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- P&L Card -->
        <div class="card group hover:shadow-xl transition-all border-l-4 border-l-emerald-500">
            <div class="p-6 flex flex-col h-full">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <i data-feather="trending-up" class="w-6 h-6"></i>
                </div>
                <h2 class="text-xl font-bold text-slate-900 mb-2">Profit & Loss</h2>
                <p class="text-sm text-slate-500 mb-6 flex-grow">Income performance summary for the period</p>
                <a href="{{ route('finance.reports.pl') }}" class="btn btn-primary w-full justify-center">
                    <i data-feather="file-text" class="w-4 h-4"></i> Generate Report
                </a>
            </div>
        </div>

        <!-- Balance Sheet Card -->
        <div class="card group hover:shadow-xl transition-all border-l-4 border-l-blue-500">
            <div class="p-6 flex flex-col h-full">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <i data-feather="briefcase" class="w-6 h-6"></i>
                </div>
                <h2 class="text-xl font-bold text-slate-900 mb-2">Balance Sheet</h2>
                <p class="text-sm text-slate-500 mb-6 flex-grow">Statement of financial position and equity</p>
                <a href="{{ route('finance.reports.bs') }}" class="btn btn-primary w-full justify-center">
                    <i data-feather="file-text" class="w-4 h-4"></i> Generate Report
                </a>
            </div>
        </div>

        <!-- Trial Balance Card -->
        <div class="card group hover:shadow-xl transition-all border-l-4 border-l-amber-500">
            <div class="p-6 flex flex-col h-full">
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <i data-feather="check-square" class="w-6 h-6"></i>
                </div>
                <h2 class="text-xl font-bold text-slate-900 mb-2">Trial Balance</h2>
                <p class="text-sm text-slate-500 mb-6 flex-grow">Ledger arithmetic verification report</p>
                <a href="{{ route('finance.reports.tb') }}" class="btn btn-primary w-full justify-center">
                    <i data-feather="file-text" class="w-4 h-4"></i> Generate Report
                </a>
            </div>
        </div>

        <!-- AP Aging Card -->
        <div class="card group hover:shadow-xl transition-all border-l-4 border-l-rose-500">
            <div class="p-6 flex flex-col h-full">
                <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <i data-feather="clock" class="w-6 h-6"></i>
                </div>
                <h2 class="text-xl font-bold text-slate-900 mb-2">Payables Aging</h2>
                <p class="text-sm text-slate-500 mb-6 flex-grow">Outstanding debt and risk assessment</p>
                <a href="{{ route('finance.reports.aging') }}" class="btn btn-primary w-full justify-center">
                    <i data-feather="file-text" class="w-4 h-4"></i> Generate Report
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
