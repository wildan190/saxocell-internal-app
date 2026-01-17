@extends('layouts.app')

@section('page-title', 'Stock Opname Sessions')

@section('content')
<div class="container">
    <div class="page-header">
        <h2 class="page-title">Stock Opname History</h2>
        <a href="{{ route('stock-opnames.create') }}" class="btn btn-primary">
            <i data-feather="plus"></i> Start New Opname
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Warehouse</th>
                            <th>Status</th>
                            <th>Notes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($opnames as $opname)
                        <tr>
                            <td>{{ $opname->date->format('d M Y') }}</td>
                            <td>{{ $opname->warehouse->name }}</td>
                            <td>
                                @if($opname->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-warning text-dark">In Progress</span>
                                @endif
                            </td>
                            <td>{{ $opname->notes }}</td>
                            <td>
                                <a href="{{ route('stock-opnames.show', $opname->id) }}" class="btn btn-sm btn-info">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No opname sessions found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
