@extends('layouts.app')

@section('page-title', 'Stock Opname Details')

@section('content')
<div class="container">
    <div class="page-header d-flex justify-content-between align-items-center">
        <h2 class="page-title">
            Opname #{{ substr($opname->id, 0, 8) }} 
            <small class="text-muted">({{ $opname->warehouse->name }})</small>
        </h2>
        <div>
            <a href="{{ route('stock-opnames.index') }}" class="btn btn-secondary">
                <i data-feather="arrow-left"></i> Back
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
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
            <div class="row">
                <div class="col-md-3">
                    <strong>Date:</strong> {{ $opname->date->format('d M Y') }}
                </div>
                <div class="col-md-3">
                    <strong>Status:</strong> 
                    @if($opname->status == 'completed')
                        <span class="badge bg-success">Completed</span>
                    @else
                        <span class="badge bg-warning text-dark">In Progress</span>
                    @endif
                </div>
                <div class="col-md-6">
                    <strong>Notes:</strong> {{ $opname->notes ?? '-' }}
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('stock-opnames.finalize', $opname->id) }}" method="POST">
        @csrf
        
        <div class="card">
            <div class="card-header">Count Sheet</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
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
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->product->sku }}</td>
                                <td class="text-end">{{ $item->system_qty }}</td>
                                <td class="text-end">
                                    @if($opname->status == 'in_progress')
                                        <input type="number" 
                                               name="items[{{ $item->id }}][actual_qty]" 
                                               class="form-control form-control-sm text-end" 
                                               value="{{ old("items.{$item->id}.actual_qty", $item->actual_qty ?? $item->system_qty) }}" 
                                               required>
                                    @else
                                        <strong>{{ $item->actual_qty }}</strong>
                                    @endif
                                </td>
                                @if($opname->status == 'completed')
                                    <td class="text-end">
                                        @if($item->difference > 0)
                                            <span class="text-success">+{{ $item->difference }}</span>
                                        @elseif($item->difference < 0)
                                            <span class="text-danger">{{ $item->difference }}</span>
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
            </div>
            
            @if($opname->status == 'in_progress')
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to finalize this opname? This will update inventory levels.')">
                    <i data-feather="check"></i> Finalize Opname & Update Inventory
                </button>
            </div>
            @endif
        </div>
    </form>
</div>
@endsection
