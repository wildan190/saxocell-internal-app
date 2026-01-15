@extends('layouts.app')

@section('title', 'Delivery Orders (Goods Receipt)')

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <a href="{{ route('home') }}">
            <i data-feather="home"></i>
            Home
        </a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">Delivery Orders</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">Delivery Orders</h1>
            <p class="page-subtitle">Track receipts and incoming logistics from suppliers</p>
        </div>
    </div>

    <div class="action-bar">
        <a href="{{ route('delivery-orders.create') }}" class="btn btn-primary">
            <i data-feather="plus"></i>
            Receive Goods
        </a>
    </div>

    <!-- Reference-styled Filter Section -->
    <div class="filter-section">
        <form action="{{ route('delivery-orders.index') }}" method="GET" class="filter-form">
            <div class="filter-group" style="flex: 1;">
                <select name="status" class="filter-select">
                    <option value="">All Receipt Statuses</option>
                    @foreach(['pending', 'partial', 'completed', 'rejected'] as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-filter-apply">Filter Receipts</button>
                @if(request('status'))
                    <a href="{{ route('delivery-orders.index') }}" class="btn-filter-clear">
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
                    <th>DO Number</th>
                    <th>PO Reference</th>
                    <th>Supplier</th>
                    <th>Delivery Date</th>
                    <th>Received By</th>
                    <th class="text-center">Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deliveryOrders as $do)
                <tr>
                    <td style="font-weight: 700; color: #1e293b;">{{ $do->do_number }}</td>
                    <td>
                        <a href="{{ route('purchase-orders.show', $do->purchase_order_id) }}" style="color: #3b82f6; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 0.5rem;">
                            <i data-feather="file-text" style="width: 14px;"></i>
                            {{ $do->purchaseOrder->po_number }}
                        </a>
                    </td>
                    <td>
                        <span style="font-weight: 600; color: #1e293b;">{{ $do->supplier->name }}</span>
                    </td>
                    <td>{{ $do->delivery_date->format('M d, Y') }}</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div style="width: 24px; height: 24px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.6rem; font-weight: 700; color: #64748b;">
                                {{ strtoupper(substr($do->receiver->name, 0, 1)) }}
                            </div>
                            <span style="font-size: 0.875rem;">{{ $do->receiver->name }}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        @php
                            $statusMap = [
                                'pending' => ['bg' => '#eff6ff', 'text' => '#3b82f6'],
                                'partial' => ['bg' => '#fefce8', 'text' => '#ca8a04'],
                                'completed' => ['bg' => '#ecfdf5', 'text' => '#10b981'],
                                'rejected' => ['bg' => '#fef2f2', 'text' => '#ef4444'],
                            ];
                            $colors = $statusMap[$do->status] ?? ['bg' => '#f1f5f9', 'text' => '#475569'];
                        @endphp
                        <span class="badge" style="background-color: {{ $colors['bg'] }}; color: {{ $colors['text'] }};">
                            {{ strtoupper($do->status) }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('delivery-orders.show', $do->id) }}" class="btn-action-secondary" title="View Transaction">
                            <i data-feather="eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 5rem 0; text-align: center;">
                        <div class="empty-icon"><i data-feather="truck"></i></div>
                        <h3>No receipt transactions recorded yet.</h3>
                        <p>Start tracking your inbound logistics by receiving your first delivery.</p>
                        <a href="{{ route('delivery-orders.create') }}" class="btn-primary" style="margin-top: 1rem;">
                            <i data-feather="plus"></i> Receive First Delivery
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($deliveryOrders->hasPages())
    <div style="display: flex; justify-content: center; margin-top: 2rem;">
        {{ $deliveryOrders->links() }}
    </div>
    @endif
</div>
@endsection
