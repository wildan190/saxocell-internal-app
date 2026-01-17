@extends('layouts.app')

@section('title', 'Create Warehouse')

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <a href="{{ route('home') }}">Home</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item">
        <a href="{{ route('warehouses.index') }}">Warehouses</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">Create</div>
</nav>
@endsection

@section('content')
<div class="p-4 md:p-8 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-2">Add New Warehouse</h1>
        <p class="text-slate-500">Register a new physical storage location for your inventory.</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-xl overflow-hidden">
        <form action="{{ route('warehouses.store') }}" method="POST">
            @csrf
            
            <div class="p-8 md:p-10 space-y-8">
                <!-- Name Section -->
                <div>
                    <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Warehouse Name <span class="text-red-500">*</span></label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400 @error('name') border-red-300 focus:border-red-500 focus:ring-red-500/20 @enderror"
                           value="{{ old('name') }}" 
                           placeholder="e.g. Central Distribution Center"
                           required>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i data-feather="alert-circle" class="w-3 h-3"></i> {{ $message }}</p>
                    @enderror
                </div>

                <!-- Address Section -->
                <div>
                    <label for="address" class="block text-sm font-bold text-slate-700 mb-2">Location Address</label>
                    <textarea name="address" 
                              id="address" 
                              rows="3" 
                              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400 @error('address') border-red-300 focus:border-red-500 focus:ring-red-500/20 @enderror"
                              placeholder="Full physical address...">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i data-feather="alert-circle" class="w-3 h-3"></i> {{ $message }}</p>
                    @enderror
                </div>

                <!-- Description Section -->
                <div>
                    <label for="description" class="block text-sm font-bold text-slate-700 mb-2">Description <span class="text-slate-400 font-normal">(Optional)</span></label>
                    <textarea name="description" 
                              id="description" 
                              rows="3" 
                              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400"
                              placeholder="Notes about capacity, type of goods, etc.">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-slate-50 p-8 flex items-center justify-end gap-4 border-t border-slate-100">
                <a href="{{ route('warehouses.index') }}" class="px-6 py-3 font-bold text-slate-600 hover:text-slate-800 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="flex items-center gap-2 px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-200 transition-all active:scale-95">
                    <i data-feather="check" class="w-5 h-5"></i> Create Warehouse
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
