@extends('layouts.app')

@section('title', 'Log Inventory Movement')

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <a href="{{ route('home') }}">Home</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item">
        <a href="{{ route('inventory.index') }}">Inventory</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">New Movement</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">Manual Stock Adjustment</h1>
            <p class="page-subtitle">Perform precise inventory corrections or manual receipts</p>
        </div>
    </div>

    <form action="{{ route('inventory.store') }}" method="POST" id="inventoryForm" class="card">
        @csrf

        <div class="form-grid">
            <!-- Specification Section -->
            <div class="form-section">
                <h3 class="section-title">
                    <i data-feather="package"></i>
                    Transaction Specification
                </h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="type" class="form-label">Nature of Movement</label>
                        <select id="type" name="type" class="form-control" required>
                            <option value="">Select Movement Type...</option>
                            <option value="in" {{ old('type') == 'in' ? 'selected' : '' }}>Stock Inbound (Manual Receipt)</option>
                            <option value="out" {{ old('type') == 'out' ? 'selected' : '' }}>Stock Outbound (Scrapped/Lost)</option>
                            <option value="adjustment" {{ old('type') == 'adjustment' ? 'selected' : '' }}>Reconciliation Adjustment</option>
                        </select>
                        @error('type') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label for="warehouse_id" class="form-label">Warehouse Location</label>
                        <select id="warehouse_id" name="warehouse_id" class="form-control">
                            <option value="">Select Warehouse...</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                    {{ $warehouse->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('warehouse_id') <p class="error-message">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group" id="supplierGroup">
                        <label for="supplier_id" class="form-label">Supplier Partner (Optional)</label>
                        <select id="supplier_id" name="supplier_id" class="form-control">
                            <option value="">Select Associated Supplier...</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="reference_number" class="form-label">Internal Reference</label>
                        <input type="text" id="reference_number" name="reference_number" class="form-control" value="{{ old('reference_number') }}" placeholder="e.g. ADJ-001">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group" style="position: relative;">
                        <label for="product_id" class="form-label">Target Product</label>
                        <div style="display: flex; gap: 0.5rem;">
                            <select id="product_id" name="product_id" class="form-control" style="flex: 1;" required>
                                <option value="">Select target product...</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                            data-sku="{{ $product->sku }}"
                                            {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} (SKU: {{ $product->sku }})
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" onclick="startScan()" class="btn btn-secondary" title="Scan QR Code" style="padding: 0.5rem 1rem;">
                                <i data-feather="maximize"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group" id="variantGroup" style="display: none;">
                        <label for="product_variant_id" class="form-label">Variant Identifier</label>
                        <select id="product_variant_id" name="product_variant_id" class="form-control">
                            <option value="">Select Specific Variant...</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="quantity" class="form-label">Quantity Value</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" value="{{ old('quantity') }}" min="1" required placeholder="0">
                    </div>
                    <div class="form-group">
                    </div>
                </div>
            </div>

            <!-- Context Section -->
            <div class="form-section">
                <h3 class="section-title">
                    <i data-feather="info"></i>
                    Context & Remarks
                </h3>

                <div class="form-group">
                    <label for="notes" class="form-label">Justification / Remarks</label>
                    <textarea id="notes" name="notes" class="form-control" rows="4" placeholder="Briefly explain this manual stock adjustment for the audit log...">{{ old('notes') }}</textarea>
                </div>

                <div style="background: #ecfdf5; border: 1px solid #dcfce7; border-radius: 1rem; padding: 1.25rem;">
                    <h5 style="font-size: 0.875rem; font-weight: 700; color: #065f46; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i data-feather="info" style="width: 16px;"></i> Inventory Protocol
                    </h5>
                    <p style="font-size: 0.75rem; color: #065f46; line-height: 1.6; margin: 0;">
                        Manual updates are logged and traceable. Ensure counts are verified before submission.
                    </p>
                </div>
            </div>
        </div>

        <div class="form-actions" style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(148, 163, 184, 0.1);">
            <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                Go Back
            </a>
            <button type="submit" class="btn btn-primary">
                <i data-feather="check-circle"></i> Confim Adjustment
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productSelect = document.getElementById('product_id');
        const variantGroup = document.getElementById('variantGroup');
        const variantSelect = document.getElementById('product_variant_id');
        const typeSelect = document.getElementById('type');
        const supplierGroup = document.getElementById('supplierGroup');

        const productsData = @json($products);

        function updateVariants(selectedVariantId = null) {
            const productId = productSelect.value;
            const product = productsData.find(p => p.id == productId);
            
            if (product && product.variants && product.variants.length > 0) {
                variantGroup.style.display = 'block';
                variantSelect.innerHTML = '<option value="">Select Specific Variant...</option>';
                
                product.variants.forEach(variant => {
                    const option = document.createElement('option');
                    option.value = variant.id;
                    option.textContent = `${variant.name} (SKU: ${variant.sku})`;
                    if (variant.id == selectedVariantId) option.selected = true;
                    variantSelect.appendChild(option);
                });
            } else {
                variantGroup.style.display = 'none';
                variantSelect.innerHTML = '<option value="">Select Specific Variant...</option>';
            }
        }

        function toggleSupplier() {
            if (typeSelect.value === 'in') {
                supplierGroup.style.opacity = '1';
                supplierGroup.style.pointerEvents = 'auto';
            } else {
                supplierGroup.style.opacity = '0.4';
                supplierGroup.style.pointerEvents = 'none';
                document.getElementById('supplier_id').value = '';
            }
        }

        window.startScan = function() {
            window.openQRScanner((sku) => {
                console.log("Scanned SKU:", sku);
                
                // Try to find variant first
                let foundVariant = null;
                let foundProduct = null;

                for (const product of productsData) {
                    const variant = product.variants.find(v => v.sku === sku);
                    if (variant) {
                        foundVariant = variant;
                        foundProduct = product;
                        break;
                    }
                    if (product.sku === sku) {
                        foundProduct = product;
                        break;
                    }
                }

                if (foundProduct) {
                    productSelect.value = foundProduct.id;
                    updateVariants(foundVariant ? foundVariant.id : null);
                    
                    // Add success effect
                    productSelect.style.borderColor = '#10b981';
                    productSelect.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.2)';
                    setTimeout(() => {
                        productSelect.style.borderColor = '';
                        productSelect.style.boxShadow = '';
                    }, 2000);
                } else {
                    alert("Product with SKU '" + sku + "' not found in active inventory.");
                }
            });
        };

        productSelect.addEventListener('change', () => updateVariants());
        typeSelect.addEventListener('change', toggleSupplier);

        // Initial setup
        if (productSelect.value) updateVariants(variantSelect.value);
        toggleSupplier();
    });
</script>
@endpush
@endsection
