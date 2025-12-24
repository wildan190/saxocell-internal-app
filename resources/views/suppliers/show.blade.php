@extends('layouts.app')

@section('title', 'Supplier Details')

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <a href="{{ route('home') }}">Home</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item">
        <a href="{{ route('suppliers.index') }}">Suppliers</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">{{ $supplier->name }}</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $supplier->name }}</h1>
        <p class="page-subtitle">Supplier Information</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn-primary">
            <i data-feather="edit"></i>
            Edit
        </a>
    </div>
</div>

<div class="details-grid">
    <div class="details-card">
        <h3 class="card-title">Contact Information</h3>
        <div class="details-list">
            <div class="detail-item">
                <span class="detail-label">Contact Person</span>
                <span class="detail-value">{{ $supplier->contact_person }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Phone</span>
                <span class="detail-value">{{ $supplier->phone }}</span>
            </div>
            @if($supplier->email)
            <div class="detail-item">
                <span class="detail-label">Email</span>
                <span class="detail-value">{{ $supplier->email }}</span>
            </div>
            @endif
            @if($supplier->address)
            <div class="detail-item">
                <span class="detail-label">Address</span>
                <span class="detail-value">{{ $supplier->address }}</span>
            </div>
            @endif
            @if($supplier->identity_card_number)
            <div class="detail-item">
                <span class="detail-label">ID Card Number</span>
                <span class="detail-value">{{ $supplier->identity_card_number }}</span>
            </div>
            @endif
        </div>
    </div>

    <div class="details-card">
        <h3 class="card-title">Transaction History</h3>
        @if($supplier->inventoryTransactions->count() > 0)
            <div class="transactions-list">
                @foreach($supplier->inventoryTransactions->take(10) as $transaction)
                    <div class="transaction-item">
                        <div class="transaction-info">
                            <strong>{{ $transaction->product->name }}</strong>
                            @if($transaction->productVariant)
                                <span class="variant-name">{{ $transaction->productVariant->name }}</span>
                            @endif
                        </div>
                        <div class="transaction-meta">
                            <span class="transaction-type {{ $transaction->type }}">
                                {{ strtoupper($transaction->type) }}
                            </span>
                            <span class="transaction-qty">{{ $transaction->quantity }} units</span>
                            <span class="transaction-date">{{ $transaction->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($supplier->inventoryTransactions->count() > 10)
                <a href="{{ route('inventory.index', ['supplier_id' => $supplier->id]) }}" class="view-all-link">
                    View All Transactions →
                </a>
            @endif
        @else
            <p class="empty-message">No transactions yet</p>
        @endif
    </div>
</div>

<div class="form-actions">
    <a href="{{ route('suppliers.index') }}" class="btn-secondary">
        <i data-feather="arrow-left"></i>
        Back to List
    </a>
    <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-danger" onclick="return confirm('Delete this supplier? This cannot be undone.')">
            <i data-feather="trash-2"></i>
            Delete Supplier
        </button>
    </form>
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

    .header-actions {
        display: flex;
        gap: 1rem;
    }

    .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .details-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(148, 163, 184, 0.2);
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0 0 1.5rem 0;
    }

    .details-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .detail-item {
        display: flex;
        justify-content: space-between;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .detail-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .detail-label {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
    }

    .detail-value {
        font-size: 0.875rem;
        color: #1e293b;
        font-weight: 500;
        text-align: right;
    }

    .transactions-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .transaction-item {
        padding: 1rem;
        background: #f8fafc;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    .transaction-info {
        margin-bottom: 0.5rem;
    }

    .transaction-info strong {
        display: block;
        color: #1e293b;
        font-size: 0.875rem;
    }

    .variant-name {
        display: block;
        color: #64748b;
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }

    .transaction-meta {
        display: flex;
        gap: 0.75rem;
        align-items: center;
        font-size: 0.75rem;
    }

    .transaction-type {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-weight: 500;
        text-transform: uppercase;
    }

    .transaction-type.in {
        background: #d1fae5;
        color: #065f46;
    }

    .transaction-type.out {
        background: #fee2e2;
        color: #991b1b;
    }

    .transaction-type.adjustment {
        background: #fef3c7;
        color: #92400e;
    }

    .transaction-qty {
        color: #64748b;
    }

    .transaction-date {
        color: #94a3b8;
        margin-left: auto;
    }

    .empty-message {
        color: #64748b;
        text-align: center;
        padding: 2rem;
    }

    .view-all-link {
        display: block;
        text-align: center;
        margin-top: 1rem;
        color: #3b82f6;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.875rem;
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
    }

    .btn-primary, .btn-secondary, .btn-danger {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }

    .btn-secondary {
        background: rgba(255, 255, 255, 0.95);
        color: #64748b;
        border: 1px solid rgba(148, 163, 184, 0.2);
    }

    .btn-secondary:hover {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        border-color: #3b82f6;
    }

    .btn-danger {
        background: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }

    @media (max-width: 1024px) {
        .details-grid {
            grid-template-columns: 1fr;
        }
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
