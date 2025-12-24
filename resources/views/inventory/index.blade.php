@extends('layouts.app')

@section('title', 'Inventory Transactions')

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <a href="{{ route('home') }}">Home</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">Inventory</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
<div class="page-header">
    <div>
        <h1 class="page-title">Inventory Transactions</h1>
        <p class="page-subtitle">Stock movement history</p>
    </div>
    <a href="{{ route('inventory.create') }}" class="btn-primary">
        <i data-feather="plus"></i>
        New Transaction
    </a>
</div>

<div class="filters-card">
    <form method="GET" action="{{ route('inventory.index') }}" class="filters-form">
        <div class="filter-group">
            <label class="filter-label">Type</label>
            <select name="type" class="filter-select">
                <option value="">All Types</option>
                <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Stock In</option>
                <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Stock Out</option>
                <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
            </select>
        </div>
        
        <div class="filter-group">
            <label class="filter-label">Product</label>
            <select name="product_id" class="filter-select">
                <option value="">All Products</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn-filter">
                <i data-feather="filter"></i>
                Filter
            </button>
            @if(request()->hasAny(['type', 'product_id']))
                <a href="{{ route('inventory.index') }}" class="btn-clear">
                    <i data-feather="x"></i>
                    Clear
                </a>
            @endif
        </div>
    </form>
</div>

<div class="transactions-card">
    <div class="transactions-table">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Product</th>
                    <th>Variant</th>
                    <th>Quantity</th>
                    <th>Supplier</th>
                    <th>Reference</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                        <td>
                            <span class="type-badge {{ $transaction->type }}">
                                {{ strtoupper($transaction->type) }}
                            </span>
                        </td>
                        <td>{{ $transaction->product->name }}</td>
                        <td>{{ $transaction->productVariant?->name ?? '-' }}</td>
                        <td>
                            <span class="quantity {{ $transaction->type }}">
                                {{ $transaction->type === 'out' ? '-' : '+' }}{{ $transaction->quantity }}
                            </span>
                        </td>
                        <td>{{ $transaction->supplier?->name ?? '-' }}</td>
                        <td>{{ $transaction->reference_number ?? '-' }}</td>
                        <td class="notes-cell">{{ \Str::limit($transaction->notes ?? '-', 30) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty-row">No transactions found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($transactions->hasPages())
        <div class="pagination-container">
            {{ $transactions->links() }}
        </div>
    @endif
</div>

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .page-subtitle {
        color: #64748b;
        margin: 0.5rem 0 0 0;
    }

    .filters-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(148, 163, 184, 0.2);
    }

    .filters-form {
        display: flex;
        gap: 1rem;
        align-items: flex-end;
    }

    .filter-group {
        flex: 1;
    }

    .filter-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .filter-select {
        width: 100%;
        padding: 0.625rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.875rem;
        background: white;
    }

    .filter-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-filter, .btn-clear {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-filter {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
    }

    .btn-clear {
        background: white;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }

    .transactions-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 16px;
        padding: 0;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(148, 163, 184, 0.2);
        overflow: hidden;
    }

    .transactions-table {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
    }

    th {
        padding: 1rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    td {
        padding: 1rem;
        font-size: 0.875rem;
        color: #1e293b;
        border-bottom: 1px solid #f1f5f9;
    }

    tr:last-child td {
        border-bottom: none;
    }

    .type-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
    }

    .type-badge.in {
        background: #d1fae5;
        color: #065f46;
    }

    .type-badge.out {
        background: #fee2e2;
        color: #991b1b;
    }

    .type-badge.adjustment {
        background: #fef3c7;
        color: #92400e;
    }

    .quantity {
        font-weight: 600;
    }

    .quantity.in, .quantity.adjustment {
        color: #059669;
    }

    .quantity.out {
        color: #dc2626;
    }

    .notes-cell {
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .empty-row {
        text-align: center;
        padding: 3rem;
        color: #64748b;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }

    .pagination-container {
        padding: 1.5rem;
        border-top: 1px solid #e2e8f0;
    }
</style>

@push('scripts')
<script>
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>
@endpush
</div>
@endsection
