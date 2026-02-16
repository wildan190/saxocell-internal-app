@extends('layouts.app')

@section('title', 'Order #' . $order->invoice_number)

@section('breadcrumb')
<nav class="breadcrumb">
    <div class="breadcrumb-item">
        <i data-feather="home"></i>
        <a href="{{ route('home') }}">Home</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item">
        <a href="{{ route('stores.index') }}">Stores</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item">
        <a href="{{ route('stores.show', $store) }}">{{ $store->name }}</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item">
        <a href="{{ route('stores.orders.index', $store) }}">Orders</a>
    </div>
    <span class="breadcrumb-separator"><i data-feather="chevron-right"></i></span>
    <div class="breadcrumb-item active">{{ $order->invoice_number }}</div>
</nav>
@endsection

@section('content')
<div class="main-content-inner content-wrapper bg-white lg:bg-transparent">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-purple-50 text-purple-600 rounded-full text-[10px] font-black uppercase tracking-widest mb-4">
                <i data-feather="shopping-cart" class="w-3 h-3"></i> Online Order
            </div>
            <h1 class="text-3xl md:text-5xl font-extrabold text-slate-900 tracking-tight">{{ $order->invoice_number }}</h1>
            <p class="text-slate-500 mt-2 flex items-center gap-2">
                <i data-feather="calendar" class="w-4 h-4"></i>
                {{ $order->created_at->format('d F Y, H:i') }}
            </p>
        </div>
        
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('stores.orders.index', $store) }}" class="flex items-center gap-2 px-6 py-2.5 bg-white border border-slate-200 hover:border-slate-300 text-slate-700 rounded-xl font-bold shadow-sm transition-all active:scale-95 leading-none">
                <i data-feather="arrow-left" class="w-4 h-4 text-slate-400"></i> Back to Orders
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-8">
            <i data-feather="check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error mb-8">
            <i data-feather="alert-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Order Content --><div class="lg:col-span-8 space-y-8">
            <!-- Items Ordered -->
            <div class="bg-white rounded-[2.5rem] border border-slate-200/60 shadow-xl overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center gap-3">
                        <span class="w-8 h-8 bg-slate-900 text-white rounded-lg flex items-center justify-center">
                            <i data-feather="package" class="w-4 h-4"></i>
                        </span>
                        Items Ordered
                    </h3>
                </div>

                <div class="p-8">
                    <ul class="divide-y divide-slate-100">
                        @foreach($order->items as $item)
                            <li class="py-5 flex justify-between items-center">
                                <div class="flex items-center gap-4">
                                    @if($item->product && $item->product->image)
                                        <img src="{{ Storage::url($item->product->image) }}" class="w-16 h-16 rounded-2xl object-cover bg-slate-100 border border-slate-200 shadow-sm">
                                    @else
                                        <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center border border-slate-200">
                                            <i data-feather="box" class="w-6 h-6 text-slate-300"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $item->product->name ?? 'Unknown Product' }}</p>
                                        <p class="text-sm text-slate-500 font-medium">{{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <span class="font-bold text-slate-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </li>
                        @endforeach
                    </ul>
                    
                    <div class="mt-6 pt-6 border-t border-slate-200 flex justify-between items-center">
                        <span class="text-sm font-black text-slate-400 uppercase tracking-widest">Total Amount</span>
                        <span class="text-2xl font-black text-purple-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-white rounded-[2.5rem] border border-slate-200/60 shadow-xl overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center gap-3">
                        <span class="w-8 h-8 bg-slate-900 text-white rounded-lg flex items-center justify-center">
                            <i data-feather="user" class="w-4 h-4"></i>
                        </span>
                        Customer Information
                    </h3>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-[10px] text-slate-400 uppercase font-black tracking-widest mb-2">Name</p>
                        <p class="font-bold text-slate-900">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 uppercase font-black tracking-widest mb-2">Phone Number</p>
                        <p class="font-bold text-slate-900">{{ $order->customer_phone }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-[10px] text-slate-400 uppercase font-black tracking-widest mb-2">Email Address</p>
                        <p class="font-bold text-slate-900">{{ $order->customer_email }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-[10px] text-slate-400 uppercase font-black tracking-widest mb-2">Shipping Address</p>
                        <p class="font-medium text-slate-600 leading-relaxed">{{ $order->shipping_address }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar --><div class="lg:col-span-4 space-y-8">
            <!-- Payment Proof -->
            <div class="bg-white rounded-[2rem] border border-slate-200/60 shadow-sm p-8">
                <h3 class="text-xs font-black text-slate-800 uppercase tracking-[0.2em] mb-6 flex items-center gap-3">
                    <span class="w-6 h-6 bg-slate-900 text-white rounded-md flex items-center justify-center">
                        <i data-feather="image" class="w-3 h-3"></i>
                    </span>
                    Payment Proof
                </h3>
                
                @if($order->payment_proof_path)
                    <a href="{{ Storage::url($order->payment_proof_path) }}" target="_blank" class="block mb-4 group">
                        <img src="{{ Storage::url($order->payment_proof_path) }}" alt="Payment Proof" class="w-full rounded-2xl border border-slate-200 group-hover:opacity-90 transition-opacity shadow-sm">
                    </a>
                    <p class="text-xs text-center text-slate-500 mb-6 font-medium">Click to view full size</p>
                @else
                    <div class="aspect-video bg-slate-50/50 rounded-2xl border border-dashed border-slate-200 flex items-center justify-center text-slate-400 mb-6">
                        <div class="text-center">
                            <i data-feather="image" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                            <span class="text-sm font-medium">No proof uploaded</span>
                        </div>
                    </div>
                @endif

                <div class="space-y-3">
                    @if($order->status === 'pending_confirmation' || $order->status === 'pending_payment')
                        <form action="{{ route('stores.orders.confirm', ['store' => $store->id, 'order' => $order->id]) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Confirm this order and payment?')" class="w-full flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 rounded-xl transition-all shadow-lg shadow-purple-200 active:scale-95">
                                <i data-feather="check-circle" class="w-4 h-4"></i>
                                Confirm Payment
                            </button>
                        </form>
                    @endif

                    @if($order->status !== 'cancelled' && $order->status !== 'completed')
                        <form action="{{ route('stores.orders.reject', ['store' => $store->id, 'order' => $order->id]) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Reject this order? Stock will be restored.')" class="w-full flex items-center justify-center gap-2 bg-white border-2 border-rose-200 text-rose-600 hover:bg-rose-50 font-bold py-3 rounded-xl transition-all active:scale-95">
                                <i data-feather="x-circle" class="w-4 h-4"></i>
                                Reject Order
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            
            <!-- Order Status -->
            <div class="bg-slate-900 rounded-[2rem] p-8 shadow-xl shadow-slate-200">
                <h3 class="text-xs font-black text-white/50 uppercase tracking-[0.2em] mb-6">Current Status</h3>
                <div class="bg-white/10 border border-white/10 p-5 rounded-2xl">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Order Status</p>
                    <p class="text-2xl font-black text-white capitalize">
                        {{ str_replace('_', ' ', $order->status) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>
@endpush
