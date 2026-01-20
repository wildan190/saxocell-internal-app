@extends('layouts.app')

@section('title', 'Stores')

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <a href="{{ route('home') }}">Home</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">Stores</div>
</nav>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <div>
            <h1 class="page-title">Store Management</h1>
            <p class="page-subtitle">Manage your retail outlets and monitor distributed inventory.</p>
        </div>
        <div class="action-bar-header">
            <a href="{{ route('stores.create') }}" class="btn btn-primary">
                <i data-feather="plus"></i> Add Store
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i data-feather="check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Stores Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($stores as $store)
        <div class="card h-full hover:shadow-lg transition-all duration-300">
            <div class="card-body p-6 flex flex-col h-full">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
                        <i data-feather="shopping-bag" class="w-6 h-6"></i>
                    </div>
                    <div class="relative">
                        <button class="btn btn-icon btn-sm text-slate-400 hover:text-blue-600 dropdown-toggle">
                            <i data-feather="more-vertical"></i>
                        </button>
                        <div class="absolute right-0 top-full mt-1 w-48 bg-white rounded-xl shadow-lg border border-slate-100 z-10 hidden dropdown-menu">
                            <ul class="py-1">
                                <li>
                                    <a href="{{ route('stores.edit', $store) }}" class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-colors">
                                        <i data-feather="edit-2" class="w-4 h-4"></i> Edit Details
                                    </a>
                                </li>
                                <li>
                                    <form action="{{ route('stores.destroy', $store) }}" method="POST" onsubmit="return confirm('Delete this store?');">
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

                <a href="{{ route('stores.show', $store) }}" class="block mb-2">
                    <h3 class="text-xl font-bold text-slate-800 hover:text-blue-600 transition-colors">{{ $store->name }}</h3>
                </a>
                
                <p class="text-slate-500 text-sm mb-4 line-clamp-2 flex-grow">
                    {{ $store->description ?: 'No description provided.' }}
                </p>

                <div class="flex items-center gap-2 text-sm text-slate-600 bg-slate-50 p-3 rounded-lg mb-4">
                    <i data-feather="map-pin" class="w-4 h-4 text-slate-400"></i>
                    <span class="truncate">{{ $store->address ?: 'No address registered' }}</span>
                </div>

                <div class="mt-auto pt-4 border-t border-slate-100">
                    <a href="{{ route('stores.show', $store) }}" class="flex items-center justify-between text-blue-600 font-semibold hover:text-blue-700">
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
                    <i data-feather="shopping-bag"></i>
                </div>
                <h3>No Stores Found</h3>
                <p>Start by creating your first retail store to manage point-of-sale inventory.</p>
                <a href="{{ route('stores.create') }}" class="btn btn-primary mt-4">
                    <i data-feather="plus"></i> Create Store
                </a>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection