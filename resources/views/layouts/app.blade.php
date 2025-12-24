<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #cbd5e1 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            color: #1e293b;
            display: flex;
        }

        .sidebar {
            width: 280px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(148, 163, 184, 0.2);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1000;
            transform: translateX(0);
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(148, 163, 184, 0.2);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-logo {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.25rem;
        }

        .sidebar-brand {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin-left: 0.75rem;
        }

        .menu-toggle,
        .sidebar-toggle {
            background: none;
            border: none;
            color: #64748b;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .menu-toggle:hover,
        .sidebar-toggle:hover {
            background: #f1f5f9;
            color: #1e293b;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-section {
            margin-bottom: 2rem;
        }

        .nav-section-title {
            padding: 0.5rem 1.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }

        .nav-item {
            margin: 0.25rem 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: #64748b;
            text-decoration: none;
            transition: all 0.2s ease;
            border-radius: 0 25px 25px 0;
            margin-right: 1rem;
        }

        .nav-link:hover,
        .nav-link.active {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
        }

        .nav-link.active {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
        }

        .nav-icon {
            width: 20px;
            height: 20px;
            margin-right: 0.75rem;
        }

        .main-content {
            flex: 1;
            margin-left: 280px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        .topbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(148, 163, 184, 0.2);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .menu-toggle,
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            color: #64748b;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .menu-toggle:hover,
        .sidebar-toggle:hover {
            background: #f1f5f9;
            color: #1e293b;
        }

        .sidebar-open-toggle {
            display: none;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border: none;
            color: white;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.2s ease;
            margin-right: 0.5rem;
        }

        .sidebar-open-toggle:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
        }

        .topbar-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-left: auto;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .breadcrumb {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin: 1.5rem 2rem 0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(148, 163, 184, 0.2);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .breadcrumb-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .breadcrumb-item a {
            color: inherit;
            text-decoration: none;
        }

        .breadcrumb-item a:hover {
            color: #3b82f6;
        }

        .breadcrumb-item.active {
            color: #1e293b;
            font-weight: 600;
        }

        .breadcrumb-item i {
            width: 16px;
            height: 16px;
        }

        .breadcrumb-separator {
            color: #94a3b8;
        }

        .breadcrumb-separator i {
            width: 14px;
            height: 14px;
        }

        .content-wrapper {
            flex: 1;
            padding: 2rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            font-size: 1.125rem;
            color: #64748b;
            margin: 0;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            backdrop-filter: blur(4px);
        }

        .overlay.active {
            display: block;
        }

        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.collapsed {
                transform: translateX(-100%);
            }

            .main-content {
                margin-left: 0;
            }

            .main-content.expanded {
                margin-left: 0;
            }

            .menu-toggle {
                display: flex;
            }

            .sidebar-open-toggle {
                display: flex;
            }

            .topbar-title {
                font-size: 1.25rem;
            }
        }

        @media (max-width: 768px) {
            .content-wrapper {
                padding: 1rem;
            }

            .breadcrumb {
                margin: 1rem;
                padding: 0.75rem 1rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .topbar {
                padding: 1rem;
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: 100%;
            }

            .breadcrumb {
                flex-wrap: wrap;
                gap: 0.25rem;
            }

            .breadcrumb-item {
                font-size: 0.75rem;
            }

            .page-title {
                font-size: 1.25rem;
            }
        }
    </style>
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
