@extends('layouts.app')

@section('title', 'Chart of Accounts')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div>
            <h1 class="text-5xl font-black text-slate-900 tracking-tight">Chart of Accounts</h1>
            <p class="text-slate-500 mt-3 font-medium text-lg">Define and organize your financial structure.</p>
        </div>
        
        <div class="flex gap-4">
            <a href="{{ route('finance.accounts.create') }}" class="flex items-center gap-3 px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-[2rem] font-black transition-all active:scale-95 shadow-xl shadow-blue-200">
                <i data-feather="plus" class="w-5 h-5"></i> Add Account
            </a>
        </div>
    </div>

    <!-- Accounts Table -->
    <div class="bg-white rounded-[3rem] border border-slate-200/60 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Code</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Account Name</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Type</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Category</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Balance</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($accounts as $account)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-10 py-6">
                            <span class="font-black text-slate-400 tracking-tighter">{{ $account->code }}</span>
                        </td>
                        <td class="px-10 py-6">
                            <span class="font-black text-slate-800 text-lg">{{ $account->name }}</span>
                        </td>
                        <td class="px-10 py-6">
                            <span class="text-xs font-black uppercase tracking-widest {{ 
                                in_array($account->type, ['asset', 'expense']) ? 'text-blue-600' : 'text-rose-600' 
                            }}">
                                {{ $account->type }}
                            </span>
                        </td>
                        <td class="px-10 py-6 uppercase text-[10px] font-black text-slate-400 tracking-widest">
                            {{ str_replace('_', ' ', $account->category) }}
                        </td>
                        <td class="px-10 py-6 text-right font-black text-slate-900">
                            RP {{ number_format($account->current_balance, 0, ',', '.') }}
                        </td>
                        <td class="px-10 py-6 text-center">
                            @if($account->is_active)
                                <span class="w-2 h-2 bg-emerald-500 rounded-full inline-block shadow-sm animate-pulse"></span>
                            @else
                                <span class="w-2 h-2 bg-slate-300 rounded-full inline-block"></span>
                            @endif
                        </td>
                        <td class="px-10 py-6 text-center">
                            <a href="{{ route('finance.accounts.ledger', $account->id) }}" class="p-4 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl transition-all inline-block">
                                <i data-feather="database" class="w-4 h-4"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
