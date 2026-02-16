@extends('marketplace.layout')

@section('content')
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-slate-900 mb-8 text-center">Checkout</h1>

        <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
            <div class="p-8 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Order Summary</h3>
                <ul class="divide-y divide-slate-100 text-sm">
                    @foreach($cart as $item)
                        <li class="py-2 flex justify-between">
                            <span>{{ $item['name'] }} (x{{ $item['quantity'] }})</span>
                            <span class="font-bold">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                        </li>
                    @endforeach
                    <li class="py-4 flex justify-between text-base border-t border-slate-100 mt-2 pt-4">
                        <span class="font-bold">Total</span>
                        <span class="font-black text-blue-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </li>
                </ul>
            </div>

            <form action="{{ route('marketplace.order.store', $store->slug) }}" method="POST" class="p-8 space-y-6">
                @csrf
                <div>
                    <label for="customer_name" class="block text-sm font-bold text-slate-700 mb-2">Full Name</label>
                    <input type="text" name="customer_name" id="customer_name" required class="w-full border-gray-300 rounded-xl focus:border-blue-500 focus:ring-blue-500 py-3">
                </div>

                <div>
                    <label for="customer_email" class="block text-sm font-bold text-slate-700 mb-2">Email Address</label>
                    <input type="email" name="customer_email" id="customer_email" required class="w-full border-gray-300 rounded-xl focus:border-blue-500 focus:ring-blue-500 py-3">
                </div>

                <div>
                    <label for="customer_phone" class="block text-sm font-bold text-slate-700 mb-2">Phone Number / WhatsApp</label>
                    <input type="text" name="customer_phone" id="customer_phone" required class="w-full border-gray-300 rounded-xl focus:border-blue-500 focus:ring-blue-500 py-3">
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-200 transition-all active:scale-95">
                        Place Order (Manual Transfer)
                    </button>
                    <p class="text-xs text-center text-slate-400 mt-4">By placing an order, you agree to make a manual bank transfer.</p>
                </div>
            </form>
        </div>
    </div>
@endsection
