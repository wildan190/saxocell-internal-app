<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $store->name }} - Marketplace</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="bg-gray-50 text-slate-800 font-sans antialiased min-h-screen flex flex-col">
    
    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('marketplace.index', $store->slug) }}" class="text-xl font-bold text-blue-600 flex items-center gap-2">
                        <i data-feather="shopping-bag" class="w-6 h-6"></i>
                        {{ $store->name }}
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('marketplace.cart', $store->slug) }}" class="relative p-2 hover:bg-gray-100 rounded-full transition-colors">
                        <i data-feather="shopping-cart" class="w-6 h-6 text-slate-600"></i>
                         @php
                            $cart = session('cart_' . $store->id, []);
                            $count = count($cart);
                        @endphp
                        @if($count > 0)
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full">{{ $count }}</span>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-8 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-8 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-slate-600 text-sm font-medium">
                    &copy; {{ date('Y') }} <span class="font-bold text-slate-900">{{ $store->name }}</span>. All rights reserved.
                </div>
                <div class="flex items-center gap-2 text-sm text-slate-500">
                    <i data-feather="map-pin" class="w-4 h-4"></i>
                    <span>{{ $store->address ?: 'Online Store' }}</span>
                </div>
            </div>
        </div>
    </footer>

    <script>
        feather.replace();
    </script>
</body>
</html>
