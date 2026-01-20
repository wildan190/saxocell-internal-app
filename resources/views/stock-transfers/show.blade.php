@extends('layouts.app')

@section('title', ($transfer->status == 'requested' ? 'Stock Request #' : 'Stock Transfer #') . $transfer->reference_number)

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <a href="{{ route('home') }}">Home</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item">
        <a href="{{ route('stock-transfers.index') }}">Stock Transfers</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">{{ $transfer->reference_number }}</div>
</nav>
@endsection

@section('content')
@extends('layouts.app')

@section('title', ($transfer->status == 'requested' ? 'Stock Request #' : 'Stock Transfer #') . $transfer->reference_number)

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <a href="{{ route('home') }}">Home</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item">
        <a href="{{ route('stock-transfers.index') }}">Stock Transfers</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">{{ $transfer->reference_number }}</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">
                {{ $transfer->status == 'requested' ? 'Stock Request' : 'Stock Transfer' }}
                <span class="text-muted fw-normal">#{{ $transfer->reference_number }}</span>
            </h1>
            <p class="page-subtitle">
                <i data-feather="calendar" style="width: 14px; height: 14px; vertical-align: text-top;"></i>
                Created on {{ $transfer->created_at->format('M d, Y') }}
            </p>
        </div>
        
        <div class="action-bar-header">
            @if($transfer->status == 'requested')
                <form action="{{ route('stock-transfers.approve', $transfer->id) }}" method="POST" onsubmit="return confirm('Approve this request? Stock will be deducted from Warehouse.');" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i data-feather="check-circle"></i> Approve Request
                    </button>
                </form>
                <form action="{{ route('stock-transfers.reject', $transfer->id) }}" method="POST" onsubmit="return confirm('Reject and cancel this request?');" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i data-feather="x-circle"></i> Reject
                    </button>
                </form>
            @endif

            <a href="{{ route('stock-transfers.index') }}" class="btn btn-secondary">
                <i data-feather="arrow-left"></i> Back
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i data-feather="check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <i data-feather="alert-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Main Detail Card -->
    <div class="card">
        <!-- Status & Route Header -->
        <div class="card-body border-bottom bg-light">
            <div class="d-flex flex-column flex-md-row gap-4 align-items-start">
                <div class="d-flex align-items-center justify-content-center bg-white rounded-3 border" style="width: 80px; height: 80px;">
                    <i data-feather="{{ $transfer->status == 'requested' ? 'download' : 'truck' }}" style="width: 40px; height: 40px; color: #6c757d;"></i>
                </div>

                <div class="flex-grow-1">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        @php
                            $badgeClass = match($transfer->status) {
                                'completed', 'received' => 'bg-success-subtle text-success',
                                'pending', 'requested' => 'bg-warning-subtle text-warning',
                                'cancelled', 'rejected' => 'bg-danger-subtle text-danger',
                                default => 'bg-secondary-subtle text-secondary'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }} text-uppercase">
                            {{ $transfer->status }}
                        </span>
                        <span class="badge bg-dark text-white text-uppercase">
                            INTERNAL MOVEMENT
                        </span>
                    </div>
                    
                    <div class="row g-4 mt-2">
                        <div class="col-md-5">
                            <span class="d-block text-uppercase text-muted fw-bold text-xs mb-1">From (Source)</span>
                            <div class="d-flex align-items-center gap-2">
                                <i data-feather="archive" class="text-muted"></i>
                                <span class="fw-bold text-dark fs-5">{{ $transfer->sourceWarehouse->name }}</span>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-center justify-content-center text-muted">
                            <i data-feather="arrow-right" style="width: 24px; height: 24px;"></i>
                        </div>
                        <div class="col-md-5">
                            <span class="d-block text-uppercase text-muted fw-bold text-xs mb-1">To (Destination)</span>
                            <div class="d-flex align-items-center gap-2">
                                <i data-feather="shopping-cart" class="text-muted"></i>
                                <span class="fw-bold text-dark fs-5">{{ $transfer->destinationStore->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('stock-transfers.receive', $transfer->id) }}" method="POST">
                @csrf
                
                <h3 class="card-title mb-4">
                    <i data-feather="list"></i>
                    Transfer Items
                </h3>
                
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th class="text-end">
                                    {{ $transfer->status == 'requested' ? 'Qty Requested' : 'Qty Sent' }}
                                </th>
                                <th class="text-end" style="width: 200px;">
                                    {{ $transfer->status == 'requested' ? 'Pending Action' : 'Qty Received' }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transfer->items as $item)
                            <tr>
                                <td>
                                    <span class="fw-bold text-dark">{{ $item->product->name }}</span>
                                </td>
                                <td class="text-muted">{{ $item->product->sku }}</td>
                                <td class="text-end fw-bold">{{ $item->quantity_sent }}</td>
                                <td class="text-end">
                                    @if($transfer->status == 'pending')
                                        <input type="number" 
                                               name="items[{{ $item->id }}]" 
                                               class="form-control text-end fs-6 fw-bold" 
                                               value="{{ old("items.{$item->id}", $item->quantity_sent) }}" 
                                               min="0"
                                               max="{{ $item->quantity_sent }}"
                                               required>
                                    @else
                                        <span class="fw-bold {{ $item->quantity_received < $item->quantity_sent ? 'text-warning' : 'text-success' }}">
                                            {{ $item->quantity_received }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($transfer->status == 'pending')
                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i data-feather="check-square"></i> Confirm Receipt
                    </button>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection

@endsection
