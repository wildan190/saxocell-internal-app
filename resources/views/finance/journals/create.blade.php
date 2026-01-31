@extends('layouts.app')

@section('title', 'Manual Journal Entry')

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20 font-sans">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div>
            <h1 class="text-5xl font-black text-slate-900 tracking-tight">Manual Journal</h1>
            <p class="text-slate-500 mt-3 font-medium text-lg">Record custom adjustments to the general ledger.</p>
        </div>
        
        <div class="flex gap-4">
            <a href="{{ route('finance.journals.index') }}" class="flex items-center gap-3 px-8 py-4 bg-white border border-slate-200 text-slate-700 rounded-[2rem] font-black transition-all active:scale-95">
                Discard Change
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-8 p-8 bg-rose-50 border-2 border-rose-100 rounded-[2.5rem] text-rose-700 animate-in fade-in slide-in-from-top-4 duration-500">
            <h4 class="font-black text-sm uppercase tracking-widest mb-4 flex items-center gap-2">
                <i data-feather="alert-octagon" class="w-5 h-5"></i> Ledger Imbalance
            </h4>
            <ul class="text-sm font-bold flex flex-col gap-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('finance.journals.store') }}" method="POST" id="journal-form">
        @csrf
        <div class="space-y-10">
            <!-- Header Info -->
            <div class="bg-white rounded-[3rem] border border-slate-200 shadow-xl p-12">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Posting Date</label>
                        <input type="date" name="entry_date" value="{{ date('Y-m-d') }}" class="w-full px-8 py-5 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none shadow-sm" required>
                    </div>
                    <div class="space-y-3 lg:col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Journal Description / Memo</label>
                        <input type="text" name="description" placeholder="e.g. Monthly Rent Allocation January" class="w-full px-8 py-5 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-2xl font-bold text-slate-800 transition-all outline-none shadow-sm" required>
                    </div>
                </div>
            </div>

            <!-- Entries Table -->
            <div class="bg-white rounded-[3rem] border border-slate-100 shadow-2xl overflow-hidden shadow-slate-200/50">
                <div class="p-10 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-2xl font-black text-slate-900">Journal Splits</h3>
                    <button type="button" id="add-row" class="px-6 py-3 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest transition-all hover:scale-105 active:scale-95 flex items-center gap-2">
                        <i data-feather="plus" class="w-4 h-4"></i> Add Account
                    </button>
                </div>
                <div id="journal-rows" class="p-10 space-y-6">
                    <!-- Row 1 -->
                    @for($i=0; $i<2; $i++)
                    <div class="journal-row group flex flex-col lg:flex-row gap-6 p-8 bg-slate-50/50 hover:bg-white border-2 border-transparent hover:border-blue-100 rounded-[2.5rem] transition-all duration-500 relative">
                        <div class="flex-1 space-y-3">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">General Ledger Account</label>
                            <select name="items[{{$i}}][account_id]" class="account-select w-full px-8 py-5 bg-white border-2 border-slate-100 focus:border-blue-500 rounded-2xl font-bold text-slate-800 outline-none appearance-none shadow-sm transition-all" required>
                                <option value="">-- Choose Account --</option>
                                @foreach($accounts as $acc)
                                    <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->name }} ({{ strtoupper($acc->type) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="lg:w-48 space-y-3">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Debit (Increase Asset/Exp)</label>
                            <input type="number" step="0.01" name="items[{{$i}}][debit]" class="debit-input w-full px-8 py-5 bg-white border-2 border-slate-100 focus:border-emerald-500 rounded-2xl font-black text-slate-800 outline-none text-right shadow-sm" placeholder="0.00">
                        </div>
                        <div class="lg:w-48 space-y-3">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Credit (Increase Liab/Rev)</label>
                            <input type="number" step="0.01" name="items[{{$i}}][credit]" class="credit-input w-full px-8 py-5 bg-white border-2 border-slate-100 focus:border-rose-500 rounded-2xl font-black text-slate-800 outline-none text-right shadow-sm" placeholder="0.00">
                        </div>
                        <div class="hidden lg:flex items-end pb-3">
                            <button type="button" class="remove-row p-4 text-slate-300 hover:text-rose-500 opacity-0 group-hover:opacity-100 transition-all">
                                <i data-feather="x-circle" class="w-6 h-6"></i>
                            </button>
                        </div>
                    </div>
                    @endfor
                </div>

                <div class="p-10 border-t border-slate-100 bg-slate-900 text-white flex justify-between items-center rounded-b-[3rem]">
                    <div class="flex gap-12">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Total Debits</p>
                            <p id="total-debit" class="text-3xl font-black tracking-tighter text-emerald-400">RP 0</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Total Credits</p>
                            <p id="total-credit" class="text-3xl font-black tracking-tighter text-rose-400">RP 0</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Out of Balance</p>
                        <p id="imbalance" class="text-3xl font-black tracking-tighter">Balanced</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-6">
                <button type="submit" class="group flex items-center gap-4 px-12 py-6 bg-blue-600 hover:bg-blue-700 text-white rounded-[2.5rem] font-black text-xl shadow-2xl shadow-blue-200 transition-all active:scale-95">
                    Post to General Ledger <i data-feather="send" class="w-6 h-6 group-hover:translate-x-2 transition-transform"></i>
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
(function() {
    let rowIndex = 2;
    const container = document.getElementById('journal-rows');
    const addBtn = document.getElementById('add-row');

    function updateTotals() {
        let debits = 0;
        let credits = 0;
        document.querySelectorAll('.debit-input').forEach(input => debits += parseFloat(input.value || 0));
        document.querySelectorAll('.credit-input').forEach(input => credits += parseFloat(input.value || 0));

        document.getElementById('total-debit').textContent = 'RP ' + debits.toLocaleString('id-ID');
        document.getElementById('total-credit').textContent = 'RP ' + credits.toLocaleString('id-ID');

        const imbalance = Math.abs(debits - credits);
        const imbEl = document.getElementById('imbalance');
        if (imbalance < 0.01) {
            imbEl.textContent = 'Balanced';
            imbEl.className = 'text-3xl font-black tracking-tighter text-emerald-400';
        } else {
            imbEl.textContent = 'RP ' + imbalance.toLocaleString('id-ID');
            imbEl.className = 'text-3xl font-black tracking-tighter text-rose-400';
        }
    }

    container.addEventListener('input', updateTotals);

    addBtn.addEventListener('click', function() {
        const firstRow = container.querySelector('.journal-row');
        const newRow = firstRow.cloneNode(true);
        const index = rowIndex++;

        newRow.querySelector('.account-select').name = `items[${index}][account_id]`;
        newRow.querySelector('.account-select').value = "";
        
        const debit = newRow.querySelector('.debit-input');
        debit.name = `items[${index}][debit]`;
        debit.value = "";
        
        const credit = newRow.querySelector('.credit-input');
        credit.name = `items[${index}][credit]`;
        credit.value = "";

        newRow.style.opacity = '0';
        newRow.style.transform = 'translateY(20px)';
        container.appendChild(newRow);
        
        setTimeout(() => {
            newRow.style.opacity = '1';
            newRow.style.transform = 'translateY(0)';
        }, 10);

        if (window.feather) feather.replace();
    });

    container.addEventListener('click', function(e) {
        const remove = e.target.closest('.remove-row');
        if (remove && container.querySelectorAll('.journal-row').length > 2) {
            const row = remove.closest('.journal-row');
            row.style.opacity = '0';
            row.style.transform = 'scale(0.9)';
            setTimeout(() => {
                row.remove();
                updateTotals();
            }, 300);
        }
    });

    // Handle Debit/Credit exclusivity
    container.addEventListener('change', function(e) {
        if (e.target.classList.contains('debit-input') && e.target.value > 0) {
            e.target.closest('.journal-row').querySelector('.credit-input').value = "";
        }
        if (e.target.classList.contains('credit-input') && e.target.value > 0) {
            e.target.closest('.journal-row').querySelector('.debit-input').value = "";
        }
        updateTotals();
    });
})();
</script>
@endpush
@endsection
