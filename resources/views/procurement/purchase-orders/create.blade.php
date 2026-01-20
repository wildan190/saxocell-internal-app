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

                <div class="overflow-x-auto">
                    <table class="table min-w-[800px]" id="itemsTable">
                        <thead>
                            <tr class="text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <th class="p-3 w-5/12 min-w-[300px]">Product Description</th>
                                <th class="p-3 w-1/12 min-w-[100px]">Qty</th>
                                <th class="p-3 w-2/12 min-w-[150px]">Unit Price</th>
                                <th class="p-3 w-1/12 min-w-[100px]">Tax %</th>
                                <th class="p-3 w-2/12 min-w-[150px] text-right">Subtotal</th>
                                <th class="p-3 w-10"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr class="empty-items-row">
                                <td colspan="6" class="p-12 text-center text-slate-400 text-sm">
                                    <div class="flex flex-col items-center gap-2">
                                        <i data-feather="shopping-cart" class="w-8 h-8 opacity-50"></i>
                                        <span>Click "Add Item" to start building your order.</span>
                                    </div>
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
    <tr class="item-row hover:bg-slate-50 transition-colors">
        <td class="p-3">
            <div class="flex flex-col gap-2">
                <select name="items[INDEX][product_id]" class="form-select product-select text-sm w-full font-medium" required>
                    <option value="">Select Product...</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-variants="{{ json_encode($product->variants) }}">
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
                <div class="variant-container hidden">
                    <select name="items[INDEX][product_variant_id]" class="form-select variant-select text-xs bg-slate-50 border-slate-200">
                        <option value="">Choose Variant...</option>
                    </select>
                </div>
            </div>
        </td>
        <td class="p-3 align-top">
            <input type="number" name="items[INDEX][quantity_ordered]" class="form-control qty-input text-center font-medium" min="1" value="1" required>
        </td>
        <td class="p-3 align-top">
            <div class="relative">
                <span class="absolute left-3 top-2.5 text-slate-400 text-sm">Rp</span>
                <input type="number" name="items[INDEX][unit_price]" class="form-control price-input pl-10" min="0" step="0.01" placeholder="0" required>
            </div>
        </td>
        <td class="p-3 align-top">
            <div class="relative">
                <input type="number" name="items[INDEX][tax_rate]" class="form-control tax-input text-center" min="0" max="100" value="11">
                <span class="absolute right-8 top-2.5 text-slate-400 text-sm opacity-50">%</span>
            </div>
        </td>
        <td class="p-3 align-top text-right font-bold text-slate-700 bg-slate-50/50 item-total pt-4">
            Rp 0
        </td>
        <td class="p-3 align-top text-right">
            <button type="button" class="text-slate-400 hover:text-red-500 transition-colors delete-item p-2">
                <i data-feather="trash-2" class="w-4 h-4"></i>
            </button>
        </td>
    </tr>
