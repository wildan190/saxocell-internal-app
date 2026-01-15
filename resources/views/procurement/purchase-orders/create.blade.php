@extends('layouts.app')

@section('title', 'Create Purchase Order')

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <a href="{{ route('home') }}">Home</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item">
        <a href="{{ route('purchase-orders.index') }}">Purchase Orders</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">Create PO</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">Create Purchase Order</h1>
            <p class="page-subtitle">Draft a new procurement request with automated calculations</p>
        </div>
    </div>

    <form action="{{ route('purchase-orders.store') }}" method="POST" id="poForm" class="card">
        @csrf

        <div class="form-grid">
            <!-- main Section -->
            <div class="form-section">
                <h3 class="section-title">
                    <i data-feather="info"></i>
                    Order Details
                </h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Supplier Partner</label>
                        <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                            <option value="">Choose Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Order Date</label>
                        <input type="date" name="order_date" class="form-control" value="{{ old('order_date', date('Y-m-d')) }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Expected Delivery Date</label>
                    <input type="date" name="expected_delivery_date" class="form-control" value="{{ old('expected_delivery_date') }}">
                </div>

                <!-- Items Board -->
                <div style="margin-top: 2rem; border: 1px solid rgba(148, 163, 184, 0.1); border-radius: 1rem; overflow: hidden;">
                    <div style="padding: 1rem 1.5rem; background: #f8fafc; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                        <h4 style="font-size: 0.875rem; font-weight: 700; color: #475569; display: flex; align-items: center; gap: 0.5rem;">
                            <i data-feather="layers" style="width: 16px;"></i>
                            Line Items
                        </h4>
                        <button type="button" class="btn btn-secondary btn-sm" id="addItem">
                            <i data-feather="plus-circle"></i> Add Item
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table" id="itemsTable">
                            <thead>
                                <tr>
                                    <th style="width: 45%;">Product Description</th>
                                    <th style="width: 12%;">Qty</th>
                                    <th style="width: 18%;">Unit Price</th>
                                    <th style="width: 10%;">Tax %</th>
                                    <th style="width: 15%; text-align: right;">Subtotal</th>
                                    <th style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="empty-items-row">
                                    <td colspan="6" style="padding: 3rem; text-align: center; color: #94a3b8; font-size: 0.875rem;">
                                        Click "Add Item" to start building your order.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Summary Sidebar -->
            <div class="form-section">
                <h3 class="section-title">
                    <i data-feather="pie-chart"></i>
                    Total Summary
                </h3>
                
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: #64748b; font-size: 0.875rem; font-weight: 500;">Subtotal</span>
                        <span id="displaySubtotal" style="font-weight: 700; color: #1e293b;">Rp 0</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: #64748b; font-size: 0.875rem; font-weight: 500;">Estimated Tax</span>
                        <span id="displayTax" style="font-weight: 700; color: #1e293b;">Rp 0</span>
                    </div>
                    <div style="height: 1px; background: #e2e8f0; margin: 0.25rem 0;"></div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-weight: 700; color: #1e293b;">Grand Total</span>
                        <span id="displayTotal" style="font-size: 1.125rem; font-weight: 700; color: #174ae4;">Rp 0</span>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label class="form-label">Internal Notes / Terms</label>
                    <textarea name="notes" class="form-control" rows="4" placeholder="Terms, conditions, or internal notes...">{{ old('notes') }}</textarea>
                </div>

                <div style="margin-top: 2rem; padding: 1.25rem; background: #eff6ff; border: 1px solid #dbeafe; border-radius: 1rem;">
                    <p style="font-size: 0.75rem; color: #1e40af; line-height: 1.6; margin: 0;">
                        Ensure all items and prices are verified. Grand total is calculated automatically.
                    </p>
                </div>
            </div>
        </div>

        <div class="form-actions" style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(148, 163, 184, 0.1);">
            <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">
                <i data-feather="x"></i> Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i data-feather="save"></i> Confirm & Create Order
            </button>
        </div>
    </form>
</div>

