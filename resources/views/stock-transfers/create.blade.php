@extends('layouts.app')

@section('page-title', 'Create Stock Transfer')

@section('content')
<div class="container">
    <div class="page-header">
        <h2 class="page-title">New Stock Transfer</h2>
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

            @if(session('error'))
                <div class="alert alert-danger mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if(!$sourceWarehouseId)
            <form action="{{ route('stock-transfers.create') }}" method="GET">
                <div class="form-group mb-4">
                    <label for="source_warehouse_id" class="form-label h5">Step 1: Select Source Warehouse</label>
                    <select name="source_warehouse_id" id="source_warehouse_id" class="form-select @error('source_warehouse_id') is-invalid @enderror" onchange="this.form.submit()">
                        <option value="">-- Select Warehouse --</option>
                        @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">
                            {{ $warehouse->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </form>
            @else
            <form action="{{ route('stock-transfers.store') }}" method="POST">
                @csrf
                <input type="hidden" name="source_warehouse_id" value="{{ $sourceWarehouseId }}">
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Source Warehouse</label>
                            <div class="form-control bg-light">
                                {{ $warehouses->find($sourceWarehouseId)->name }}
                            </div>
                            <small><a href="{{ route('stock-transfers.create') }}">Change Warehouse</a></small>
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

                <div class="card border-light mb-3">
                    <div class="card-header">Select Items to Transfer</div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-hover mb-0">
                                <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                                    <tr>
                                        <th style="width: 50px;">Select</th>
                                        <th>Product</th>
                                        <th>SKU</th>
                                        <th>Available Qty</th>
                                        <th>Transfer Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($inventory as $index => $item)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="items[{{ $index }}][selected]" class="item-select" data-index="{{ $index }}">
                                            <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $item->product_id }}" disabled class="item-input-{{ $index }}">
                                        </td>
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ $item->product->sku }}</td>
                                        <td><span class="badge bg-info text-dark">{{ $item->quantity }}</span></td>
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
                                        <td colspan="5" class="text-center">No inventory available in this warehouse.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Create Transfer Request</button>
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
