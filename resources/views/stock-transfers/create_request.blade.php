@extends('layouts.app')

@section('title', 'Request Stock')

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <a href="{{ route('home') }}">Home</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item">
        <a href="{{ route('stock-transfers.index') }}">Stock Transfers</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">Request Stock</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">Request Stock</h1>
            <p class="page-subtitle">Request inventory replenishment from a warehouse.</p>
        </div>
        <div class="action-bar-header">
            <a href="{{ route('stock-transfers.index') }}" class="btn btn-secondary">
                <i data-feather="arrow-left"></i> Back
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('stock-transfers.store-request') }}" method="POST">
                @csrf
                
                <div class="form-grid">
                    <div class="form-section">
                        <h3 class="section-title">
                            <i data-feather="info"></i>
                            Request Details
                        </h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Requesting Store (My Store)</label>
                                    <select name="destination_store_id" class="form-select" required>
                                        <option value="">-- Select Store --</option>
                                        @foreach($stores as $store)
                                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Source Warehouse</label>
                                    <select name="source_warehouse_id" class="form-select" required>
                                        <option value="">-- Select Warehouse --</option>
                                        @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="section-title">
                            <i data-feather="list"></i>
                            Items to Request
                        </h3>
                        
                        <div id="items-container">
                            <div class="item-row row mb-2">
                                <div class="col-md-8">
                                    <select name="items[0][product_id]" class="form-select product-select" required>
                                        <option value="">-- Select Product --</option>
                                        @foreach(\App\Models\Product::all() as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="items[0][quantity]" class="form-control" placeholder="Qty" min="1" required>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-outline-danger btn-icon remove-row" style="display:none;">
                                        <i data-feather="trash-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <button type="button" class="btn btn-sm btn-outline-primary" id="add-item-btn">
                                <i data-feather="plus"></i> Add Item
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-actions mt-4">
                    <a href="{{ route('stock-transfers.index') }}" class="btn btn-secondary">
                        <i data-feather="x"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i data-feather="check"></i> Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = 1;
    const container = document.getElementById('items-container');
    const addBtn = document.getElementById('add-item-btn');

    addBtn.addEventListener('click', function() {
        const firstRow = container.querySelector('.item-row');
        const newRow = firstRow.cloneNode(true);
        
        // Reset values
        newRow.querySelector('select').name = `items[${itemIndex}][product_id]`;
        newRow.querySelector('select').value = "";
        newRow.querySelector('input').name = `items[${itemIndex}][quantity]`;
        newRow.querySelector('input').value = "";
        
        // Show remove button
        newRow.querySelector('.remove-row').style.display = 'inline-flex';
        
        container.appendChild(newRow);
        itemIndex++;
        
        // Re-attach remove listeners and initialize feather icons for new row
        attachRemoveListeners();
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });

    function attachRemoveListeners() {
        document.querySelectorAll('.remove-row').forEach(btn => {
            btn.onclick = function() {
                this.closest('.item-row').remove();
            };
        });
    }
    
    attachRemoveListeners();
});
</script>
@endpush
@endsection
