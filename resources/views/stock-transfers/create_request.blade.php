@extends('layouts.app')

@section('page-title', 'Request Stock')

@section('content')
<div class="container">
    <div class="page-header">
        <h2 class="page-title">Request Stock from Warehouse</h2>
        <a href="{{ route('stock-transfers.index') }}" class="btn btn-secondary">
            <i data-feather="arrow-left"></i> Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('stock-transfers.store-request') }}" method="POST">
                @csrf
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="destination_store_id" class="form-label">Requesting Store (My Store)</label>
                            <select name="destination_store_id" id="destination_store_id" class="form-select" required>
                                <option value="">-- Select Store --</option>
                                @foreach($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="source_warehouse_id" class="form-label">Source Warehouse</label>
                            <select name="source_warehouse_id" id="source_warehouse_id" class="form-select" required>
                                <option value="">-- Select Warehouse --</option>
                                @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card border-light mb-3">
                    <div class="card-header">Items to Request</div>
                    <div class="card-body">
                        <!-- Dynamic Item Rows -->
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
                                    <button type="button" class="btn btn-danger btn-sm remove-row" style="display:none;">&times;</button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-item-btn">
                            <i data-feather="plus"></i> Add Item
                        </button>
                    </div>
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn btn-primary">Submit Request</button>
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
        newRow.querySelector('.remove-row').style.display = 'inline-block';
        
        container.appendChild(newRow);
        itemIndex++;
        
        // Re-attach remove listeners
        attachRemoveListeners();
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
