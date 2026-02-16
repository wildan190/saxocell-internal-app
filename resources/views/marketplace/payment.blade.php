@extends('marketplace.layout')

@section('content')
    <div class="max-w-2xl mx-auto text-center">
        <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden p-10">
            @if($order->status == 'pending_payment')
                <div class="mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-50 mb-6">
                        <i data-feather="credit-card" class="w-10 h-10 text-blue-600"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-slate-900 mb-2">Payment Required</h1>
                    <p class="text-slate-500">Please transfer the total amount to complete your order.</p>
                </div>

                <div class="bg-slate-50 rounded-2xl p-6 mb-8 text-left">
                    <div class="flex justify-between mb-2">
                        <span class="text-slate-500">Invoice Number</span>
                        <span class="font-mono font-bold">{{ $order->invoice_number }}</span>
                    </div>
                    <div class="flex justify-between mb-4">
                        <span class="text-slate-500">Total Amount</span>
                        <span class="font-black text-xl text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="pt-4 border-t border-slate-200">
                        <p class="font-bold text-slate-900 mb-2">Bank Transfer Details:</p>
                        <p class="text-sm text-slate-600">Bank BCA: 1234567890 (Saxocell)</p>
                        <p class="text-sm text-slate-600">Bank Mandiri: 0987654321 (Saxocell)</p>
                    </div>
                </div>

                <form action="{{ route('marketplace.payment.upload', ['slug' => $store->slug, 'order' => $order->id]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="border-2 border-dashed border-slate-300 rounded-2xl p-8 hover:border-blue-500 transition-colors cursor-pointer relative">
                        <input type="file" name="payment_proof" id="payment_proof" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required onchange="document.getElementById('file-label').innerText = this.files[0].name">
                        <div class="text-center pointer-events-none">
                            <i data-feather="upload-cloud" class="w-10 h-10 text-slate-400 mx-auto mb-4"></i>
                            <p id="file-label" class="text-slate-600 font-medium">Click to upload payment proof (Image)</p>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-200 transition-all active:scale-95">
                        Upload Payment Proof
                    </button>
                </form>
            @elseif($order->status == 'pending_confirmation')
                <div class="py-10">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-yellow-50 mb-6">
                        <i data-feather="clock" class="w-10 h-10 text-yellow-600"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-slate-900 mb-4">Waiting for Confirmation</h1>
                    <p class="text-slate-600 max-w-md mx-auto">Thank you! We have received your payment proof. We will verify it and process your order shortly.</p>
                    <div class="mt-8">
                        <a href="{{ route('marketplace.index', $store->slug) }}" class="text-blue-600 font-bold hover:underline">Continue Shopping</a>
                    </div>
                </div>
            @elseif($order->status == 'processing' || $order->status == 'completed')
                <div class="py-10">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-green-50 mb-6">
                        <i data-feather="check-circle" class="w-10 h-10 text-green-600"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-slate-900 mb-4">Order Confirmed!</h1>
                    <p class="text-slate-600 max-w-md mx-auto">Your order is being processed. Thank you for shopping with us!</p>
                    <div class="mt-8">
                        <a href="{{ route('marketplace.index', $store->slug) }}" class="text-blue-600 font-bold hover:underline">Continue Shopping</a>
                    </div>
                </div>
            @elseif($order->status == 'cancelled')
                <div class="py-10">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-red-50 mb-6">
                        <i data-feather="x-circle" class="w-10 h-10 text-red-600"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-slate-900 mb-4">Order Cancelled</h1>
                    <p class="text-slate-600 max-w-md mx-auto">This order has been cancelled. Please contact support if you have questions.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
