@extends('layouts.app')

@section('title', 'Start Stock Opname')

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <a href="{{ route('home') }}">Home</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item">
        <a href="{{ route('stock-opnames.index') }}">Stock Opname</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">Start New</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">Start New Stock Opname</h1>
            <p class="page-subtitle">Initiate a stock taking session for a warehouse.</p>
        </div>
    </div>

    <form action="{{ route('stock-opnames.store') }}" method="POST" class="card">
        @csrf
        
        <div class="form-grid">
            <div class="form-section">
                <h3 class="section-title">
                    <i data-feather="info"></i>
                    Session Details
                </h3>

                <div class="alert alert-info">
                    <i data-feather="info"></i> Starting an Opname will take a snapshot of the current System Stock for all products in the selected warehouse.
                </div>

                <div class="form-group">
                    <label class="form-label">Select Warehouse</label>
                    <select name="warehouse_id" class="form-select @error('warehouse_id') is-invalid @enderror" required>
                        <option value="">-- Select Warehouse --</option>
                        @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}" {{ (isset($selectedWarehouseId) && $selectedWarehouseId == $warehouse->id) ? 'selected' : '' }}>
                            {{ $warehouse->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('warehouse_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Date</label>
                    <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ date('Y-m-d') }}" required>
                    @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="2"></textarea>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('stock-opnames.index') }}" class="btn btn-secondary">
                <i data-feather="x"></i> Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i data-feather="play"></i> Start Opname & Snapshot Stock
            </button>
        </div>
    </form>
</div>
@endsection
