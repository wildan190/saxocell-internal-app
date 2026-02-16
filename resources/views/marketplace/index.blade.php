@extends('marketplace.layout')

@section('content')
    <div class="text-center mb-12">
        <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight sm:text-5xl mb-4">Welcome to {{ $store->name }}</h1>
        <p class="text-lg text-slate-600 max-w-2xl mx-auto">{{ $store->description ?? 'Browse our exclusive collection of products.' }}</p>
    </div>

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
                                <button type="submit" class="p-2 bg-blue-50 text-blue-600 rounded-full hover:bg-blue-100 transition-colors">
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
            <h3 class="text-lg font-medium text-slate-900">No products available</h3>
            <p class="mt-1 text-slate-500">Check back later for new inventory.</p>
        </div>
    @endif
@endsection
