@extends('layouts.app')

@section('title', 'Record Invoice')

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
    <div class="breadcrumb-item active">Record Invoice</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">Accounts Payable Entry</h1>
            <p class="page-subtitle">Standardize supplier billing against procurement contracts</p>
        </div>
    </div>

    @if(!$selectedPo)
    <div style="display: flex; justify-content: center; padding: 4rem 0;">
        <div class="card" style="width: 100%; max-width: 550px; text-align: center; background: white;">
            <div style="width: 64px; height: 64px; background: #eff6ff; color: #3b82f6; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <i data-feather="file-text" style="width: 32px; height: 32px;"></i>
            </div>
            <h3 style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-bottom: 0.75rem;">Link Purchase Order</h3>
            <p style="color: #64748b; margin-bottom: 2.5rem; font-size: 0.95rem;">Every supplier invoice must be validated against an authorized Purchase Order to ensure fiscal compliance.</p>
            
            <form action="{{ route('invoices.create') }}" method="GET">
                <div class="form-group" style="text-align: left;">
                    <label class="form-label" style="margin-bottom: 0.5rem; display: block;">Authorized PO Reference</label>
                    <select name="purchase_order_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Search PO Number or Supplier...</option>
                        @foreach($purchaseOrders as $po)
                            <option value="{{ $po->id }}">
                                {{ $po->po_number }} — {{ $po->supplier->name }} (PO Total: Rp {{ number_format($po->total_amount, 0) }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
            
            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #f1f5f9;">
                <p style="font-size: 0.8rem; color: #94a3b8;">Common step: Find the PO number mentioned on the supplier's paper invoice.</p>
            </div>
        </div>
    </div>
    @else
    <form action="{{ route('invoices.store') }}" method="POST" class="card">
        @csrf
        <input type="hidden" name="purchase_order_id" value="{{ $selectedPo->id }}">

        <div class="form-grid">
            <div class="form-section">
                <h3 class="section-title">
                    <i data-feather="file-plus"></i>
                    Invoice Header
                </h3>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Linked Supplier</label>
                        <div class="form-control" style="background-color: #f8fafc; font-weight: 700; color: #475569;">
                            {{ $selectedPo->supplier->name }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Linked PO Reference</label>
                        <div style="display: flex; gap: 0.5rem; align-items: center;">
                            <div class="form-control" style="background-color: #f8fafc; font-weight: 700; color: #475569; flex: 1;">
                                {{ $selectedPo->po_number }}
                            </div>
                            <a href="{{ route('invoices.create') }}" class="btn btn-secondary btn-sm" title="Switch PO">
                                <i data-feather="refresh-ccw"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Invoice Number (Supplier's Ref)</label>
                        <input type="text" name="invoice_number" class="form-control @error('invoice_number') is-invalid @enderror" value="{{ old('invoice_number') }}" required placeholder="e.g. S-900332">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Invoice Date</label>
                        <input type="date" name="invoice_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Payment Due Date</label>
                    <input type="date" name="due_date" class="form-control" value="{{ date('Y-m-d', strtotime('+30 days')) }}">
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">
                    <i data-feather="shield"></i>
                    Verification & Notes
                </h3>

                <div style="background: #f0f9ff; border: 1px solid #e0f2fe; border-radius: 1rem; padding: 1.25rem; margin-bottom: 1.5rem;">
                    <h5 style="font-size: 0.875rem; font-weight: 700; color: #0369a1; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i data-feather="check-circle" style="width: 16px;"></i> 3-Way Match Protocol
                    </h5>
                    <p style="font-size: 0.75rem; color: #0c4a6e; line-height: 1.6; margin: 0;">
                        System will compare this invoice against <strong>{{ $selectedPo->po_number }}</strong> and physical Goods Receipts.
                    </p>
                </div>

                <div class="form-group">
                    <label class="form-label">Internal Audit Remarks</label>
                    <textarea name="notes" class="form-control" rows="4" placeholder="Mention any discrepancies handled..."></textarea>
                </div>
            </div>
        </div>

        <!-- line Items -->
        <div style="margin-top: 2rem; border: 1px solid rgba(148, 163, 184, 0.1); border-radius: 1rem; overflow: hidden;">
            <div style="padding: 1rem 1.5rem; background: #fbfcfd; border-bottom: 1px solid #f1f5f9;">
                <h4 style="font-size: 0.875rem; font-weight: 700; color: #1e293b;">Billed Items Verification</h4>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 40%;">Product Item</th>
                            <th style="width: 15%;">Qty</th>
                            <th style="width: 20%;">Unit Price (Net)</th>
                            <th style="width: 10%;">Tax %</th>
                            <th style="text-align: right; width: 15%;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($selectedPo->items as $index => $item)
                        <tr class="item-row">
                            <td>
                                <div style="display: flex; flex-direction: column;">
                                    <span style="font-weight: 700; color: #1e293b;">{{ $item->product->name }}</span>
                                    <span style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.25rem;">
                                        Ordered: {{ $item->quantity_ordered }} units @ Rp {{ number_format($item->unit_price, 0) }}
                                    </span>
                                    <input type="hidden" name="items[{{ $index }}][purchase_order_item_id]" value="{{ $item->id }}">
                                </div>
                            </td>
                            <td>
                                <input type="number" name="items[{{ $index }}][quantity]" class="form-control qty-input" value="{{ $item->quantity_ordered }}" required>
                            </td>
                            <td>
                                <div style="position: relative;">
                                    <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.8rem;">Rp</span>
                                    <input type="number" name="items[{{ $index }}][unit_price]" class="form-control price-input" style="padding-left: 2.5rem;" step="0.01" value="{{ $item->unit_price }}" required>
                                </div>
                            </td>
                            <td>
                                <input type="number" name="items[{{ $index }}][tax_rate]" class="form-control tax-input" value="{{ $item->tax_rate }}" required>
                            </td>
                            <td class="item-total" style="text-align: right; font-weight: 800; color: #1e293b; vertical-align: middle;">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="form-actions" style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(148, 163, 184, 0.1);">
            <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                Go Back
            </a>
            <button type="submit" class="btn btn-primary">
                <i data-feather="save"></i> Confirm & Record Invoice
            </button>
        </div>
    </form>
    @endif
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('.item-row');
        
        function updateRowTotal(row) {
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const tax = parseFloat(row.querySelector('.tax-input').value) || 0;
            const total = (qty * price) + (qty * price * tax / 100);
            row.querySelector('.item-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        rows.forEach(row => {
            row.querySelectorAll('input').forEach(input => {
                input.addEventListener('input', () => updateRowTotal(row));
            });
        });
    });
</script>
@endpush
