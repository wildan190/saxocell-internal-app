@extends('layouts.app')

@section('title', 'Balance Sheet')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20 p-12">
    <!-- Header -->
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-[4rem] shadow-2xl p-16 border border-slate-100 relative overflow-hidden">
            <!-- Glass Decorative Element -->
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-blue-600/5 rounded-full blur-[100px]"></div>
            
            <div class="flex justify-between items-center mb-20 relative">
                <div>
                    <h1 class="text-7xl font-black text-slate-900 tracking-tighter uppercase italic leading-none">Financial Result</h1>
                    <p class="text-slate-400 font-bold text-xl mt-6 tracking-widest uppercase ml-2 flex items-center gap-4">
                        <i data-feather="calendar" class="w-6 h-6 text-blue-600"></i> Balance Sheet Statement • As of {{ $date }}
                    </p>
                </div>
                <div class="text-right">
                    <div class="flex flex-col items-end">
                        <span class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-400 mb-2">Total Solvency</span>
                        <p class="text-5xl font-black text-slate-900">RP {{ number_format($totalAssets, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="space-y-24">
                <!-- Assets -->
                <section>
                    <div class="flex justify-between items-end border-b-[6px] border-slate-900 pb-6 mb-10">
                        <h2 class="text-4xl font-black text-slate-900 uppercase tracking-tighter">Assets</h2>
                        <span class="text-2xl font-black text-slate-400">Section I</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <div class="space-y-6">
                            @foreach($assets as $acc)
                            <div class="flex justify-between items-center group p-6 hover:bg-slate-50 rounded-3xl transition-all">
                                <span class="text-slate-500 font-black text-lg group-hover:text-slate-900 transition-colors">{{ $acc->name }}</span>
                                <span class="text-slate-900 font-black text-xl">RP {{ number_format($acc->current_balance, 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>
                        <div class="bg-slate-50 rounded-[3rem] p-12 flex flex-col justify-center text-center border-2 border-slate-100 shadow-inner">
                            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 mb-4">Cumulative Liquid Value</p>
                            <p class="text-5xl font-black text-slate-900">RP {{ number_format($totalAssets, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </section>

                <!-- Liabilities & Equity -->
                <section>
                    <div class="flex justify-between items-end border-b-[6px] border-slate-900 pb-6 mb-10">
                        <h2 class="text-4xl font-black text-slate-900 uppercase tracking-tighter">Liabilities & Equity</h2>
                        <span class="text-2xl font-black text-slate-400">Section II</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <div class="space-y-12">
                            <!-- Liabilities Sub-section -->
                            <div class="space-y-4">
                                <h3 class="text-xs font-black uppercase tracking-widest text-rose-500 mb-6 flex items-center gap-2">
                                    <span class="w-8 h-[2px] bg-rose-200"></span> Liabilities
                                </h3>
                                @foreach($liabilities as $acc)
                                <div class="flex justify-between items-center group px-6">
                                    <span class="text-slate-500 font-black text-lg group-hover:text-slate-900 transition-colors">{{ $acc->name }}</span>
                                    <span class="text-slate-900 font-black text-xl">RP {{ number_format($acc->current_balance, 0, ',', '.') }}</span>
                                </div>
                                @endforeach
                            </div>
                            <!-- Equity Sub-section -->
                            <div class="space-y-4 pt-12 border-t-2 border-slate-100">
                                <h3 class="text-xs font-black uppercase tracking-widest text-emerald-500 mb-6 flex items-center gap-2">
                                    <span class="w-8 h-[2px] bg-emerald-200"></span> Shareholder's Equity
                                </h3>
                                @foreach($equity as $acc)
                                <div class="flex justify-between items-center group px-6">
                                    <span class="text-slate-500 font-black text-lg group-hover:text-slate-900 transition-colors">{{ $acc->name }}</span>
                                    <span class="text-slate-900 font-black text-xl">RP {{ number_format($acc->current_balance, 0, ',', '.') }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="bg-slate-900 rounded-[3rem] p-12 flex flex-col justify-center text-center text-white shadow-2xl relative overflow-hidden group">
                            <div class="absolute -right-20 -bottom-20 w-60 h-60 bg-white/5 rounded-full blur-[60px] group-hover:bg-white/10 transition-all"></div>
                            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-4">Total Liabilities & Equity</p>
                            <p class="text-5xl font-black text-white">RP {{ number_format($totalLiabilities + $totalEquity, 0, ',', '.') }}</p>
                            @if(abs($totalAssets - ($totalLiabilities + $totalEquity)) < 0.01)
                                <div class="mt-8 flex items-center justify-center gap-3 text-emerald-400 font-black text-xs uppercase tracking-widest">
                                    <i data-feather="check-circle" class="w-4 h-4"></i> Balanced Structure
                                </div>
                            @else
                                <div class="mt-8 flex items-center justify-center gap-3 text-rose-400 font-black text-xs uppercase tracking-widest">
                                    <i data-feather="alert-triangle" class="w-4 h-4"></i> Imbalance Detected
                                </div>
                            @endif
                        </div>
                    </div>
                </section>
            </div>
            
            <div class="mt-20 text-center border-t border-slate-100 pt-12">
                <p class="text-slate-300 font-black text-[10px] uppercase tracking-[0.8em]">Saxocell Accounting Ledger • Official Business Statement</p>
            </div>
        </div>
    </div>
</div>
@endsection
