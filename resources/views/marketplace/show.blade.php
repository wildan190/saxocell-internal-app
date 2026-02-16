@extends('marketplace.layout')

@section('content')
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-100">
        <div class="grid grid-cols-1 lg:grid-cols-2">
            <!-- Image Section -->
            <div class="p-8 bg-slate-50 flex items-center justify-center">
                 @if($product->image)
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="max-h-[500px] w-auto object-contain rounded-xl shadow-lg">
                @else
                    <div class="w-full h-96 bg-white rounded-xl shadow-inner flex items-center justify-center text-slate-300">
                        <i data-feather="image" class="w-24 h-24"></i>
                    </div>
                @endif
            </div>

            <!-- Details Section -->
            <div class="p-8 lg:p-12 flex flex-col justify-center">
                <div class="mb-6">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 uppercase tracking-wide">
                        {{ $product->category ?? 'Product' }}
                    </span>
                </div>
                
                <h1 class="text-3xl lg:text-4xl font-black text-slate-900 mb-4">{{ $product->name }}</h1>
                <p class="text-3xl font-black text-blue-600 mb-8">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                <div class="prose prose-slate mb-8 text-slate-600">
                    <h3 class="font-bold text-slate-900">Description</h3>
                    <p>{{ $product->description_1 }}</p>

                    @if($product->description_2)
                        <div class="my-4">
                            <p>{{ $product->description_2 }}</p>
                        </div>
                    @endif

                    @if($product->description_3)
                        <div class="my-4">
                            <p>{{ $product->description_3 }}</p>
                        </div>
                    @endif
                </div>

                <!-- Stock Status -->
                <div class="flex items-center mb-8 pb-8 border-b border-slate-100">
                    @if($quantity > 0)
                        <div class="flex items-center text-emerald-600 font-bold">
                            <i data-feather="check-circle" class="w-5 h-5 mr-2"></i>
                            In Stock ({{ $quantity }} available)
                        </div>
                    @else
                        <div class="flex items-center text-rose-500 font-bold">
                            <i data-feather="x-circle" class="w-5 h-5 mr-2"></i>
                            Out of Stock
                        </div>
                    @endif
                </div>

                <!-- Add to Cart -->
                <form action="{{ route('marketplace.cart.add', ['slug' => $store->slug, 'product' => $product->id]) }}" method="POST" class="flex gap-4">
                    @csrf
                    <div class="w-24">
                        <label for="quantity" class="sr-only">Quantity</label>
                        <input type="number" name="quantity" id="quantity" min="1" max="{{ $quantity }}" value="1" 
                               class="w-full border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 font-bold text-center py-3"
                               {{ $quantity == 0 ? 'disabled' : '' }}>
                    </div>
                    <button type="submit" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-blue-200 transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                            {{ $quantity == 0 ? 'disabled' : '' }}>
                        <i data-feather="shopping-cart" class="w-5 h-5"></i>
                        Add to Cart
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
