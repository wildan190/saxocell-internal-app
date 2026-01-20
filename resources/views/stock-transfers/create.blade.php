@extends('layouts.app')

@section('title', 'Create Stock Transfer')

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
    <div class="breadcrumb-item active">New Transfer</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">New Stock Transfer</h1>
            <p class="page-subtitle">Transfer inventory from warehouse to store.</p>
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

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if(!$sourceWarehouseId)
            <form action="{{ route('stock-transfers.create') }}" method="GET">
                <div class="form-grid">
                    <div class="form-section">
                        <h3 class="section-title">
                            <i data-feather="map-pin"></i>
                            Select Function
                        </h3>
                        <div class="form-group">
                            <label for="source_warehouse_id" class="form-label">Step 1: Select Source Warehouse</label>
                            <select name="source_warehouse_id" id="source_warehouse_id" class="form-select" onchange="this.form.submit()">
                                <option value="">-- Select Warehouse --</option>
                                @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">
                                    {{ $warehouse->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </form>
            @else
            <form action="{{ route('stock-transfers.store') }}" method="POST">
                @csrf
                <input type="hidden" name="source_warehouse_id" value="{{ $sourceWarehouseId }}">
                
                <div class="form-grid">
                    <div class="form-section">
                        <h3 class="section-title">
                            <i data-feather="info"></i>
                            Transfer Details
                        </h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Source Warehouse</label>
                                    <div class="form-control-plaintext fw-bold">
                                        {{ $warehouses->find($sourceWarehouseId)->name }}
                                        <small class="ms-2"><a href="{{ route('stock-transfers.create') }}" class="text-decoration-none">(Change)</a></small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="destination_store_id" class="form-label">Destination Store</label>
                                    <select name="destination_store_id" id="destination_store_id" class="form-select" required>
                                        <option value="">-- Select Destination Store --</option>
                                        @foreach($stores as $store)
                                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="section-title">
                            <i data-feather="list"></i>
                            Select Items
                        </h3>
                        
                        <div class="table-container" style="max-height: 500px; overflow-y: auto;">
                            <table class="table">
                                <thead class="sticky-top bg-white">
                                    <tr>
                                        <th style="width: 50px;">Select</th>
                                        <th>Product</th>
                                        <th>SKU</th>
                                        <th>Available</th>
                                        <th style="width: 150px;">Transfer Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($inventory as $index => $item)
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input type="checkbox" name="items[{{ $index }}][selected]" class="form-check-input item-select" data-index="{{ $index }}">
                                                <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $item->product_id }}" disabled class="item-input-{{ $index }}">
                                            </div>
                                        </td>
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ $item->product->sku }}</td>
                                        <td><span class="badge bg-info-subtle text-info">{{ $item->quantity }}</span></td>
                                        <td>
                                            <input type="number" 
                                                   name="items[{{ $index }}][quantity]" 
                                                   class="form-control form-control-sm item-qty item-input-{{ $index }}" 
                                                   min="1" 
                                                   max="{{ $item->quantity }}" 
                                                   required
                                                   disabled
                                                   placeholder="Qty">
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">No inventory available in this warehouse.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="form-actions mt-4">
                    <a href="{{ route('stock-transfers.index') }}" class="btn btn-secondary">
                        <i data-feather="x"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                        <i data-feather="check"></i> Create Transfer
                    </button>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.item-select');
    const submitBtn = document.getElementById('submitBtn');

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            const index = this.dataset.index;
            const inputs = document.querySelectorAll('.item-input-' + index);
            
            inputs.forEach(input => {
                input.disabled = !this.checked;
                if (!this.checked && input.classList.contains('item-qty')) {
                    input.value = '';
                }
            });
            
            checkSubmit();
        });
    });

    function checkSubmit() {
        if (!submitBtn) return;
        const checked = document.querySelectorAll('.item-select:checked').length;
        submitBtn.disabled = checked === 0;
    }
});
</script>
@endpush
@endsection