<!-- Row Template (Hidden) -->
<template id="itemRowTemplate">
    <tr class="item-row">
        <td>
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <select name="items[INDEX][product_id]" class="form-select product-select" required>
                    <option value="">Select Product...</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-variants="{{ json_encode($product->variants) }}">
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
                <div class="variant-container" style="display: none;">
                    <select name="items[INDEX][product_variant_id]" class="form-select variant-select" style="font-size: 0.8rem; background-color: #f8fafc;">
                        <option value="">Choose Variant...</option>
                    </select>
                </div>
            </div>
        </td>
        <td>
            <input type="number" name="items[INDEX][quantity_ordered]" class="form-control qty-input" min="1" value="1" required>
        </td>
        <td>
            <div style="position: relative;">
                <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.8rem;">Rp</span>
                <input type="number" name="items[INDEX][unit_price]" class="form-control price-input" style="padding-left: 2.5rem;" min="0" step="0.01" required>
            </div>
        </td>
        <td>
            <input type="number" name="items[INDEX][tax_rate]" class="form-control tax-input" min="0" max="100" value="0">
        </td>
        <td class="item-total" style="text-align: right; font-weight: 700; color: #1e293b; vertical-align: middle;">
            Rp 0
        </td>
        <td style="text-align: right; vertical-align: middle;">
            <button type="button" class="btn-icon-delete delete-item">
                <i data-feather="trash-2"></i>
            </button>
        </td>
    </tr>
</template>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const itemsTable = document.getElementById('itemsTable').getElementsByTagName('tbody')[0];
        const addItemBtn = document.getElementById('addItem');
        const rowTemplate = document.getElementById('itemRowTemplate');
        let rowIndex = 0;

        function updateTotals() {
            let subtotal = 0;
            let totalTax = 0;

            const rows = itemsTable.querySelectorAll('tr.item-row');
            rows.forEach(row => {
                const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;
                const taxRate = parseFloat(row.querySelector('.tax-input').value) || 0;

                const itemSubtotal = qty * price;
                const itemTax = itemSubtotal * (taxRate / 100);
                
                row.querySelector('.item-total').textContent = 'Rp ' + (itemSubtotal + itemTax).toLocaleString('id-ID');
                
                subtotal += itemSubtotal;
                totalTax += itemTax;
            });

            document.getElementById('displaySubtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
            document.getElementById('displayTax').textContent = 'Rp ' + totalTax.toLocaleString('id-ID');
            document.getElementById('displayTotal').textContent = 'Rp ' + (subtotal + totalTax).toLocaleString('id-ID');
        }

        addItemBtn.addEventListener('click', function() {
            const emptyRow = itemsTable.querySelector('.empty-items-row');
            if (emptyRow) emptyRow.style.display = 'none';

            const content = rowTemplate.innerHTML.replace(/INDEX/g, rowIndex++);
            const newRow = document.createElement('tr');
            newRow.className = 'item-row';
            newRow.innerHTML = content;
            itemsTable.appendChild(newRow);

            feather.replace();

            // Product selection logic
            const productSelect = newRow.querySelector('.product-select');
            const variantContainer = newRow.querySelector('.variant-container');
            const variantSelect = newRow.querySelector('.variant-select');

            productSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const variants = JSON.parse(selectedOption.dataset.variants || '[]');

                variantSelect.innerHTML = '<option value="">Choose Variant...</option>';
                if (variants.length > 0) {
                    variants.forEach(v => {
                        const opt = document.createElement('option');
                        opt.value = v.id;
                        opt.textContent = Object.entries(v.formatted_attributes).map(([k, v]) => `${k}: ${v}`).join(', ');
                        variantSelect.appendChild(opt);
                    });
                    variantContainer.style.display = 'block';
                    variantSelect.required = true;
                } else {
                    variantContainer.style.display = 'none';
                    variantSelect.required = false;
                }
            });

            // Input changes
            newRow.querySelectorAll('input').forEach(input => {
                input.addEventListener('input', updateTotals);
            });

            // Delete item
            newRow.querySelector('.delete-item').addEventListener('click', function() {
                newRow.remove();
                if (itemsTable.querySelectorAll('tr.item-row').length === 0) {
                    const emptyRow = itemsTable.querySelector('.empty-items-row');
                    if (emptyRow) emptyRow.style.display = 'table-row';
                }
                updateTotals();
            });

            updateTotals();
        });

        // Initialize with one row
        addItemBtn.click();
    });
</script>
@endpush
