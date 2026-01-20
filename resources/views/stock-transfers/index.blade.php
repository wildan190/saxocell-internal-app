@extends('layouts.app')

@section('title', 'Stock Transfers')

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <a href="{{ route('home') }}">Home</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">Stock Transfers</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">Stock Transfers</h1>
            <p class="page-subtitle">History of inventory movements and requests.</p>
        </div>
        <div class="action-bar-header">
            <a href="{{ route('stock-transfers.create-request') }}" class="btn btn-secondary">
                <i data-feather="download"></i> Request Stock
            </a>
            <a href="{{ route('stock-transfers.create') }}" class="btn btn-primary">
                <i data-feather="plus"></i> New Transfer
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i data-feather="check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Ref Number</th>
                        <th>Date</th>
                        <th>Source</th>
                        <th>Destination</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transfers as $transfer)
                    <tr>
                        <td class="text-monospace text-muted">{{ $transfer->reference_number }}</td>
                        <td>{{ $transfer->created_at->format('d M Y') }}</td>
                        <td>{{ $transfer->sourceWarehouse->name }}</td>
                        <td>{{ $transfer->destinationStore->name }}</td>
                        <td>
                            @php
                                $badgeClass = match($transfer->status) {
                                    'completed', 'received' => 'bg-success-subtle text-success',
                                    'pending', 'requested' => 'bg-warning-subtle text-warning',
                                    'cancelled', 'rejected' => 'bg-danger-subtle text-danger',
                                    default => 'bg-secondary-subtle text-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                {{ ucfirst($transfer->status) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('stock-transfers.show', $transfer->id) }}" class="btn btn-icon btn-sm text-primary hover:bg-primary-subtle">
                                <i data-feather="eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="empty-state">
                                <div class="empty-icon"><i data-feather="truck"></i></div>
                                <p>No stock transfers found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
