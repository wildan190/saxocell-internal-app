@extends('layouts.app')

@section('title', 'Warehouses')

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <a href="{{ route('home') }}">Home</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">Warehouses</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">Warehouse Management</h1>
            <p class="page-subtitle">Manage your physical storage locations and inventory distribution.</p>
        </div>
        <div class="action-bar-header">
             <a href="{{ route('warehouses.create') }}" class="btn btn-primary">
                <i data-feather="plus"></i> Add Warehouse
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i data-feather="check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Warehouses Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($warehouses as $warehouse)
        <div class="card h-full hover:shadow-lg transition-all duration-300">
            <div class="card-body p-6 flex flex-col h-full">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                        <i data-feather="archive" class="w-6 h-6"></i>
                    </div>
                    <div class="relative">
                        <button class="btn btn-icon btn-sm text-slate-400 hover:text-blue-600 dropdown-toggle">
                            <i data-feather="more-vertical"></i>
                        </button>
                        <div class="absolute right-0 top-full mt-1 w-48 bg-white rounded-xl shadow-lg border border-slate-100 z-10 hidden dropdown-menu">
                            <ul class="py-1">
                                <li>
                                    <a href="{{ route('warehouses.edit', $warehouse) }}" class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-colors">
                                        <i data-feather="edit-2" class="w-4 h-4"></i> Edit Details
                                    </a>
                                </li>
                                <li>
                                    <form action="{{ route('warehouses.destroy', $warehouse) }}" method="POST" onsubmit="return confirm('Delete this warehouse?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 transition-colors text-left">
                                            <i data-feather="trash-2" class="w-4 h-4"></i> Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <a href="{{ route('warehouses.show', $warehouse) }}" class="block mb-2">
                    <h3 class="text-xl font-bold text-slate-800 hover:text-blue-600 transition-colors">{{ $warehouse->name }}</h3>
                </a>
                
                <p class="text-slate-500 text-sm mb-4 line-clamp-2 flex-grow">
                    {{ $warehouse->description ?: 'No description provided.' }}
                </p>

                <div class="flex items-center gap-2 text-sm text-slate-600 bg-slate-50 p-3 rounded-lg mb-4">
                    <i data-feather="map-pin" class="w-4 h-4 text-slate-400"></i>
                    <span class="truncate">{{ $warehouse->address ?: 'No address registered' }}</span>
                </div>

                <div class="mt-auto pt-4 border-t border-slate-100">
                    <a href="{{ route('warehouses.show', $warehouse) }}" class="flex items-center justify-between text-blue-600 font-semibold hover:text-blue-700">
                        <span>View Inventory</span>
                        <i data-feather="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="empty-state">
                <div class="empty-icon">
                    <i data-feather="archive"></i>
                </div>
                <h3>No Warehouses Found</h3>
                <p>Get started by creating your first warehouse to manage stock locations.</p>
                <a href="{{ route('warehouses.create') }}" class="btn btn-primary mt-4">
                    <i data-feather="plus"></i> Create Warehouse
                </a>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