</template>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const itemsTable = document.getElementById('itemsTable');
        const itemsBody = itemsTable.getElementsByTagName('tbody')[0];
        const addItemBtn = document.getElementById('addItem');
        const rowTemplate = document.getElementById('itemRowTemplate');
        let rowIndex = 0;

        // --- Event Delegation for Calculations ---
        itemsTable.addEventListener('input', function(e) {
            if (e.target.matches('.qty-input, .price-input, .tax-input')) {
                updateTotals();
            }
        });

        // --- Event Delegation for Delete ---
        itemsTable.addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('.delete-item');
            if (deleteBtn) {
                e.preventDefault();
                e.stopPropagation();
                
                // Optional: Prevent deleting the last row if required, 
                // but checking current count allows deleting down to 0 and showing empty state.
                const row = deleteBtn.closest('tr');
                if (row) {
                    row.remove();
                    checkEmptyState();
                    updateTotals();
                }
            }
        });

        // --- Event Delegation for Product Selection ---
        itemsTable.addEventListener('change', function(e) {
            if (e.target.matches('.product-select')) {
                const select = e.target;
                const row = select.closest('tr');
                const variantContainer = row.querySelector('.variant-container');
                const variantSelect = row.querySelector('.variant-select');
                
                const selectedOption = select.options[select.selectedIndex];
                const variantsData = selectedOption.dataset.variants;

                // Reset variant select
                variantSelect.innerHTML = '<option value="">Choose Variant...</option>';
                
                if (variantsData) {
                    try {
                        const variants = JSON.parse(variantsData);
                        if (variants.length > 0) {
                            variants.forEach(v => {
                                const opt = document.createElement('option');
                                opt.value = v.id;
                                // Handle attribute formatting safely
                                const name = v.name || v.sku || 'Variant';
                                let attrs = '';
                                if (v.formatted_attributes) {
                                    attrs = Object.entries(v.formatted_attributes).map(([k, val]) => `${k}: ${val}`).join(', ');
                                }
                                opt.textContent = attrs ? `${name} (${attrs})` : name;
                                variantSelect.appendChild(opt);
                            });
                            variantContainer.style.display = 'block';
                            variantSelect.required = true;
                        } else {
                            variantContainer.style.display = 'none';
                            variantSelect.required = false;    
                        }
                    } catch (err) {
                        console.error("Error parsing variants", err);
                        variantContainer.style.display = 'none';
                    }
                } else {
                    variantContainer.style.display = 'none';
                    variantSelect.required = false;
                }
            }
        });

        function checkEmptyState() {
            const hasRows = itemsBody.querySelectorAll('tr.item-row').length > 0;
            const emptyRow = itemsBody.querySelector('.empty-items-row');
            if (emptyRow) {
                emptyRow.style.display = hasRows ? 'none' : 'table-row';
            }
        }

        function updateTotals() {
            let subtotal = 0;
            let totalTax = 0;

            const rows = itemsBody.querySelectorAll('tr.item-row');
            rows.forEach(row => {
                const qtyInput = row.querySelector('.qty-input');
                const priceInput = row.querySelector('.price-input');
                const taxInput = row.querySelector('.tax-input');

                const qty = parseFloat(qtyInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                const taxRate = parseFloat(taxInput.value) || 0;

                const itemSubtotal = qty * price;
                const itemTax = itemSubtotal * (taxRate / 100);
                
                // Update row total
                const rowTotalEl = row.querySelector('.item-total');
                if (rowTotalEl) {
                    rowTotalEl.textContent = 'Rp ' + (itemSubtotal + itemTax).toLocaleString('id-ID');
                }
                
                subtotal += itemSubtotal;
                totalTax += itemTax;
            });

            const displaySubtotal = document.getElementById('displaySubtotal');
            const displayTax = document.getElementById('displayTax');
            const displayTotal = document.getElementById('displayTotal');

            if (displaySubtotal) displaySubtotal.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
            if (displayTax) displayTax.textContent = 'Rp ' + totalTax.toLocaleString('id-ID');
            if (displayTotal) displayTotal.textContent = 'Rp ' + (subtotal + totalTax).toLocaleString('id-ID');
        }

        addItemBtn.addEventListener('click', function() {
            // Replace placeholder
            const content = rowTemplate.innerHTML.replace(/INDEX/g, rowIndex++);
            
            // Insert cleanly
            // Note: rowTemplate.innerHTML contains <tr>...</tr>, so we need to append a TR, but the template content usually HAS the tr tag itself or is inner content?
            // Checking previous code: <template id="itemRowTemplate"><tr class="item-row">...</tr></template>
            // So innerHTML is the TR itself.
            // But we cannot just append string to tbody. We need `insertAdjacentHTML` or generic element creation.
            
            itemsBody.insertAdjacentHTML('beforeend', content);
            
            // Re-initialize icons for new content
            if (typeof feather !== 'undefined') feather.replace();
            
            checkEmptyState();
            updateTotals();
        });

        // Initialize
        checkEmptyState();
        addItemBtn.click(); // Add first row
    });
</script>
@endpush
