@extends('layouts.app')

@section('title', 'Suppliers')

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <a href="{{ route('home') }}">
            <i data-feather="home"></i>
            Home
        </a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">Supplier Directory</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">Vendor Relations</h1>
            <p class="page-subtitle">Centralized database of certified suppliers and trade partners</p>
        </div>
    </div>

    <div class="action-bar">
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
            <i data-feather="user-plus"></i>
            Onboard Supplier
        </a>
    </div>

    <!-- Reference-styled Filter Section -->
    <div class="filter-section">
        <form action="{{ route('suppliers.index') }}" method="GET" class="filter-form">
            <div class="filter-group search-group">
                <i data-feather="search" class="search-icon"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or contact person..." class="filter-input">
            </div>
            
            <div class="filter-actions">
                <button type="submit" class="btn-filter-apply">Search</button>
                @if(request()->filled('search'))
                    <a href="{{ route('suppliers.index') }}" class="btn-filter-clear">
                        <i data-feather="x"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    @if(session('success'))
        <div style="background: #ecfdf5; border: 1px solid #10b981; color: #065f46; padding: 1rem 1.5rem; border-radius: 1rem; margin-bottom: 2rem; font-weight: 600; display: flex; align-items: center; gap: 0.75rem;">
            <i data-feather="check-circle" style="width: 18px;"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="items-grid">
        @forelse($suppliers as $supplier)
            <div class="item-card">
                <div class="item-info">
                    <div style="display: flex; align-items: center; gap: 1.25rem; margin-bottom: 1.5rem;">
                        <div class="supplier-avatar-premium">
                            {{ strtoupper(substr($supplier->name, 0, 2)) }}
                        </div>
                        <div>
                            <h3 style="margin: 0; font-size: 1.125rem; font-weight: 700; color: #1e293b;">{{ $supplier->name }}</h3>
                            <p style="margin: 0.25rem 0 0; font-size: 0.8rem; color: #64748b; font-weight: 500;">{{ strtoupper($supplier->contact_person) }}</p>
                        </div>
                    </div>
                    
                    <div style="display: flex; flex-direction: column; gap: 1rem; padding: 1.25rem; background: #f8fafc; border-radius: 1.25rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; font-size: 0.875rem; color: #475569;">
                            <i data-feather="phone" style="width: 16px; color: #3b82f6;"></i>
                            <span style="font-weight: 500;">{{ $supplier->phone }}</span>
                        </div>
                        @if($supplier->email)
                        <div style="display: flex; align-items: center; gap: 0.75rem; font-size: 0.875rem; color: #475569;">
                            <i data-feather="mail" style="width: 16px; color: #3b82f6;"></i>
                            <span style="font-weight: 500;">{{ $supplier->email }}</span>
                        </div>
                        @endif
                        @if($supplier->identity_card_number)
                        <div style="display: flex; align-items: center; gap: 0.75rem; font-size: 0.875rem; color: #475569;">
                            <i data-feather="shield" style="width: 16px; color: #3b82f6;"></i>
                            <span style="font-size: 0.75rem;">ID: {{ $supplier->identity_card_number }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="item-actions">
                    <a href="{{ route('suppliers.show', $supplier) }}" class="btn-action-primary">
                        <i data-feather="eye"></i> View Profile
                    </a>
                    <a href="{{ route('suppliers.edit', $supplier) }}" class="btn-action-secondary" title="Edit">
                        <i data-feather="edit-2"></i>
                    </a>
                    <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action-danger" onclick="return confirm('Archive this partner?')">
                            <i data-feather="trash-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty-state" style="grid-column: 1 / -1;">
                <div class="empty-icon"><i data-feather="users"></i></div>
                <h3>No trade partners registered yet.</h3>
                <p>Get started by onboarding your first supplier to the network.</p>
                <a href="{{ route('suppliers.create') }}" class="btn-primary" style="margin-top: 1rem;">
                    <i data-feather="plus"></i> Onboard First Supplier
                </a>
            </div>
        @endforelse
    </div>

    @if($suppliers->hasPages())
        <div style="display: flex; justify-content: center; margin-top: 3rem;">
            {{ $suppliers->links() }}
        </div>
    @endif
</div>
@endsection