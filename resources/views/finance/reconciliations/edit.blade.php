@extends('layouts.app')

@section('title', 'Edit Reconciliation')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20">
    <div class="max-w-xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('finance.reconciliations.show', $reconciliation) }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-600 transition-colors mb-2 text-xs font-bold uppercase tracking-wider">
                <i data-feather="arrow-left" class="w-4 h-4"></i> Back to Reconciliation
            </a>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Edit Reconciliation Details</h1>
        </div>

        <form action="{{ route('finance.reconciliations.update', $reconciliation) }}" method="POST" class="bg-white rounded-3xl p-8 border border-slate-200 shadow-xl space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Statement Date</label>
                <input type="date" name="statement_date" value="{{ old('statement_date', $reconciliation->statement_date->format('Y-m-d')) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Statement Closing Balance (RP)</label>
                <input type="number" name="closing_balance" step="0.01" value="{{ old('closing_balance', $reconciliation->closing_balance) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-black text-xl text-slate-900 focus:outline-none focus:border-blue-500 transition-colors">
                <p class="text-xs text-slate-400 mt-2">Enter the ending balance shown on your bank statement.</p>
            </div>

            <div class="flex justify-between items-center pt-4">
                <button type="button" onclick="if(confirm('Are you sure you want to delete this reconciliation draft?')) document.getElementById('deleteForm').submit()" class="text-rose-500 hover:text-rose-700 text-xs font-bold uppercase tracking-wider">
                    Delete Draft
                </button>
                <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm transition-all shadow-lg shadow-blue-200">
                    Save Changes
                </button>
            </div>
        </form>

        <form id="deleteForm" action="{{ route('finance.reconciliations.destroy', $reconciliation) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
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
