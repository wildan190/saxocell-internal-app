@extends('layouts.app')

@section('page-title', 'Start Stock Opname')

@section('content')
<div class="container">
    <div class="page-header">
        <h2 class="page-title">Start New Stock Opname</h2>
        <a href="{{ route('stock-opnames.index') }}" class="btn btn-secondary">
            <i data-feather="arrow-left"></i> Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('stock-opnames.store') }}" method="POST">
                @csrf
                
                <div class="form-group mb-3">
                    <label for="warehouse_id">Select Warehouse</label>
                    <select name="warehouse_id" id="warehouse_id" class="form-select @error('warehouse_id') is-invalid @enderror" required>
                        <option value="">-- Select Warehouse --</option>
                        @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}" {{ (isset($selectedWarehouseId) && $selectedWarehouseId == $warehouse->id) ? 'selected' : '' }}>
                            {{ $warehouse->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('warehouse_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="date">Date</label>
                    <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ date('Y-m-d') }}" required>
                    @error('date')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" class="form-control" rows="2"></textarea>
                </div>

                <div class="alert alert-info">
                    <i data-feather="info"></i> Starting an Opname will take a snapshot of the current System Stock for all products in the selected warehouse.
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn btn-primary">Start Opname & Snapshot Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
