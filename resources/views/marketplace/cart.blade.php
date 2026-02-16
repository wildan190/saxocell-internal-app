@extends('marketplace.layout')

@section('content')
    <h1 class="text-3xl font-bold text-slate-900 mb-8">Shopping Cart</h1>

    @if(count($cart) > 0)
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-8">
                <ul class="divide-y divide-slate-100">
                    @foreach($cart as $id => $item)
                        <li class="py-6 flex items-center">
                            @if($item['image'])
                                <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}" class="h-24 w-24 flex-none rounded-xl bg-slate-100 object-cover object-center">
                            @else
                                <div class="h-24 w-24 flex-none rounded-xl bg-slate-100 flex items-center justify-center text-slate-300">
                                    <i data-feather="image" class="w-8 h-8"></i>
                                </div>
                            @endif
                            <div class="ml-6 flex-auto">
                                <h3 class="font-bold text-slate-900">{{ $item['name'] }}</h3>
                                <p class="text-slate-500">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-slate-900 mb-2">Qty: {{ $item['quantity'] }}</p>
                                <form action="{{ route('marketplace.cart.remove', ['slug' => $store->slug, 'id' => $id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-rose-500 font-bold hover:text-rose-700">Remove</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="bg-slate-50 p-8 border-t border-slate-200 flex justify-between items-center">
                <div>
                    <p class="text-sm text-slate-500">Total Amount</p>
                    <p class="text-2xl font-black text-slate-900">Rp {{ number_format($total, 0, ',', '.') }}</p>
                </div>
                <a href="{{ route('marketplace.checkout', $store->slug) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-blue-200 transition-colors">
                    Proceed to Checkout
                </a>
            </div>
        </div>
    @else
        <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-slate-200">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4">
                <i data-feather="shopping-cart" class="w-8 h-8 text-slate-400"></i>
            </div>
            <h3 class="text-lg font-medium text-slate-900">Your cart is empty</h3>
            <p class="mt-1 text-slate-500 mb-8">Browse our products and add items to your cart.</p>
            <a href="{{ route('marketplace.index', $store->slug) }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-bold rounded-xl text-blue-700 bg-blue-100 hover:bg-blue-200">
                Start Shopping
            </a>
        </div>
    @endif
@endsection
