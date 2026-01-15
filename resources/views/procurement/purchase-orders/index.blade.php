@extends('layouts.app')

@section('title', 'Purchase Orders')

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <a href="{{ route('home') }}">
            <i data-feather="home"></i>
            Home
        </a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">Purchase Orders</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">Purchase Orders</h1>
            <p class="page-subtitle">Manage procurement and supplier orders with precision</p>
        </div>
    </div>

    <div class="action-bar">
        <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary">
            <i data-feather="plus"></i>
            Create PO
        </a>
    </div>

    <!-- Reference-styled Stats Overview -->
    <div class="stats-grid">
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin: 0;">Total POs</p>
                    <h3 style="font-size: 2rem; font-weight: 700; margin: 0.5rem 0 0;">{{ $purchaseOrders->total() }}</h3>
                </div>
                <div style="background: #eff6ff; padding: 1rem; border-radius: 1rem; color: #3b82f6;">
                    <i data-feather="shopping-cart"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin: 0;">Draft / Pending</p>
                    <h3 style="font-size: 2rem; font-weight: 700; margin: 0.5rem 0 0;">
                        {{ \App\Models\PurchaseOrder::whereIn('status', ['draft', 'submitted'])->count() }}
                    </h3>
                </div>
                <div style="background: #fffbeb; padding: 1rem; border-radius: 1rem; color: #f59e0b;">
                    <i data-feather="clock"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin: 0;">Approved</p>
                    <h3 style="font-size: 2rem; font-weight: 700; margin: 0.5rem 0 0;">
                        {{ \App\Models\PurchaseOrder::where('status', 'approved')->count() }}
                    </h3>
                </div>
                <div style="background: #ecfdf5; padding: 1rem; border-radius: 1rem; color: #10b981;">
                    <i data-feather="check-circle"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin: 0;">Wait Receipt</p>
                    <h3 style="font-size: 2rem; font-weight: 700; margin: 0.5rem 0 0;">
                        {{ \App\Models\PurchaseOrder::where('status', 'partial')->count() }}
                    </h3>
                </div>
                <div style="background: #fef2f2; padding: 1rem; border-radius: 1rem; color: #ef4444;">
                    <i data-feather="truck"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Standardized Filter Section -->
    <div class="filter-section">
        <form action="{{ route('purchase-orders.index') }}" method="GET" class="filter-form">
            <div class="filter-group" style="flex: 1;">
                <select name="status" class="filter-select">
                    <option value="">All Statuses</option>
                    @foreach(['draft', 'submitted', 'approved', 'partial', 'completed', 'cancelled'] as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-filter-apply">Apply Filter</button>
                @if(request('status'))
                    <a href="{{ route('purchase-orders.index') }}" class="btn-filter-clear">
                        <i data-feather="x"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>PO Number</th>
                    <th>Supplier</th>
                    <th>Order Date</th>
                    <th class="text-center">Items</th>
                    <th>Total Amount</th>
                    <th class="text-center">Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchaseOrders as $po)
                <tr>
                    <td style="font-weight: 700; color: #1e293b;">{{ $po->po_number }}</td>
                    <td>
                        <div style="display: flex; flex-direction: column;">
                            <span style="font-weight: 600; color: #1e293b;">{{ $po->supplier->name }}</span>
                            <span style="font-size: 0.75rem; color: #64748b;">{{ $po->supplier->contact_person }}</span>
                        </div>
                    </td>
                    <td>{{ $po->order_date->format('M d, Y') }}</td>
                    <td class="text-center"><span class="badge" style="background: #f1f5f9; color: #475569;">{{ $po->items->count() }} items</span></td>
                    <td style="font-weight: 700; color: #1e293b;">Rp {{ number_format($po->total_amount, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @php
                            $statusMap = [
                                'draft' => ['bg' => '#f1f5f9', 'text' => '#475569'],
                                'submitted' => ['bg' => '#eff6ff', 'text' => '#3b82f6'],
                                'approved' => ['bg' => '#ecfdf5', 'text' => '#10b981'],
                                'partial' => ['bg' => '#fefce8', 'text' => '#ca8a04'],
                                'completed' => ['bg' => '#f0fdf4', 'text' => '#15803d'],
                                'cancelled' => ['bg' => '#fef2f2', 'text' => '#ef4444'],
                            ];
                            $colors = $statusMap[$po->status] ?? $statusMap['draft'];
                        @endphp
                        <span class="badge" style="background-color: {{ $colors['bg'] }}; color: {{ $colors['text'] }};">
                            {{ strtoupper($po->status) }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                            <a href="{{ route('purchase-orders.show', $po->id) }}" class="btn-action-secondary" title="View Detail">
                                <i data-feather="eye"></i>
                            </a>
                            @if($po->status == 'draft' || $po->status == 'submitted')
                            <form action="{{ route('purchase-orders.destroy', $po->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action-danger" onclick="return confirm('Delete this PO?');" title="Delete PO">
                                    <i data-feather="trash-2"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 5rem 0; text-align: center;">
                        <div class="empty-icon"><i data-feather="inbox"></i></div>
                        <h3>No purchase orders found.</h3>
                        <p>Start your procurement by creating your first purchase order.</p>
                        <a href="{{ route('purchase-orders.create') }}" class="btn-primary" style="margin-top: 1rem;">
                            <i data-feather="plus"></i> Start Procuring
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($purchaseOrders->hasPages())
        {{ $purchaseOrders->links() }}
    @endif
</div>
@endsection
