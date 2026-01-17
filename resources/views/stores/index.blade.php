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
<div class="p-4 md:p-8 max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">Store Management</h1>
            <p class="text-slate-500 mt-2 flex items-center gap-2">
                Manage your retail outlets and monitor distributed inventory.
            </p>
        </div>
        
        <div>
            <a href="{{ route('stores.create') }}" class="flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-200 transition-all active:scale-95">
                <i data-feather="plus" class="w-4 h-4"></i> Add Store
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3">
            <i data-feather="check-circle" class="w-5 h-5 flex-shrink-0"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Stores Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($stores as $store)
        <div class="group bg-white rounded-[2rem] border border-slate-200/60 shadow-sm hover:shadow-xl hover:border-blue-200 transition-all duration-300 overflow-hidden">
            <div class="p-8">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-500 rounded-2xl flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform">
                        <i data-feather="shopping-bag" class="w-6 h-6"></i>
                    </div>
                    <div class="dropdown relative">
                        <button class="text-slate-400 hover:text-blue-600 transition-colors" data-bs-toggle="dropdown">
                            <i data-feather="more-vertical" class="w-5 h-5"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('stores.edit', $store) }}">Edit Details</a></li>
                            <li>
                                <form action="{{ route('stores.destroy', $store) }}" method="POST" onsubmit="return confirm('Delete this store?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-red-600">Delete</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

                <a href="{{ route('stores.show', $store) }}" class="block group-hover:translate-x-1 transition-transform duration-300">
                    <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-blue-600 transition-colors">{{ $store->name }}</h3>
                </a>
                
                <p class="text-slate-500 text-sm leading-relaxed mb-6 line-clamp-2 min-h-[2.5rem]">
                    {{ $store->description ?: 'No description provided.' }}
                </p>

                <div class="flex items-center gap-2 text-sm font-medium text-slate-600 bg-slate-50 p-3 rounded-xl">
                    <i data-feather="map-pin" class="w-4 h-4 text-slate-400"></i>
                    <span class="truncate">{{ $store->address ?: 'No address registered' }}</span>
                </div>
            </div>

            <div class="bg-slate-50/50 p-6 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('stores.show', $store) }}" class="w-full flex items-center justify-center gap-2 text-blue-600 font-bold text-sm hover:underline">
                    View Inventory <i data-feather="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-slate-50 rounded-[2rem] border-2 border-dashed border-slate-200 p-12 text-center">
                <div class="w-16 h-16 bg-white text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                    <i data-feather="shopping-bag" class="w-8 h-8"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-700 mb-2">No Stores Found</h3>
                <p class="text-slate-500 mb-6 max-w-md mx-auto">Start by creating your first retail store to manage point-of-sale inventory.</p>
                <a href="{{ route('stores.create') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 text-white rounded-xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all">
                    <i data-feather="plus" class="w-4 h-4"></i> Create Store
                </a>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
