@extends('layouts.app')

@section('title', 'Pay Supplier: ' . $warehouse->name)

@section('content')
<div class="content-wrapper bg-slate-50/50 min-h-screen pb-20">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div>
            <a href="{{ route('warehouses.show', $warehouse) }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-600 transition-colors mb-2 text-xs font-bold uppercase tracking-wider">
                <i data-feather="arrow-left" class="w-4 h-4"></i> Back to Warehouse
            </a>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Pay Supplier</h1>
            <p class="text-slate-500 mt-2 font-medium text-sm">Settle supplier invoices using {{ $warehouse->name }} accounts.</p>
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

        <form action="{{ route('warehouses.pay-supplier.store', $warehouse) }}" method="POST" class="space-y-6">
            @csrf

            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl space-y-6">
                
                <!-- Invoice Selection -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Select Unpaid Invoice</label>
                    <select name="invoice_id" id="invoice_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors" required>
                        <option value="">-- Select Invoice --</option>
                        @foreach($invoices as $invoice)
                            <option value="{{ $invoice->id }}" data-amount="{{ $invoice->total_amount }}">
                                #{{ $invoice->invoice_number }} - {{ $invoice->supplier->name }} (RP {{ number_format($invoice->total_amount, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                    @if($invoices->isEmpty())
                        <p class="text-xs text-amber-600 mt-2 italic font-medium">No unpaid invoices found for this warehouse.</p>
                    @endif
                </div>

                <!-- Date & Method -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Payment Date</label>
                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Payment Method</label>
                        <select name="payment_method" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors" required>
                            <option value="transfer">Bank Transfer</option>
                            <option value="cash">Cash</option>
                            <option value="cheque">Cheque</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                <!-- Financials -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pay From (Warehouse Account)</label>
                    <select name="account_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors" required>
                        <option value="">-- Select Bank/Cash Account --</option>
                        @foreach($cashAccounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }} (Bal: RP {{ number_format($account->current_balance, 0, ',', '.') }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Amount to Pay (RP)</label>
                    <input type="number" name="amount" id="amount" step="0.01" min="0.01" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-black text-xl text-slate-900 focus:outline-none focus:border-blue-500 transition-colors" placeholder="0" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Reference Number</label>
                        <input type="text" name="reference_number" placeholder="e.g. TRF-12345" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Notes</label>
                        <input type="text" name="notes" placeholder="Optional notes" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-semibold text-slate-700 focus:outline-none focus:border-blue-500 transition-colors">
                    </div>
                </div>

            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('warehouses.show', $warehouse) }}" class="px-6 py-3 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl font-bold text-sm transition-all">Cancel</a>
                <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm transition-all shadow-lg shadow-blue-200" {{ $invoices->isEmpty() ? 'disabled' : '' }}>
                    Confirm Payment
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        const invoiceSelect = document.getElementById('invoice_id');
        const amountInput = document.getElementById('amount');

        invoiceSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                const amount = selectedOption.getAttribute('data-amount');
                amountInput.value = amount;
            } else {
                amountInput.value = '';
            }
        });
    });
</script>
@endpush
@endsection
