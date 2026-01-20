@extends('layouts.app')

@section('title', 'Edit Store: ' . $store->name)

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
    <div class="breadcrumb-item active">Edit</div>
</nav>
@endsection

@section('content')
@extends('layouts.app')

@section('title', 'Edit Store: ' . $store->name)

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
    <div class="breadcrumb-item active">Edit</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Store</h1>
            <p class="page-subtitle">Update details for <strong>{{ $store->name }}</strong>.</p>
        </div>
    </div>

    <form action="{{ route('stores.update', $store) }}" method="POST" class="card">
        @csrf
        @method('PUT')
        
        <div class="form-grid">
            <div class="form-section">
                <h3 class="section-title">
                    <i data-feather="edit"></i>
                    Edit Details
                </h3>

                <div class="form-group">
                    <label class="form-label">Store Name <span class="text-danger">*</span></label>
                    <input type="text" 
                           name="name" 
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $store->name) }}" 
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Location Address</label>
                    <textarea name="address" 
                              rows="3" 
                              class="form-control @error('address') is-invalid @enderror">{{ old('address', $store->address) }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Description <span class="text-muted font-normal">(Optional)</span></label>
                    <textarea name="description" 
                              rows="3" 
                              class="form-control">{{ old('description', $store->description) }}</textarea>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('stores.index') }}" class="btn btn-secondary">
                <i data-feather="x"></i> Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i data-feather="save"></i> Save Changes
            </button>
        </div>
    </form>
</div>
@endsection

@endsection
