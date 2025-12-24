@extends('layouts.app')

@section('title', 'New Inventory Transaction')

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
    <div class="breadcrumb-item active">New Transaction</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
<div class="page-header">
    <div>
        <h1 class="page-title">New Inventory Transaction</h1>
        <p class="page-subtitle">Record stock movement</p>
    </div>
</div>

<form action="{{ route('inventory.store') }}" method="POST" class="form-card" id="inventoryForm">
    @csrf

    <div class="form-section">
        <h3 class="section-title">Transaction Details</h3>

        <div class="form-row">
            <div class="form-group">
                <label for="type" class="form-label">Transaction Type *</label>
                <select id="type" name="type" class="form-select" required>
                    <option value="">Select Type</option>
                    <option value="in" {{ old('type') == 'in' ? 'selected' : '' }}>Stock In (Purchase/Receive)</option>
                    <option value="out" {{ old('type') == 'out' ? 'selected' : '' }}>Stock Out (Sale/Loss)</option>
                    <option value="adjustment" {{ old('type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                </select>
                @error('type')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" id="supplierGroup">
                <label for="supplier_id" class="form-label">Supplier</label>
                <select id="supplier_id" name="supplier_id" class="form-select">
                    <option value="">Select Supplier (Optional)</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
                @error('supplier_id')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="product_id" class="form-label">Product *</label>
                <select id="product_id" name="product_id" class="form-select" required>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-has-variants="{{ $product->variants->count() > 0 ? 'true' : 'false' }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} (Stock: {{ $product->stock_quantity }})
                        </option>
                    @endforeach
                </select>
                @error('product_id')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" id="variantGroup" style="display: none;">
                <label for="product_variant_id" class="form-label">Variant</label>
                <select id="product_variant_id" name="product_variant_id" class="form-select">
                    <option value="">Select Variant (Optional)</option>
                </select>
                @error('product_variant_id')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="quantity" class="form-label">Quantity *</label>
                <input type="number" id="quantity" name="quantity" class="form-input" value="{{ old('quantity') }}" min="1" required>
                @error('quantity')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="reference_number" class="form-label">Reference Number</label>
                <input type="text" id="reference_number" name="reference_number" class="form-input" value="{{ old('reference_number') }}" placeholder="e.g., PO-001">
                @error('reference_number')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="notes" class="form-label">Notes</label>
            <textarea id="notes" name="notes" class="form-textarea" rows="3" placeholder="Optional notes about this transaction">{{ old('notes') }}</textarea>
            @error('notes')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="form-actions">
        <a href="{{ route('inventory.index') }}" class="btn-secondary">
            <i data-feather="arrow-left"></i>
            Cancel
        </a>
        <button type="submit" class="btn-primary">
            <i data-feather="save"></i>
            Record Transaction
        </button>
    </div>
</form>

<style>
    .page-header {
        margin-bottom: 2rem;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .page-subtitle {
        color: #64748b;
        margin: 0.5rem 0 0 0;
    }

    .form-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(148, 163, 184, 0.2);
    }

    .form-section {
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-input, .form-select, .form-textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.2s;
        background: white;
    }

    .form-input:focus, .form-select:focus, .form-textarea:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-textarea {
        resize: vertical;
    }

    .error-message {
        color: #ef4444;
        font-size: 0.75rem;
        margin-top: 0.25rem;
        display: block;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        padding-top: 2rem;
        border-top: 1px solid #e5e7eb;
    }

    .btn-primary, .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }

    .btn-secondary {
        background: rgba(255, 255, 255, 0.95);
        color: #64748b;
        border: 1px solid rgba(148, 163, 184, 0.2);
    }

    .btn-secondary:hover {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        border-color: #3b82f6;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

@push('scripts')
<script>
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    // Product variant loading
    const productSelect = document.getElementById('product_id');
    const variantGroup = document.getElementById('variantGroup');
    const variantSelect = document.getElementById('product_variant_id');
    const typeSelect = document.getElementById('type');
    const supplierGroup = document.getElementById('supplierGroup');

    const productsData = @json($products);

    productSelect.addEventListener('change', function() {
        const productId = this.value;
        const product = productsData.find(p => p.id == productId);
        
        if (product && product.variants && product.variants.length > 0) {
            variantGroup.style.display = 'block';
            variantSelect.innerHTML = '<option value="">Select Variant (Optional)</option>';
            
            product.variants.forEach(variant => {
                const option = document.createElement('option');
                option.value = variant.id;
                option.textContent = `${variant.name} (Stock: ${variant.stock_quantity})`;
                variantSelect.appendChild(option);
            });
        } else {
            variantGroup.style.display = 'none';
            variantSelect.innerHTML = '<option value="">Select Variant (Optional)</option>';
        }
    });

    // Show/hide supplier based on transaction type
    typeSelect.addEventListener('change', function() {
        if (this.value === 'in') {
            supplierGroup.style.display = 'block';
        } else {
            supplierGroup.style.display = 'none';
            document.getElementById('supplier_id').value = '';
        }
    });

    // Initial state
    if (typeSelect.value !== 'in') {
        supplierGroup.style.display = 'none';
    }
</script>
@endpush
</div>
@endsection
