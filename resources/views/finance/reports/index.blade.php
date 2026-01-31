@extends('layouts.app')

@section('title', 'Financial Reports')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20 p-8 md:p-12">
    <div class="mb-16">
        <h1 class="text-5xl font-black text-slate-900 tracking-tight">Intelligence Center</h1>
        <p class="text-slate-500 mt-4 font-medium text-lg">Generate audit-ready financial statements and analysis.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
        <!-- P&L Card -->
        <div class="bg-white p-12 rounded-[3.5rem] border border-slate-100 shadow-sm hover:shadow-2xl transition-all group flex flex-col h-full border-b-8 border-b-emerald-400">
            <div class="w-20 h-20 bg-emerald-50 text-emerald-600 rounded-[2rem] flex items-center justify-center mb-10 group-hover:scale-110 transition-transform">
                <i data-feather="file-text" class="w-10 h-10"></i>
            </div>
            <h2 class="text-3xl font-black text-slate-900 mb-4 tracking-tighter italic">Profit & Loss</h2>
            <p class="text-slate-500 font-medium mb-10 flex-grow leading-relaxed">Periodic income performance summary.</p>
            <a href="{{ route('finance.reports.pl') }}" class="w-full py-5 bg-slate-900 text-white rounded-[2rem] font-black text-center text-xs uppercase tracking-widest hover:bg-black transition-all">Generate</a>
        </div>

        <!-- Balance Sheet Card -->
        <div class="bg-white p-12 rounded-[3.5rem] border border-slate-100 shadow-sm hover:shadow-2xl transition-all group flex flex-col h-full border-b-8 border-b-blue-400">
            <div class="w-20 h-20 bg-blue-50 text-blue-600 rounded-[2rem] flex items-center justify-center mb-10 group-hover:scale-110 transition-transform">
                <i data-feather="briefcase" class="w-10 h-10"></i>
            </div>
            <h2 class="text-3xl font-black text-slate-900 mb-4 tracking-tighter italic">Balance Sheet</h2>
            <p class="text-slate-500 font-medium mb-10 flex-grow leading-relaxed">Statement of financial position.</p>
            <a href="{{ route('finance.reports.bs') }}" class="w-full py-5 bg-slate-900 text-white rounded-[2rem] font-black text-center text-xs uppercase tracking-widest hover:bg-black transition-all">Generate</a>
        </div>

        <!-- Trial Balance Card -->
        <div class="bg-white p-12 rounded-[3.5rem] border border-slate-100 shadow-sm hover:shadow-2xl transition-all group flex flex-col h-full border-b-8 border-b-amber-400">
            <div class="w-20 h-20 bg-amber-50 text-amber-600 rounded-[2rem] flex items-center justify-center mb-10 group-hover:scale-110 transition-transform">
                <i data-feather="check-square" class="w-10 h-10"></i>
            </div>
            <h2 class="text-3xl font-black text-slate-900 mb-4 tracking-tighter italic">Trial Balance</h2>
            <p class="text-slate-500 font-medium mb-10 flex-grow leading-relaxed">Verification of ledger arithmetic.</p>
            <a href="{{ route('finance.reports.tb') }}" class="w-full py-5 bg-slate-900 text-white rounded-[2rem] font-black text-center text-xs uppercase tracking-widest hover:bg-black transition-all">Generate</a>
        </div>

        <!-- AP Aging Card -->
        <div class="bg-white p-12 rounded-[3.5rem] border border-slate-100 shadow-sm hover:shadow-2xl transition-all group flex flex-col h-full border-b-8 border-b-rose-400">
            <div class="w-20 h-20 bg-rose-50 text-rose-600 rounded-[2rem] flex items-center justify-center mb-10 group-hover:scale-110 transition-transform">
                <i data-feather="clock" class="w-10 h-10"></i>
            </div>
            <h2 class="text-3xl font-black text-slate-900 mb-4 tracking-tighter italic">Payables Aging</h2>
            <p class="text-slate-500 font-medium mb-10 flex-grow leading-relaxed">Outstanding debt risk assessment.</p>
            <a href="{{ route('finance.reports.aging') }}" class="w-full py-5 bg-slate-900 text-white rounded-[2rem] font-black text-center text-xs uppercase tracking-widest hover:bg-black transition-all">Generate</a>
        </div>
    </div>
</div>
@endsection
