@extends('layouts.app')

@section('title', 'Record Income: ' . $store->name)

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div>
            <a href="{{ route('stores.show', $store) }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-600 transition-colors mb-2 text-xs font-bold uppercase tracking-wider">
                <i data-feather="arrow-left" class="w-4 h-4"></i> Back to Store
            </a>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Record Income</h1>
            <p class="text-slate-500 mt-2 font-medium text-sm">Record revenue for {{ $store->name }}.</p>
        </div>
    </div>

    <div class="max-w-2xl mx-auto">
        <!-- Error Display -->
        @if ($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-600 p-4 rounded-xl mb-6 shadow-sm">
                <div class="flex items-center gap-2 mb-2">
                    <i data-feather="alert-circle" class="w-4 h-4"></i>
                    <span class="font-bold">Please correct the following errors:</span>
                </div>
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('stores.income.store', $store) }}" method="POST" class="space-y-6">
            @csrf

            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl space-y-6">
                
                <!-- Date & Description -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Date</label>
                        <input type="date" name="income_date" value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Description</label>
                        <input type="text" name="description" placeholder="e.g. Daily Sales" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors">
                    </div>
                </div>

                <!-- Financials -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Revenue Category</label>
                    <select name="revenue_account_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors">
                        @foreach($revenueAccounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Deposit To (Store Account)</label>
                    <select name="deposit_account_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors">
                        @foreach($store->accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }} (Bal: {{ number_format($account->current_balance) }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Amount (RP)</label>
                    <input type="number" name="amount" step="0.01" min="0" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-black text-xl text-slate-900 focus:outline-none focus:border-emerald-500 transition-colors" placeholder="0">
                </div>

            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('stores.show', $store) }}" class="px-6 py-3 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl font-bold text-sm transition-all">Cancel</a>
                <button type="submit" class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-sm transition-all shadow-lg shadow-emerald-200">
                    Record Income
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>
@endpush
@endsection
