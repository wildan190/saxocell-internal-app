@extends('layouts.app')

@section('title', 'Invoice Details: ' . $invoice->invoice_number)

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <a href="{{ route('home') }}">Home</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item">
        <a href="{{ route('invoices.index') }}">Invoices</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">{{ $invoice->invoice_number }}</div>
</nav>
@endsection

@section('content')
<div class="p-4 md:p-8 max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">{{ $invoice->invoice_number }}</h1>
            <p class="text-slate-500 mt-2 flex items-center gap-2">
                <span class="flex items-center gap-1.5 font-medium text-slate-700">
                    <i data-feather="truck" class="w-4 h-4"></i>
                    {{ $invoice->supplier->name }}
                </span>
                <span class="text-slate-300">•</span>
                <span class="flex items-center gap-1.5">
                    <i data-feather="calendar" class="w-4 h-4"></i>
                    Issued on {{ $invoice->invoice_date->format('M d, Y') }}
                </span>
            </p>
        </div>
        
        <div class="flex flex-wrap gap-3">
            @if($invoice->threeWayMatch?->status === 'discrepancy')
                <button class="flex items-center gap-2 px-6 py-2.5 bg-red-50 hover:bg-red-100 text-red-600 border border-red-100 rounded-xl font-bold transition-all active:scale-95">
                    <i data-feather="alert-triangle" class="w-4 h-4"></i> Flag Discrepancy
                </button>
            @endif
            
            @if($invoice->status === 'matched' && !$invoice->approved_at)
                <!-- Combined Approve & Pay Button -->
                <button type="button" @click="showApprovePayModal = true" class="flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-200 transition-all active:scale-95">
                    <i data-feather="zap" class="w-4 h-4"></i> Approve & Pay
                </button>

                <form action="{{ route('invoices.approve', $invoice->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-6 py-2.5 bg-slate-900 hover:bg-black text-white rounded-xl font-bold shadow-lg shadow-slate-200 transition-all active:scale-95">
                        <i data-feather="check-square" class="w-4 h-4"></i> Post to Ledger (Only)
                    </button>
                </form>
            @endif
            @if($invoice->payment_status === 'unpaid' && $invoice->approved_at)
                <a href="{{ route('finance.payments.create', $invoice->id) }}" class="flex items-center gap-2 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold shadow-lg shadow-emerald-100 transition-all active:scale-95">
                    <i data-feather="credit-card" class="w-4 h-4"></i> Record Payment
                </a>
            @endif
            <button class="flex items-center gap-2 px-6 py-2.5 bg-white border border-slate-200 hover:border-slate-300 text-slate-700 rounded-xl font-bold shadow-sm transition-all active:scale-95" onclick="window.print()">
                <i data-feather="printer" class="w-4 h-4"></i> Print Audit
            </button>
            <a href="{{ route('invoices.index') }}" class="flex items-center gap-2 px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-all active:scale-95">
                <i data-feather="arrow-left" class="w-4 h-4"></i> Back
            </a>
        </div>
    </div>

    <!-- 3-Way Matching Dashboard -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        @php
            $matchItems = [
                ['label' => 'Quantity Match', 'status' => $invoice->threeWayMatch?->quantity_match, 'desc' => 'Receipts vs Invoice', 'icon' => 'box'],
                ['label' => 'Price Audit', 'status' => $invoice->threeWayMatch?->price_match, 'desc' => 'PO vs Billed Price', 'icon' => 'tag'],
                ['label' => 'Gross Match', 'status' => $invoice->threeWayMatch?->total_match, 'desc' => 'Calculated vs Total', 'icon' => 'trending-up'],
            ];
        @endphp

        @foreach($matchItems as $item)
        <div class="bg-white p-6 rounded-[2rem] border {{ $item['status'] ? 'border-emerald-100 bg-emerald-50/20' : 'border-red-100 bg-red-50/20' }} shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <span class="text-[10px] font-black {{ $item['status'] ? 'text-emerald-500' : 'text-red-500' }} uppercase tracking-widest">{{ $item['label'] }}</span>
                <div class="p-2 {{ $item['status'] ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }} rounded-xl">
                    <i data-feather="{{ $item['status'] ? 'check-circle' : 'alert-circle' }}" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="text-2xl font-black {{ $item['status'] ? 'text-emerald-700' : 'text-red-700' }} mb-1">
                {{ $item['status'] ? 'PASSED' : 'FAILED' }}
            </div>
            <p class="text-xs font-medium text-slate-500">{{ $item['desc'] }}</p>
        </div>
        @endforeach
    </div>

    <!-- Main Detail Card -->
    <div class="bg-white/95 backdrop-blur-xl rounded-[2rem] border border-slate-200/60 shadow-xl overflow-hidden">
        <!-- Particulars Grid -->
        <div class="p-8 md:p-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 bg-slate-50/30 border-b border-slate-100">
            <div>
                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Reference PO</span>
                <a href="{{ route('purchase-orders.show', $invoice->purchase_order_id) }}" class="text-base font-bold text-blue-600 hover:text-blue-700 transition-colors">
                    {{ $invoice->purchaseOrder->po_number }}
                </a>
            </div>
            <div>
                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Billing Date</span>
                <span class="block text-base font-bold text-slate-800">{{ $invoice->invoice_date->format('M d, Y') }}</span>
            </div>
            <div>
                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Net Due Date</span>
                <span class="block text-base font-bold text-slate-800">
                    {{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'Immediately' }}
                </span>
            </div>
            <div>
                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Payment Status</span>
                @php
                    $payMap = [
                        'paid' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-600', 'label' => 'PAID'],
                        'unpaid' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'label' => 'UNPAID'],
                        'partial' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-600', 'label' => 'PARTIAL'],
                    ];
                    $p = $payMap[$invoice->payment_status] ?? $payMap['unpaid'];
                @endphp
                <span class="inline-flex px-3 py-1 {{ $p['bg'] }} {{ $p['text'] }} rounded-lg text-[10px] font-black tracking-widest">
                    {{ $p['label'] }}
                </span>
            </div>
        </div>

        <!-- Items Table Container -->
        <div class="p-8 md:p-10">
            <h3 class="text-xl font-extrabold text-slate-900 mb-6 flex items-center gap-3">
                <span class="p-2 bg-slate-100 rounded-lg"><i data-feather="list" class="w-5 h-5"></i></span>
                Accounting Itemization
            </h3>
            
            <div class="overflow-x-auto rounded-2xl border border-slate-100">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest">Specification</th>
                            <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-center">Qty Billed</th>
                            <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-right">Unit Price</th>
                            <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-right">Tax</th>
                            <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($invoice->items as $item)
                        <tr class="hover:bg-slate-50/30 transition-colors">
                            <td class="px-6 py-5">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-800 leading-tight">{{ $item->product->name }}</span>
                                    @if($item->variant)
                                    <span class="text-[11px] font-medium text-slate-400 mt-1 uppercase tracking-wider">
                                        {{ $item->variant->attributes_summary }}
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center font-bold text-slate-700">{{ $item->quantity }}</td>
                            <td class="px-6 py-5 text-right font-medium text-slate-500">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td class="px-6 py-5 text-right font-medium text-slate-400 text-xs">{{ $item->tax_rate }}%</td>
                            <td class="px-6 py-5 text-right font-bold text-slate-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Financial Summary Bar -->
        <div class="bg-slate-50/50 p-8 md:p-10 border-t border-slate-100">
            <div class="flex flex-col md:flex-row items-end justify-end gap-8 text-right">
                <div class="flex flex-col gap-1">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Financial Subtotal</span>
                    <span class="text-xl font-bold text-slate-700">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Aggregated Tax</span>
                    <span class="text-xl font-bold text-slate-700">Rp {{ number_format($invoice->tax_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex flex-col gap-1 md:pl-8 md:border-l border-slate-200">
                    <span class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Final Balance</span>
                    <span class="text-4xl font-black text-blue-600 tracking-tight">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    @if($invoice->notes)
    <div class="mt-8 p-8 bg-blue-50/30 border border-blue-100 rounded-[2rem]">
        <h4 class="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-3">Auditor's Remarks</h4>
        <p class="text-slate-600 text-sm leading-relaxed italic">"{{ $invoice->notes }}"</p>
    </div>
    @endif
</div>

<!-- Approve & Pay Modal -->
<div id="approvePayModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden animate-in zoom-in-95 duration-200">
            <div class="p-10 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="text-2xl font-black text-slate-900">Approve & Pay</h3>
                <button type="button" onclick="closeApprovePayModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i data-feather="x" class="w-6 h-6"></i>
                </button>
            </div>
            <form action="{{ route('invoices.approve_and_pay', $invoice->id) }}" method="POST">
                @csrf
                <div class="p-10 space-y-8">
                    <div class="p-6 bg-blue-50 border border-blue-100 rounded-2xl">
                        <div class="flex items-center gap-4 text-blue-700">
                            <i data-feather="info" class="w-6 h-6 shrink-0"></i>
                            <div class="text-sm font-bold">
                                This will approve the invoice and record a full payment of <span class="font-bold">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span> in one step.
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Select Bank/Cash Account</label>
                        <select name="account_id" class="w-full px-6 py-3 bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:bg-white rounded-xl font-bold text-slate-800 transition-all outline-none shadow-sm" required>
                            <option value="">-- Choose Account --</option>
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->name }} (Rp {{ number_format($account->current_balance, 0, ',', '.') }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="p-8 bg-slate-50 border-t border-slate-100 flex gap-4">
                    <button type="button" onclick="closeApprovePayModal()" class="flex-1 px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold transition-all active:scale-95">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-200 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i data-feather="shield-check" class="w-5 h-5"></i> Confirm & Pay
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function() {
        const modal = document.getElementById('approvePayModal');
        const trigger = document.querySelector('[onclick*="showApprovePayModal"]');
        
        // Overwrite the @click or onclick logic since we aren't using Alpine here for simplicity
        if (trigger) {
            trigger.onclick = (e) => {
                e.preventDefault();
                modal.classList.remove('hidden');
                if (window.feather) feather.replace();
            };
        }

        window.closeApprovePayModal = () => {
            modal.classList.add('hidden');
        };

        // Close on ESC
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeApprovePayModal();
        });
    })();
</script>
@endpush
@endsection
