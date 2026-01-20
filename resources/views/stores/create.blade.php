@extends('layouts.app')

@section('title', 'Create Store')

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <a href="{{ route('home') }}">Home</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item">
        <a href="{{ route('stores.index') }}">Stores</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">Create</div>
</nav>
@endsection

@section('content')
@extends('layouts.app')

@section('title', 'Create Store')

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <a href="{{ route('home') }}">Home</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item">
        <a href="{{ route('stores.index') }}">Stores</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">Create</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">Add New Store</h1>
            <p class="page-subtitle">Register a new retail outlet location.</p>
        </div>
    </div>

    <form action="{{ route('stores.store') }}" method="POST" class="card">
        @csrf
        
        <div class="form-grid">
            <div class="form-section">
                <h3 class="section-title">
                    <i data-feather="info"></i>
                    Store Details
                </h3>

                <div class="form-group">
                    <label class="form-label">Store Name <span class="text-danger">*</span></label>
                    <input type="text" 
                           name="name" 
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" 
                           placeholder="e.g. Downtown Branch"
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
            <a href="{{ route('stores.index') }}" class="btn btn-secondary">
                <i data-feather="x"></i> Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i data-feather="check"></i> Create Store
            </button>
        </div>
    </form>
</div>
@endsection

@endsection
