@extends('layouts.app')

@section('title', 'Create Warehouse')

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <a href="{{ route('home') }}">Home</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item">
        <a href="{{ route('warehouses.index') }}">Warehouses</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">Create</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">Add New Warehouse</h1>
            <p class="page-subtitle">Register a new physical storage location for your inventory.</p>
        </div>
    </div>

    <form action="{{ route('warehouses.store') }}" method="POST" class="card">
        @csrf
        
        <div class="form-grid">
            <div class="form-section">
                <h3 class="section-title">
                    <i data-feather="info"></i>
                    Warehouse Details
                </h3>

                <div class="form-group">
                    <label class="form-label">Warehouse Name <span class="text-danger">*</span></label>
                    <input type="text" 
                           name="name" 
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" 
                           placeholder="e.g. Central Distribution Center"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Location Address</label>
                    <textarea name="address" 
                              rows="3" 
                              class="form-control @error('address') is-invalid @enderror"
                              placeholder="Full physical address...">{{ old('address') }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Description <span class="text-muted font-normal">(Optional)</span></label>
                    <textarea name="description" 
                              rows="3" 
                              class="form-control"
                              placeholder="Notes about capacity, type of goods, etc.">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">
                <i data-feather="x"></i> Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i data-feather="check"></i> Create Warehouse
            </button>
        </div>
    </form>
</div>
@endsection

@endsection
