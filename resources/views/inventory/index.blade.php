@extends('layouts.app')

@section('title', 'Inventory Transactions')

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <a href="{{ route('home') }}">
            <i data-feather="home"></i>
            Home
        </a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">Inventory History</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">Stock Ledger</h1>
            <p class="page-subtitle">Real-time audit log of all inventory movements</p>
        </div>
    </div>

    <div class="action-bar">
        <a href="{{ route('inventory.create') }}" class="btn btn-primary">
            <i data-feather="plus"></i>
            Log Movement
        </a>
    </div>

    <!-- Reference-styled Stats Row -->
    <div class="stats-grid">
        <div class="stat-card" style="flex-direction: row; align-items: center; gap: 1.25rem;">
            <div style="background: #ecfdf5; color: #10b981; padding: 1rem; border-radius: 1.25rem;">
                <i data-feather="arrow-up-right"></i>
            </div>
            <div>
                <p style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin: 0;">Stock In (30d)</p>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin: 0.25rem 0 0;">{{ $transactions->where('type', 'in')->count() }} entries</h3>
            </div>
        </div>
        <div class="stat-card" style="flex-direction: row; align-items: center; gap: 1.25rem;">
            <div style="background: #fff1f2; color: #f43f5e; padding: 1rem; border-radius: 1.25rem;">
                <i data-feather="arrow-down-left"></i>
            </div>
            <div>
                <p style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin: 0;">Stock Out (30d)</p>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin: 0.25rem 0 0;">{{ $transactions->where('type', 'out')->count() }} entries</h3>
            </div>
        </div>
        <div class="stat-card" style="flex-direction: row; align-items: center; gap: 1.25rem;">
            <div style="background: #eff6ff; color: #3b82f6; padding: 1rem; border-radius: 1.25rem;">
                <i data-feather="layers"></i>
            </div>
            <div>
                <p style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin: 0;">Active Items</p>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin: 0.25rem 0 0;">{{ $products->count() }}</h3>
            </div>
        </div>
    </div>

    <!-- Standardized Filter Section -->
    <div class="filter-section">
        <form method="GET" action="{{ route('inventory.index') }}" class="filter-form">
            <div class="filter-group">
                <select name="type" class="filter-select">
                    <option value="">All Movements</option>
                    <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Stock Inbound</option>
                    <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Stock Outbound</option>
                    <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Physical Adjustment</option>
                </select>
            </div>
            
            <div class="filter-group" style="flex: 1;">
                <select name="product_id" class="filter-select">
                    <option value="">Global Catalog</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-filter-apply">Apply Filters</button>
                @if(request()->hasAny(['type', 'product_id']))
                    <a href="{{ route('inventory.index') }}" class="btn-filter-clear">
                        <i data-feather="x"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Container -->
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th class="text-center">Type</th>
                    <th>Product & Variant</th>
                    <th class="text-center">Qty Delta</th>
                    <th>Supplier / Ref</th>
                    <th>Logistics Notes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                <tr>
                    <td style="font-size: 0.8rem; color: #64748b; font-weight: 500;">
                        {{ $transaction->created_at->format('M d, Y') }}<br>
                        <span style="opacity: 0.6;">{{ $transaction->created_at->format('H:i') }}</span>
                    </td>
                    <td class="text-center">
                        @php
                            $typeMap = [
                                'in' => ['bg' => '#ecfdf5', 'text' => '#059669', 'label' => 'INBOUND'],
                                'out' => ['bg' => '#fff1f2', 'text' => '#e11d48', 'label' => 'OUTBOUND'],
                                'adjustment' => ['bg' => '#fffbeb', 'text' => '#d97706', 'label' => 'ADJUST'],
                            ];
                            $t = $typeMap[$transaction->type] ?? ['bg' => '#f1f5f9', 'text' => '#475569', 'label' => 'OTHER'];
                        @endphp
                        <span class="badge" style="background: {{ $t['bg'] }}; color: {{ $t['text'] }};">
                            {{ $t['label'] }}
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; flex-direction: column;">
                            <span style="font-weight: 700; color: #1e293b;">{{ $transaction->product->name }}</span>
                            @if($transaction->productVariant)
                            <span style="font-size: 0.7rem; color: #64748b; margin-top: 0.25rem;">Variant: {{ $transaction->productVariant->name }}</span>
                            @else
                            <span style="font-size: 0.7rem; color: #94a3b8; font-style: italic; margin-top: 0.25rem;">Standard Base</span>
                            @endif
                        </div>
                    </td>
                    <td style="text-align: center;">
                        <span style="font-size: 1rem; font-weight: 700; {{ $transaction->type === 'out' ? 'color: #e11d48;' : 'color: #059669;' }}">
                            {{ $transaction->type === 'out' ? '−' : '+' }}{{ $transaction->quantity }}
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; flex-direction: column;">
                            @if($transaction->supplier)
                                <span style="font-weight: 600; color: #1e293b; font-size: 0.875rem;">{{ $transaction->supplier->name }}</span>
                            @endif
                            <span style="font-size: 0.75rem; color: #94a3b8;">{{ $transaction->reference_number ?: 'Internal Move' }}</span>
                        </div>
                    </td>
                    <td>
                        <p style="font-size: 0.8rem; color: #64748b; line-height: 1.4; max-width: 250px; margin: 0;">
                            {{ \Str::limit($transaction->notes ?? '-', 60) }}
                        </p>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 5rem 0; text-align: center;">
                        <div class="empty-icon"><i data-feather="package"></i></div>
                        <h3>No stock history found.</h3>
                        <p>Get started by logging your first inventory movement.</p>
                        <a href="{{ route('inventory.create') }}" class="btn-primary" style="margin-top: 1rem;">
                            <i data-feather="plus"></i> Log First Transaction
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($transactions->hasPages())
        {{ $transactions->links() }}
    @endif
</div>
@endsection
