@extends('layouts.app')

@section('title', 'Suppliers')

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <a href="{{ route('home') }}">Home</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">Suppliers</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
<div class="page-header">
    <div>
        <h1 class="page-title">Suppliers</h1>
        <p class="page-subtitle">Manage your suppliers and vendors</p>
    </div>
    <a href="{{ route('suppliers.create') }}" class="btn-primary">
        <i data-feather="plus"></i>
        Add Supplier
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="suppliers-grid">
        @forelse($suppliers as $supplier)
            <div class="supplier-card">
                <div class="supplier-header">
                    <div class="supplier-avatar">
                        {{ substr($supplier->name, 0, 2) }}
                    </div>
                    <div class="supplier-info">
                        <h3 class="supplier-name">{{ $supplier->name }}</h3>
                        <p class="supplier-contact">{{ $supplier->contact_person }}</p>
                    </div>
                </div>
                
                <div class="supplier-details">
                    <div class="detail-row">
                        <i data-feather="phone"></i>
                        <span>{{ $supplier->phone }}</span>
                    </div>
                    @if($supplier->email)
                    <div class="detail-row">
                        <i data-feather="mail"></i>
                        <span>{{ $supplier->email }}</span>
                    </div>
                    @endif
                    @if($supplier->identity_card_number)
                    <div class="detail-row">
                        <i data-feather="credit-card"></i>
                        <span>ID: {{ $supplier->identity_card_number }}</span>
                    </div>
                    @endif
                </div>

                <div class="supplier-actions">
                    <a href="{{ route('suppliers.show', $supplier) }}" class="btn-action-primary">
                        <i data-feather="eye"></i>
                        View
                    </a>
                    <a href="{{ route('suppliers.edit', $supplier) }}" class="btn-action-secondary">
                        <i data-feather="edit"></i>
                    </a>
                    <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action-danger" onclick="return confirm('Delete this supplier?')">
                            <i data-feather="trash-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i data-feather="users"></i>
                <p>No suppliers found. Add your first supplier to get started.</p>
            </div>
        @endforelse
    </div>

    @if($suppliers->hasPages())
        <div class="pagination-container">
            {{ $suppliers->links() }}
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
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }

    .alert {
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #10b981;
    }

    .card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(148, 163, 184, 0.2);
    }

    .suppliers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
    }

    .supplier-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.5rem;
        transition: all 0.2s;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .supplier-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .supplier-header {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .supplier-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.125rem;
        text-transform: uppercase;
    }

    .supplier-info {
        flex: 1;
    }

    .supplier-name {
        margin: 0;
        font-size: 1.125rem;
        font-weight: 600;
        color: #1e293b;
    }

    .supplier-contact {
        margin: 0.25rem 0 0 0;
        font-size: 0.875rem;
        color: #64748b;
    }

    .supplier-details {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .detail-row {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: #64748b;
    }

    .detail-row i {
        width: 16px;
        height: 16px;
    }

    .supplier-actions {
        display: flex;
        gap: 0.5rem;
        padding-top: 1rem;
        border-top: 1px solid #f1f5f9;
        margin-top: auto;
    }

    .btn-action-primary, .btn-action-secondary, .btn-action-danger {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-action-primary {
        flex: 1;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
    }

    .btn-action-secondary, .btn-action-danger {
        width: 36px;
        height: 36px;
        padding: 0;
        background: white;
        border: 1px solid #e2e8f0;
        color: #64748b;
    }

    .btn-action-danger:hover {
        background: #fee2e2;
        border-color: #ef4444;
        color: #ef4444;
    }

    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 4rem 2rem;
        color: #64748b;
    }

    .empty-state i {
        width: 64px;
        height: 64px;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .pagination-container {
        margin-top: 2rem;
        padding-top: 2rem;
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
