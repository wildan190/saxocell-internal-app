@extends('marketplace.layout')

@section('content')
    <div class="text-center mb-12">
        <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight sm:text-5xl mb-4">Welcome to {{ $store->name }}</h1>
        <p class="text-lg text-slate-600 max-w-2xl mx-auto">{{ $store->description ?? 'Browse our exclusive collection of products.' }}</p>
    </div>

    <!-- Search & Filter Bar -->
    <div class="mb-8 bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <form method="GET" action="{{ route('marketplace.index', $store->slug) }}" class="flex flex-col md:flex-row gap-4">
            <!-- Search Input -->
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i data-feather="search" class="w-5 h-5 text-slate-400"></i>
                </div>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $search ?? '' }}"
                    placeholder="Search products by name, SKU, or description..." 
                    class="block w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-slate-900 placeholder-slate-400 transition-all"
                >
            </div>

            <!-- Category Filter -->
            <div class="relative w-full md:w-64">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i data-feather="tag" class="w-5 h-5 text-slate-400"></i>
                </div>
                <select 
                    name="category" 
                    class="block w-full pl-12 pr-10 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-slate-900 appearance-none bg-white transition-all cursor-pointer"
                >
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ ($category ?? '') == $cat ? 'selected' : '' }}>
                            {{ ucwords($cat) }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                    <i data-feather="chevron-down" class="w-5 h-5 text-slate-400"></i>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                <button 
                    type="submit" 
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-200 active:scale-95 flex items-center gap-2"
                >
                    <i data-feather="filter" class="w-4 h-4"></i>
                    <span>Filter</span>
                </button>
                
                @if($search || $category)
                    <a 
                        href="{{ route('marketplace.index', $store->slug) }}" 
                        class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-all active:scale-95 flex items-center gap-2"
                    >
                        <i data-feather="x" class="w-4 h-4"></i>
                        <span>Clear</span>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Active Filters Display -->
    @if($search || $category)
        <div class="mb-6 flex flex-wrap items-center gap-3">
            <span class="text-sm font-bold text-slate-600">Active Filters:</span>
            
            @if($search)
                <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-sm font-medium border border-blue-200">
                    <i data-feather="search" class="w-3 h-3"></i>
                    Search: "{{ $search }}"
                    <a href="{{ route('marketplace.index', array_merge(request()->except('search'), ['category' => $category])) }}" class="ml-1 hover:text-blue-900">
                        <i data-feather="x" class="w-3 h-3"></i>
                    </a>
                </span>
            @endif
            
            @if($category)
                <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-purple-50 text-purple-700 rounded-lg text-sm font-medium border border-purple-200">
                    <i data-feather="tag" class="w-3 h-3"></i>
                    Category: {{ ucwords($category) }}
                    <a href="{{ route('marketplace.index', array_merge(request()->except('category'), ['search' => $search])) }}" class="ml-1 hover:text-purple-900">
                        <i data-feather="x" class="w-3 h-3"></i>
                    </a>
                </span>
            @endif
            
            <span class="text-sm text-slate-500">({{ $products->count() }} {{ Str::plural('product', $products->count()) }} found)</span>
        </div>
    @endif

    @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @foreach($products as $product)
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-shadow duration-300 overflow-hidden border border-slate-100 flex flex-col h-full">
                    <a href="{{ route('marketplace.product.show', ['slug' => $store->slug, 'product' => $product->id]) }}" class="block aspect-w-1 aspect-h-1 w-full overflow-hidden bg-gray-200 xl:aspect-w-7 xl:aspect-h-8">
                         @if($product->image)
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-64 object-cover object-center group-hover:opacity-75 transition-opacity">
                        @else
                            <div class="w-full h-64 bg-slate-100 flex items-center justify-center text-slate-300">
                                <i data-feather="image" class="w-12 h-12"></i>
                            </div>
                        @endif
                    </a>
                    <div class="p-6 flex flex-col flex-1">
                        <div class="flex-1">
                            @if($product->category)
                                <span class="inline-block px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg text-[10px] font-black uppercase tracking-widest mb-2">
                                    {{ $product->category }}
                                </span>
                            @endif
                            <h3 class="mt-1 text-lg font-bold text-slate-900">
                                <a href="{{ route('marketplace.product.show', ['slug' => $store->slug, 'product' => $product->id]) }}">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            <p class="mt-2 text-sm text-slate-500 line-clamp-2">{{ $product->description_1 }}</p>
                        </div>
                        <div class="mt-4 flex items-center justify-between">
                            <p class="text-xl font-black text-blue-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <form action="{{ route('marketplace.cart.add', ['slug' => $store->slug, 'product' => $product->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="p-2 bg-blue-50 text-blue-600 rounded-full hover:bg-blue-100 transition-colors" title="Add to cart">
                                    <i data-feather="plus" class="w-5 h-5"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-slate-200">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4">
                <i data-feather="box" class="w-8 h-8 text-slate-400"></i>
            </div>
            <h3 class="text-lg font-medium text-slate-900">
                @if($search || $category)
                    No products match your filters
                @else
                    No products available
                @endif
            </h3>
            <p class="mt-1 text-slate-500">
                @if($search || $category)
                    Try adjusting your search or filter criteria
                @else
                    Check back later for new inventory
                @endif
            </p>
            
            @if($search || $category)
                <a href="{{ route('marketplace.index', $store->slug) }}" class="mt-4 inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-200">
                    <i data-feather="refresh-cw" class="w-4 h-4"></i>
                    View All Products
                </a>
            @endif
        </div>
    @endif
@endsection
