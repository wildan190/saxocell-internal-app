<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=rubik:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div style="display: flex; align-items: center;">
                <div class="sidebar-logo">{{ substr(config('app.name', 'Laravel'), 0, 1) }}</div>
                <span class="sidebar-brand">{{ config('app.name', 'Laravel') }}</span>
            </div>
            <button class="sidebar-toggle" id="sidebarToggle">
                <i data-feather="x"></i>
            </button>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Main</div>
                <div class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <i data-feather="home" class="nav-icon"></i>
                        Dashboard
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <i data-feather="package" class="nav-icon"></i>
                        Products
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                        <i data-feather="users" class="nav-icon"></i>
                        Suppliers
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('inventory.index') }}" class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                        <i data-feather="clipboard" class="nav-icon"></i>
                        Inventory
                    </a>
                </div>
                
                <div class="nav-section-title">Procurement</div>
                <div class="nav-item">
                    <a href="{{ route('purchase-orders.index') }}" class="nav-link {{ request()->routeIs('purchase-orders.*') ? 'active' : '' }}">
                        <i data-feather="shopping-bag" class="nav-icon"></i>
                        Purchase Orders
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('delivery-orders.index') }}" class="nav-link {{ request()->routeIs('delivery-orders.*') ? 'active' : '' }}">
                        <i data-feather="package" class="nav-icon"></i>
                        Delivery Orders
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('invoices.index') }}" class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                        <i data-feather="file-text" class="nav-icon"></i>
                        Invoices
                    </a>
                </div>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <header class="topbar">
            <button class="menu-toggle" id="menuToggle">
                <i data-feather="menu"></i>
            </button>
            <button class="sidebar-open-toggle" id="sidebarOpenToggle" style="display: none;">
                <i data-feather="sidebar"></i>
            </button>
            <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
            <div class="user-menu">
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            </div>
        </header>

        @yield('breadcrumb')

        @yield('content')
    </div>

    <script>
        // Initialize sidebar functionality immediately
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const overlay = document.getElementById('overlay');
            const menuToggle = document.getElementById('menuToggle');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOpenToggle = document.getElementById('sidebarOpenToggle');

            // Cache DOM queries for better performance
            let isInitialized = false;

            function toggleSidebar() {
                if (!isInitialized) return;

                const isCollapsed = sidebar.classList.contains('collapsed');

                if (isCollapsed) {
                    // Opening sidebar
                    sidebar.classList.remove('collapsed');
                    overlay.classList.remove('active');
                    if (window.innerWidth > 1024) {
                        mainContent.classList.remove('expanded');
                    }
                    menuToggle.style.display = 'none';
                    sidebarOpenToggle.style.display = 'none';
                } else {
                    // Closing sidebar - show menu toggle for closing, hide sidebar open toggle
                    sidebar.classList.add('collapsed');
                    if (window.innerWidth <= 1024) {
                        overlay.classList.add('active');
                    }
                    if (window.innerWidth > 1024) {
                        mainContent.classList.add('expanded');
                    }
                    menuToggle.style.display = 'flex';
                    sidebarOpenToggle.style.display = 'none';
                }
            }

            function openSidebar() {
                if (!isInitialized) return;

                sidebar.classList.remove('collapsed');
                overlay.classList.remove('active');
                if (window.innerWidth > 1024) {
                    mainContent.classList.remove('expanded');
                }
                menuToggle.style.display = 'none';
                sidebarOpenToggle.style.display = 'none';
            }

            // Add event listeners
            menuToggle.addEventListener('click', toggleSidebar);
            sidebarToggle.addEventListener('click', toggleSidebar);
            sidebarOpenToggle.addEventListener('click', openSidebar);
            overlay.addEventListener('click', toggleSidebar);

            // Handle responsive behavior with debouncing
            let resizeTimeout;
            function handleResize() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => {
                    if (window.innerWidth <= 1024) {
                        sidebar.classList.add('collapsed');
                        overlay.classList.add('active');
                        menuToggle.style.display = 'flex';
                        sidebarOpenToggle.style.display = 'none';
                    } else {
                        sidebar.classList.remove('collapsed');
                        overlay.classList.remove('active');
                        mainContent.classList.remove('expanded');
                        menuToggle.style.display = 'none';
                        sidebarOpenToggle.style.display = 'none';
                    }
                }, 100);
            }

            window.addEventListener('resize', handleResize);

            // Initial setup
            handleResize();
            isInitialized = true;
        });

    </script>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
