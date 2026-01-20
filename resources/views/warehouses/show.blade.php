@extends('layouts.app')

@section('title', 'Warehouse: ' . $warehouse->name)

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
    <div class="breadcrumb-item active">{{ $warehouse->name }}</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ $warehouse->name }}</h1>
            <p class="page-subtitle">
                <i data-feather="map-pin" style="width: 14px; height: 14px; vertical-align: text-top;"></i>
                {{ $warehouse->address ?: 'No address provided' }}
            </p>
        </div>
        
        <div class="action-bar-header">
            <a href="{{ route('warehouses.edit', $warehouse) }}" class="btn btn-secondary">
                <i data-feather="edit-2"></i> Edit
            </a>
            <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">
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
                        About Warehouse
                    </h3>
                    
                    <div class="mb-3">
                        <label class="text-uppercase text-secondary font-bold text-xs">Description</label>
                        <p class="text-dark">
                            {{ $warehouse->description ?: 'No description available for this warehouse.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Card -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title m-0">
                        <i data-feather="box"></i>
                        Current Inventory
                    </h3>
                    <a href="{{ route('stock-opnames.create') }}?warehouse_id={{ $warehouse->id }}" class="btn btn-sm btn-outline-primary">
                        <i data-feather="check-circle"></i> Stock Opname
                    </a>
                </div>
                
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th class="text-end">Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($warehouse->inventory as $item)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ $item->product->name }}</span>
                                </td>
                                <td class="text-muted">{{ $item->product->sku }}</td>
                                <td class="text-end">
                                    <span class="fw-bold {{ $item->quantity > 0 ? 'text-dark' : 'text-muted' }}">{{ $item->quantity }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-5">
                                    <div class="empty-state">
                                        <div class="empty-icon"><i data-feather="inbox"></i></div>
                                        <p>No inventory records found in this warehouse.</p>
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