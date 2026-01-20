@extends('layouts.app')

@section('title', 'Opname #' . substr($opname->id, 0, 8))

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <a href="{{ route('home') }}">Home</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item">
        <a href="{{ route('stock-opnames.index') }}">Stock Opname</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">#{{ substr($opname->id, 0, 8) }}</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">
                Opname #{{ substr($opname->id, 0, 8) }}
                <span class="text-muted fw-normal">({{ $opname->warehouse->name }})</span>
            </h1>
            <p class="page-subtitle">
                <i data-feather="calendar" style="width: 14px; height: 14px; vertical-align: text-top;"></i>
                {{ $opname->date->format('d M Y') }}
            </p>
        </div>
        <div class="action-bar-header">
            <a href="{{ route('stock-opnames.index') }}" class="btn btn-secondary">
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
            {{ session('error') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <label class="text-uppercase text-secondary font-bold text-xs">Status</label>
                    <div class="mt-1">
                        @if($opname->status == 'completed')
                            <span class="badge bg-success-subtle text-success">Completed</span>
                        @else
                            <span class="badge bg-warning-subtle text-warning">In Progress</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-9 border-start">
                    <label class="text-uppercase text-secondary font-bold text-xs">Notes</label>
                    <p class="mb-0 text-dark">{{ $opname->notes ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('stock-opnames.finalize', $opname->id) }}" method="POST" class="card">
        @csrf
        
        <div class="card-header border-bottom">
            <h3 class="card-title m-0">
                <i data-feather="clipboard"></i> Count Sheet
            </h3>
        </div>
        
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>SKU</th>
                        <th class="text-end">System Qty</th>
                        <th class="text-end" style="width: 150px;">Actual Qty</th>
                        @if($opname->status == 'completed')
                            <th class="text-end">Difference</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($opname->items as $item)
                    <tr>
                        <td><span class="fw-bold">{{ $item->product->name }}</span></td>
                        <td class="text-muted">{{ $item->product->sku }}</td>
                        <td class="text-end text-muted">{{ $item->system_qty }}</td>
                        <td class="text-end">
                            @if($opname->status == 'in_progress')
                                <input type="number" 
                                       name="items[{{ $item->id }}][actual_qty]" 
                                       class="form-control form-control-sm text-end fw-bold" 
                                       value="{{ old("items.{$item->id}.actual_qty", $item->actual_qty ?? $item->system_qty) }}" 
                                       required>
                            @else
                                <span class="fw-bold text-dark">{{ $item->actual_qty }}</span>
                            @endif
                        </td>
                        @if($opname->status == 'completed')
                            <td class="text-end">
                                @if($item->difference > 0)
                                    <span class="text-success fw-bold">+{{ $item->difference }}</span>
                                @elseif($item->difference < 0)
                                    <span class="text-danger fw-bold">{{ $item->difference }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($opname->status == 'in_progress')
        <div class="card-footer text-end bg-light">
            <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Are you sure you want to finalize this opname? This will update inventory levels.')">
                <i data-feather="check"></i> Finalize Opname & Update Inventory
            </button>
        </div>
        @endif
    </form>
</div>
@endsection
