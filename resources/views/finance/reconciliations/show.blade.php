@extends('layouts.app')

@section('title', 'Reconcile: ' . $reconciliation->account->name)

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20 p-8 md:p-12">
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Dashboard Header -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="md:col-span-2 bg-white p-8 rounded-[3rem] border border-slate-200 shadow-xl flex items-center gap-6">
                <div class="w-20 h-20 bg-blue-100 text-blue-600 rounded-3xl flex items-center justify-center">
                    <i data-feather="briefcase" class="w-10 h-10"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-slate-900 leading-tight">{{ $reconciliation->account->name }}</h1>
                    <p class="text-slate-500 font-bold uppercase tracking-wider text-xs">Statement Date: {{ $reconciliation->statement_date->format('M d, Y') }}</p>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[3rem] border border-slate-200 shadow-xl">
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-[0.2em] mb-3 text-center">Difference</span>
                <div id="differenceDisplay" class="text-3xl font-black text-center {{ abs($reconciliation->closing_balance - $reconciliation->reconciled_balance) < 0.01 ? 'text-emerald-500' : 'text-rose-500' }} tracking-tight">
                    RP {{ number_format($reconciliation->closing_balance - $reconciliation->reconciled_balance, 2, ',', '.') }}
                </div>
                <div class="w-full h-1 bg-slate-100 rounded-full mt-4 overflow-hidden">
                    <div id="diffProgress" class="h-full bg-emerald-500 transition-all duration-500" style="width: {{ abs($reconciliation->closing_balance - $reconciliation->reconciled_balance) < 0.01 ? '100%' : '10%' }}"></div>
                </div>
            </div>

            <div class="flex flex-col gap-4">
                @if($reconciliation->status === 'draft')
                <button type="button" id="finalizeBtn" class="h-full bg-slate-900 hover:bg-black text-white rounded-[2rem] font-black transition-all active:scale-95 shadow-xl flex items-center justify-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed" {{ abs($reconciliation->closing_balance - $reconciliation->reconciled_balance) >= 0.01 ? 'disabled' : '' }} onclick="document.getElementById('finalizeForm').submit()">
                    <i data-feather="check-circle" class="w-5 h-5"></i> Finalize
                </button>
                <form id="finalizeForm" action="{{ route('finance.reconciliations.finalize', $reconciliation->id) }}" method="POST" class="hidden">@csrf</form>
                @else
                <div class="h-full bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-[2rem] font-black flex items-center justify-center gap-3 uppercase tracking-wider text-xs">
                    <i data-feather="shield-check" class="w-5 h-5"></i> Reconciled
                </div>
                @endif
                <a href="{{ route('finance.reconciliations.index') }}" class="py-4 bg-white border border-slate-200 text-slate-600 rounded-[2rem] font-black text-center transition-all hover:bg-slate-50 active:scale-95 text-xs uppercase tracking-wider">
                    Back to List
                </a>
            </div>
        </div>

        <!-- Summary Totals Bar -->
        <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-lg p-6 flex flex-wrap justify-around items-center gap-8">
            <div class="text-center">
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Opening Balance</span>
                <span class="text-lg font-bold text-slate-700">Rp {{ number_format($reconciliation->opening_balance, 2, ',', '.') }}</span>
            </div>
            <div class="text-slate-300"><i data-feather="plus" class="w-4 h-4"></i></div>
            <div class="text-center">
                <span class="block text-xs font-bold text-emerald-400 uppercase tracking-wider mb-1">Total Clearings (Dr)</span>
                <span id="totalDebit" class="text-lg font-bold text-emerald-600">Rp 0,00</span>
            </div>
            <div class="text-slate-300"><i data-feather="minus" class="w-4 h-4"></i></div>
            <div class="text-center">
                <span class="block text-xs font-bold text-rose-400 uppercase tracking-wider mb-1">Total Clearings (Cr)</span>
                <span id="totalCredit" class="text-lg font-bold text-rose-600">Rp 0,00</span>
            </div>
            <div class="text-slate-300"><i data-feather="pause" class="w-4 h-4 rotate-90"></i></div>
            <div class="text-center">
                <span class="block text-xs font-bold text-blue-400 uppercase tracking-wider mb-1">Reconciled Balance</span>
                <span id="reconciledBalance" class="text-2xl font-black text-blue-600">Rp {{ number_format($reconciliation->reconciled_balance, 2, ',', '.') }}</span>
            </div>
            <div class="text-slate-300"><i data-feather="arrow-right" class="w-4 h-4"></i></div>
            <div class="text-center">
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Statement Balance</span>
                <span class="text-2xl font-black text-slate-900">Rp {{ number_format($reconciliation->closing_balance, 2, ',', '.') }}</span>
            </div>
        </div>

        <!-- Reconciliation Items -->
        <div class="bg-white rounded-[3rem] border border-slate-200 shadow-xl overflow-hidden">
            <div class="px-10 py-8 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                    <span class="p-2 bg-slate-100 rounded-xl"><i data-feather="list" class="w-5 h-5 text-slate-600"></i></span>
                    Book Transactions
                </h3>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                    Only showing unreconciled items for this account
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-10 py-6 w-16 text-center">
                                <i data-feather="check-square" class="w-4 h-4 text-slate-400"></i>
                            </th>
                            <th class="px-6 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Ref / Entry</th>
                            <th class="px-6 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Debit (+)</th>
                            <th class="px-6 py-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Credit (-)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50" id="itemTbody">
                        @php 
                            $allAvailable = $unreconciledItems->merge($linkedItems)->sortBy('created_at');
                        @endphp
                        @forelse($allAvailable as $item)
                        <tr class="hover:bg-slate-50 transition-colors group cursor-pointer item-row" data-id="{{ $item->id }}" data-debit="{{ $item->debit }}" data-credit="{{ $item->credit }}">
                            <td class="px-10 py-6 text-center">
                                <div class="w-6 h-6 rounded-lg border-2 {{ $item->bank_reconciliation_id ? 'bg-blue-600 border-blue-600' : 'border-slate-200' }} flex items-center justify-center transition-all checkbox-ui">
                                    <i data-feather="check" class="w-3 h-3 text-white {{ $item->bank_reconciliation_id ? '' : 'hidden' }}"></i>
                                </div>
                                <input type="checkbox" name="item_ids[]" value="{{ $item->id }}" class="hidden item-checkbox" {{ $item->bank_reconciliation_id ? 'checked' : '' }}>
                            </td>
                            <td class="px-6 py-6">
                                <span class="font-bold text-slate-600 text-xs">{{ $item->journalEntry->entry_date->format('M d, Y') }}</span>
                            </td>
                            <td class="px-6 py-6">
                                <span class="text-xs font-bold text-blue-500 uppercase tracking-tighter">{{ substr($item->journalEntry->id, 0, 8) }}</span>
                            </td>
                            <td class="px-6 py-6">
                                <span class="font-black text-slate-800">{{ $item->description ?? $item->journalEntry->description }}</span>
                            </td>
                            <td class="px-6 py-6 text-right font-black text-emerald-600">
                                {{ $item->debit > 0 ? 'Rp ' . number_format($item->debit, 2, ',', '.') : '-' }}
                            </td>
                            <td class="px-6 py-6 text-right font-black text-rose-600">
                                {{ $item->credit > 0 ? 'Rp ' . number_format($item->credit, 2, ',', '.') : '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-10 py-12 text-center text-slate-400 font-bold italic">No pending transactions found for this account.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    const reconciliationId = "{{ $reconciliation->id }}";
    const closingBalance = parseFloat("{{ $reconciliation->closing_balance }}");
    const openingBalance = parseFloat("{{ $reconciliation->opening_balance }}");
    const updateUrl = "{{ route('finance.reconciliations.update-items', $reconciliation->id) }}";
    const isDraft = "{{ $reconciliation->status }}" === 'draft';

    const formatCurrency = (val) => {
        return 'Rp ' + val.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    };

    const updateCalculations = () => {
        let totalDebit = 0;
        let totalCredit = 0;

        document.querySelectorAll('.item-checkbox:checked').forEach(cb => {
            const row = cb.closest('.item-row');
            totalDebit += parseFloat(row.dataset.debit || 0);
            totalCredit += parseFloat(row.dataset.credit || 0);
        });

        const reconciledBalance = openingBalance + totalDebit - totalCredit;
        const difference = closingBalance - reconciledBalance;

        document.getElementById('totalDebit').textContent = formatCurrency(totalDebit);
        document.getElementById('totalCredit').textContent = formatCurrency(totalCredit);
        document.getElementById('reconciledBalance').textContent = formatCurrency(reconciledBalance);
        
        const diffDisplay = document.getElementById('differenceDisplay');
        diffDisplay.textContent = formatCurrency(difference);
        
        const finalizeBtn = document.getElementById('finalizeBtn');
        const diffProgress = document.getElementById('diffProgress');

        if (Math.abs(difference) < 0.01) {
            diffDisplay.classList.remove('text-rose-500');
            diffDisplay.classList.add('text-emerald-500');
            if (finalizeBtn) finalizeBtn.disabled = false;
            diffProgress.style.width = '100%';
            diffProgress.classList.remove('bg-rose-500');
            diffProgress.classList.add('bg-emerald-500');
        } else {
            diffDisplay.classList.add('text-rose-500');
            diffDisplay.classList.remove('text-emerald-500');
            if (finalizeBtn) finalizeBtn.disabled = true;
            diffProgress.style.width = '30%';
            diffProgress.classList.add('bg-rose-500');
            diffProgress.classList.remove('bg-emerald-500');
        }
    };

    if (isDraft) {
        document.querySelectorAll('.item-row').forEach(row => {
            row.addEventListener('click', async function(e) {
                const checkbox = this.querySelector('.item-checkbox');
                const ui = this.querySelector('.checkbox-ui');
                const icon = ui.querySelector('i');

                // Toggle state
                checkbox.checked = !checkbox.checked;
                
                if (checkbox.checked) {
                    ui.classList.add('bg-blue-600', 'border-blue-600');
                    ui.classList.remove('border-slate-200');
                    icon.classList.remove('hidden');
                } else {
                    ui.classList.remove('bg-blue-600', 'border-blue-600');
                    ui.classList.add('border-slate-200');
                    icon.classList.add('hidden');
                }

                updateCalculations();

                // Sync with server
                const itemIds = Array.from(document.querySelectorAll('.item-checkbox:checked')).map(cb => cb.value);
                
                try {
                    const response = await fetch(updateUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ item_ids: itemIds })
                    });
                    const data = await response.json();
                    console.log('Synced:', data);
                } catch (err) {
                    console.error('Sync failed:', err);
                }
            });
        });
    }

    // Initial calc
    updateCalculations();
})();
</script>
@endpush
@endsection
