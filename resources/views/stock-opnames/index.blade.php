@extends('layouts.app')

@section('title', 'Stock Opname Sessions')

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <a href="{{ route('home') }}">Home</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">Stock Opname</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">Stock Opname History</h1>
            <p class="page-subtitle">Manage stock taking sessions and inventory adjustments.</p>
        </div>
        <div class="action-bar-header">
            <a href="{{ route('stock-opnames.create') }}" class="btn btn-primary">
                <i data-feather="plus"></i> Start New Opname
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
                        <th>Date</th>
                        <th>Warehouse</th>
                        <th>Status</th>
                        <th>Notes</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($opnames as $opname)
                    <tr>
                        <td>{{ $opname->date->format('d M Y') }}</td>
                        <td>{{ $opname->warehouse->name }}</td>
                        <td>
                            @if($opname->status == 'completed')
                                <span class="badge bg-success-subtle text-success">Completed</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning">In Progress</span>
                            @endif
                        </td>
                        <td class="text-muted">{{ $opname->notes ?: '-' }}</td>
                        <td class="text-end">
                            <a href="{{ route('stock-opnames.show', $opname->id) }}" class="btn btn-icon btn-sm text-primary hover:bg-primary-subtle">
                                <i data-feather="eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="empty-state">
                                <div class="empty-icon"><i data-feather="clipboard"></i></div>
                                <p>No opname sessions found.</p>
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
