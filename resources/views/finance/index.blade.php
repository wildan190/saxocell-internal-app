@extends('layouts.app')

@section('title', 'Finance Dashboard')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div>
            <span class="text-blue-600 font-bold text-xs uppercase tracking-wider mb-2 block">Financial Ecosystem</span>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Accounting</h1>
            <p class="text-slate-500 mt-2 font-medium text-sm">Real-time financial health and ledger management.</p>
        </div>
        
        <div class="flex gap-4">
            <a href="{{ route('finance.cash') }}" class="flex items-center gap-2 px-6 py-3 bg-slate-900 border border-slate-800 text-white rounded-xl font-bold text-sm transition-all active:scale-95 shadow-lg shadow-slate-200/50">
                <i data-feather="monitor" class="w-4 h-4"></i> Cash Desk
            </a>
            <a href="{{ route('finance.income.create') }}" class="flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-sm transition-all active:scale-95 shadow-lg shadow-emerald-200/50">
                <i data-feather="dollar-sign" class="w-4 h-4"></i> Record Income
            </a>
            <a href="{{ route('finance.transfers.create') }}" class="flex items-center gap-2 px-6 py-3 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 rounded-xl font-bold text-sm transition-all active:scale-95">
                <i data-feather="repeat" class="w-4 h-4"></i> Transfer Funds
            </a>
            <a href="{{ route('finance.journals.create') }}" class="flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm transition-all active:scale-95 shadow-lg shadow-blue-200/50">
                <i data-feather="plus-circle" class="w-4 h-4"></i> New Journal Entry
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-lg transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl group-hover:scale-110 transition-transform">
                    <i data-feather="dollar-sign" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-bold text-emerald-500 uppercase tracking-wider bg-emerald-50 px-2 py-1 rounded-full">Liquid</span>
            </div>
            <h3 class="text-slate-400 font-bold text-xs uppercase tracking-wider mb-1">Total Cash Value</h3>
            <p class="text-2xl font-bold text-slate-900">RP {{ number_format($stats['cash_balance'], 0, ',', '.') }}</p>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-lg transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl group-hover:scale-110 transition-transform">
                    <i data-feather="arrow-down-left" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-bold text-blue-500 uppercase tracking-wider bg-blue-50 px-2 py-1 rounded-full">Incoming</span>
            </div>
            <h3 class="text-slate-400 font-bold text-xs uppercase tracking-wider mb-1">Accounts Receivable</h3>
            <p class="text-2xl font-bold text-slate-900">RP {{ number_format($stats['total_ar'], 0, ',', '.') }}</p>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-lg transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-rose-50 text-rose-600 rounded-xl group-hover:scale-110 transition-transform">
                    <i data-feather="arrow-up-right" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-bold text-rose-500 uppercase tracking-wider bg-rose-50 px-2 py-1 rounded-full">Outgoing</span>
            </div>
            <h3 class="text-slate-400 font-bold text-xs uppercase tracking-wider mb-1">Accounts Payable</h3>
            <p class="text-2xl font-bold text-slate-900">RP {{ number_format($stats['total_ap'], 0, ',', '.') }}</p>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-lg transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-amber-50 text-amber-600 rounded-xl group-hover:scale-110 transition-transform">
                    <i data-feather="trending-up" class="w-5 h-5"></i>
                </div>
                @php $margin = $stats['month_revenue'] - $stats['month_expense']; @endphp
                <span class="text-[10px] font-bold {{ $margin >= 0 ? 'text-emerald-500 bg-emerald-50' : 'text-rose-500 bg-rose-50' }} uppercase tracking-wider px-2 py-1 rounded-full">
                    {{ $margin >= 0 ? 'Surplus' : 'Deficit' }}
                </span>
            </div>
            <h3 class="text-slate-400 font-bold text-xs uppercase tracking-wider mb-1">Monthly Net Margin</h3>
            <p class="text-2xl font-bold text-slate-900">RP {{ number_format($margin, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Ledger Activity -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl border border-slate-200/60 shadow-lg overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900">Recent Ledger Activity</h3>
                        <p class="text-slate-400 font-semibold text-xs uppercase tracking-wider mt-1">latest transactions across all accounts</p>
                    </div>
                    <a href="{{ route('finance.journals.index') }}" class="text-blue-600 font-bold text-xs uppercase tracking-wider hover:underline">View All Journals</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/30 border-b border-slate-100">
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Debit</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Credit</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($recentEntries as $entry)
                                @foreach($entry->items as $item)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <span class="font-semibold text-slate-500 text-sm">{{ $entry->entry_date->format('M d, Y') }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-800 text-sm">{{ $item->account->name }}</span>
                                            <span class="text-xs text-slate-400 font-medium">{{ $entry->description }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if($item->debit > 0)
                                            <span class="font-bold text-slate-900 text-sm">RP {{ number_format($item->debit, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-slate-200">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if($item->credit > 0)
                                            <span class="font-bold text-slate-900 text-sm">RP {{ number_format($item->credit, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-slate-200">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400 font-semibold italic text-sm">No recent activity detected</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Reports -->
        <div class="space-y-8">
            <div class="bg-slate-900 rounded-[3rem] p-10 text-white shadow-2xl relative overflow-hidden group">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-500/20 rounded-full blur-3xl group-hover:bg-blue-500/30 transition-all"></div>
                <h3 class="text-2xl font-black mb-6 relative">Financial Reports</h3>
                <div class="space-y-4 relative">
                    <a href="{{ route('finance.reports.pl') }}" class="flex items-center justify-between p-6 bg-white/5 border border-white/10 rounded-3xl hover:bg-white/10 transition-all">
                        <div class="flex items-center gap-4">
                            <i data-feather="file-text" class="w-5 h-5 text-emerald-400"></i>
                            <span class="font-black text-sm uppercase tracking-wider">Profit & Loss</span>
                        </div>
                        <i data-feather="chevron-right" class="w-4 h-4 text-slate-500"></i>
                    </a>
                    <a href="{{ route('finance.reports.bs') }}" class="flex items-center justify-between p-6 bg-white/5 border border-white/10 rounded-3xl hover:bg-white/10 transition-all">
                        <div class="flex items-center gap-4">
                            <i data-feather="briefcase" class="w-5 h-5 text-blue-400"></i>
                            <span class="font-black text-sm uppercase tracking-wider">Balance Sheet</span>
                        </div>
                        <i data-feather="chevron-right" class="w-4 h-4 text-slate-500"></i>
                    </a>
                    <a href="{{ route('finance.reports.cashflow') }}" class="flex items-center justify-between p-6 bg-white/5 border border-white/10 rounded-3xl hover:bg-white/10 transition-all">
                        <div class="flex items-center gap-4">
                            <i data-feather="activity" class="w-5 h-5 text-amber-400"></i>
                            <span class="font-black text-sm uppercase tracking-wider">Cashflow</span>
                        </div>
                        <i data-feather="chevron-right" class="w-4 h-4 text-slate-500"></i>
                    </a>
                </div>
            </div>

            <!-- Pending Payables -->
            <div class="bg-white rounded-[3rem] p-10 border border-slate-100 shadow-xl">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Pending Payables</h3>
                    <a href="{{ route('finance.payables') }}" class="text-xs font-black text-blue-600 uppercase tracking-wider">Pay Now</a>
                </div>
                <div class="space-y-6">
                    @forelse($receivables as $inv)
                    <div class="flex items-center gap-5 p-5 bg-slate-50 rounded-[2rem] hover:bg-white border border-transparent hover:border-slate-100 hover:shadow-lg transition-all">
                        <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-rose-500 shadow-sm">
                            <i data-feather="invoice" class="w-6 h-6"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-black text-slate-400 uppercase tracking-wider mb-1">{{ $inv->supplier->name ?? 'Unknown' }}</p>
                            <p class="font-bold text-slate-900">RP {{ number_format($inv->total_amount, 0, ',', '.') }}</p>
                            <p class="text-[10px] text-rose-400 font-black mt-1">Due {{ $inv->due_date->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-slate-400 font-bold italic py-4">No urgent payables</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
