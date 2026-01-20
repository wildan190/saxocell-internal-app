@extends('layouts.app')

@section('title', 'Store: ' . $store->name)

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
    <div class="breadcrumb-item active">{{ $store->name }}</div>
</nav>
@endsection

@section('content')
@extends('layouts.app')

@section('title', 'Store: ' . $store->name)

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
    <div class="breadcrumb-item active">{{ $store->name }}</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ $store->name }}</h1>
            <p class="page-subtitle">
                <i data-feather="map-pin" style="width: 14px; height: 14px; vertical-align: text-top;"></i>
                {{ $store->address ?: 'No address provided' }}
            </p>
        </div>
        
        <div class="action-bar-header">
            <a href="{{ route('stores.edit', $store) }}" class="btn btn-secondary">
                <i data-feather="edit-2"></i> Edit
            </a>
            <a href="{{ route('stores.index') }}" class="btn btn-secondary">
                <i data-feather="arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Details Card -->
        <div class="lg:col-span-1">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title mb-4">
                        <i data-feather="info"></i>
                        About Store
                    </h3>
                    
                    <div class="mb-3">
                        <label class="text-uppercase text-secondary font-bold text-xs">Description</label>
                        <p class="text-dark">
                            {{ $store->description ?: 'No description available for this store.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Card -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title m-0">
                        <i data-feather="shopping-bag"></i>
                        Current Inventory
                    </h3>
                </div>
                
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th class="text-center">Status</th>
                                <th class="text-end">Quantity</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($store->inventory as $item)
                            <tr>
                                <td>
                                    <span class="fw-bold {{ $item->is_active ? 'text-dark' : 'text-muted' }}">{{ $item->product->name }}</span>
                                </td>
                                <td class="text-muted">{{ $item->product->sku }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $item->is_active ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                        {{ $item->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <span class="fw-bold {{ $item->quantity > 0 ? 'text-dark' : 'text-muted' }}">{{ $item->quantity }}</span>
                                </td>
                                <td class="text-end">
                                    <form action="{{ route('stores.inventory.toggle-status', [$store->id, $item->id]) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-icon btn-sm {{ $item->is_active ? 'text-danger hover:bg-danger-subtle' : 'text-success hover:bg-success-subtle' }}" 
                                            title="{{ $item->is_active ? 'Mark as Inactive' : 'Mark as Active' }}">
                                            <i data-feather="{{ $item->is_active ? 'slash' : 'check-circle' }}"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="empty-state">
                                        <div class="empty-icon"><i data-feather="inbox"></i></div>
                                        <p>No inventory records found in this store.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@endsection
