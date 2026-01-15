@extends('layouts.app')

@section('title', 'Invoices')

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <a href="{{ route('home') }}">
            <i data-feather="home"></i>
            Home
        </a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">Invoices</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">Invoices</h1>
            <p class="page-subtitle">Standardize accounts payable with automated verification</p>
        </div>
    </div>

    <div class="action-bar">
        <a href="{{ route('invoices.create') }}" class="btn btn-primary">
            <i data-feather="plus-circle"></i>
            Record Invoice
        </a>
    </div>

    <!-- Reference-styled Filter Section -->
    <div class="filter-section">
        <form action="{{ route('invoices.index') }}" method="GET" class="filter-form">
            <div class="filter-group" style="flex: 1;">
                <select name="status" class="filter-select">
                    <option value="">All Payment Statuses</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-filter-apply">Filter Invoices</button>
                @if(request('status'))
                    <a href="{{ route('invoices.index') }}" class="btn-filter-clear">
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
                    <th>Invoice Number</th>
                    <th>Linked Purchase Order</th>
                    <th>Supplier</th>
                    <th>Billing Date</th>
                    <th>Gross Amount</th>
                    <th class="text-center">3-Way Match</th>
                    <th class="text-center">Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                <tr>
                    <td style="font-weight: 700; color: #1e293b;">{{ $invoice->invoice_number }}</td>
                    <td>
                        <a href="{{ route('purchase-orders.show', $invoice->purchase_order_id) }}" style="color: #3b82f6; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 0.5rem;">
                            <i data-feather="file-text" style="width: 14px;"></i>
                            {{ $invoice->purchaseOrder->po_number }}
                        </a>
                    </td>
                    <td>
                        <span style="font-weight: 600; color: #1e293b;">{{ $invoice->supplier->name }}</span>
                    </td>
                    <td>{{ $invoice->invoice_date->format('M d, Y') }}</td>
                    <td style="font-weight: 700; color: #1e293b;">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @php
                            $matchStatus = $invoice->threeWayMatch?->status ?? 'pending';
                            $matchMap = [
                                'matched' => ['bg' => '#ecfdf5', 'text' => '#059669', 'icon' => 'check-circle'],
                                'discrepancy' => ['bg' => '#fef2f2', 'text' => '#dc2626', 'icon' => 'alert-triangle'],
                                'pending' => ['bg' => '#eff6ff', 'text' => '#2563eb', 'icon' => 'clock'],
                            ];
                            $mColors = $matchMap[$matchStatus] ?? $matchMap['pending'];
                        @endphp
                        <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: {{ $mColors['bg'] }}; color: {{ $mColors['text'] }}; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.7rem; font-weight: 700; white-space: nowrap;">
                            <i data-feather="{{ $mColors['icon'] }}" style="width: 12px; height: 12px;"></i>
                            {{ strtoupper($matchStatus) }}
                        </div>
                    </td>
                    <td class="text-center">
                        @php
                            $payMap = [
                                'paid' => ['bg' => '#ecfdf5', 'text' => '#059669'],
                                'unpaid' => ['bg' => '#f1f5f9', 'text' => '#475569'],
                                'partial' => ['bg' => '#fffbeb', 'text' => '#d97706'],
                            ];
                            $pColors = $payMap[$invoice->payment_status] ?? $payMap['unpaid'];
                        @endphp
                        <span class="badge" style="background-color: {{ $pColors['bg'] }}; color: {{ $pColors['text'] }};">
                            {{ strtoupper($invoice->payment_status) }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('invoices.show', $invoice->id) }}" class="btn-action-secondary" title="View Audit">
                            <i data-feather="eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="padding: 5rem 0; text-align: center;">
                        <div class="empty-icon"><i data-feather="file-text"></i></div>
                        <h3>No invoices recorded yet.</h3>
                        <p>Track your supplier billings by recording your first invoice.</p>
                        <a href="{{ route('invoices.create') }}" class="btn-primary" style="margin-top: 1rem;">
                            <i data-feather="plus"></i> Record First Invoice
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($invoices->hasPages())
        {{ $invoices->links() }}
    @endif
</div>
@endsection
